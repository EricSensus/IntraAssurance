<?php

namespace Jenga\MyProject\Accident\Repositories;

use Jenga\App\Core\App;
use Jenga\App\Request\Session;
use Jenga\MyProject\Accident\Models\PersonalCoverPricing;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

/**
 * Class AccidentQuotation
 * @package Jenga\MyProject\Accident\Repositories
 */
class AccidentQuotation
{

    /**
     * Subscriber ID
     * @var int
     */
    protected $customer_id;
    /**
     * @var CustomersController
     */
    private $_customer;
    /**
     * @var EntitiesController
     */
    private $_entities;
    /**
     * @var QuotesController
     */
    private $_quotes;
    /**
     * @var PersonalCoverPricing
     */
    protected $_pricing_model;

    /**
     * Constructor
     * @param null $quote
     */
    public function __construct($quote = null)
    {
        $this->quote = $quote;
    }

    /**
     * Get a recent new quote calculation and update database if any changes
     * @return $this
     */
    public function getQuote()
    {
        $this->setUpEntities();
        $this->setUpValues();
        $this->calculateValues();
        $this->updateQuote();
        return $this;
    }

    /**
     * Set up Values used to generate quotation
     */
    private function setUpValues()
    {
        if (empty($this->quote)) {
            $this->customer_id = Session::get('customer_id');
            $this->customer = $this->_customer->getCustomerById($this->customer_id, null);
            $this->quote = $this->_quotes->getQuoteById(Session::get('quote_id'));
            $this->main_entity = $this->_entities->getEntityDataByfinder(Session::get('main_id'));
            $others = unserialize(Session::get('other_id'));
        } else {
            $this->customer_id = $this->quote->customers_id;
            $this->customer = $this->_customer->getCustomerById($this->customer_id, null);
            $__id = json_decode($this->quote->customer_entity_data_id);
            $this->main_entity = $this->_entities->getEntityDataByfinder($__id[0]);
            $others = array_except($__id, 0);

        }
        $all = [];
        foreach ($others as $other) {
            $all[] = $this->_entities->getEntityDataByfinder($other);
        }
        $this->other_entities = $all;
    }

    /**
     * Set up entities
     * @return $this
     */
    private function setUpEntities()
    {
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entities = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        $this->_pricing_model = App::get(PersonalCoverPricing::class);
        return $this;
    }

    /**
     * Perform database quote updates on the amount column
     */
    private function updateQuote()
    {
        $available = get_object_vars($this);
        $to_save = array_only($available, $this->quoteFields());
        $others = [];
        foreach ($this->others as $en) {
            $others[$en->name] = array_only(get_object_vars($en), $this->quoteFields());
        }
        $mine = $this->_quotes->model->find($this->quote->id);
        $to_save = array_prepend($to_save, $others, 'other_covers');
        $to_save = array_prepend($to_save, 14, 'insurer_id');
        $total = json_encode($to_save);
        $mine->amount = $total;
        $mine->save();
    }

    /**
     * Calibrate the age brackets relevant to calculations
     * @param $range
     * @return string
     */
    private function formatAgeBracket($range)
    {
        switch ($range) {
            case '3 - 17':
                $age_bracket = '3-17';
                break;
            case '18-21':
            case '19-30':
            case '22-25':
            case '26-30':
            case '31-40':
            case '41-50':
            case '51-60':
                $age_bracket = '18-55';
                break;
            case '61 - 69':
            case '70 or over':
                $age_bracket = '56-69';
                break;
            default:
                $age_bracket = '18-55';
                break;
        }
        return $age_bracket;
    }

    /**
     * Use already set values to calculate the values for the quote
     */
    private function calculateValues()
    {
        $more_info = json_decode($this->customer->additional_info);
        $quote = json_decode($this->quote->product_info);
        $this->band = $quote->cover_type;
        $this->class = $quote->cover_class;
        //process the age bracket
        $this->age_bracket = $this->formatAgeBracket($more_info->age_bracket);
        $this->premium_rate = $this->_payments($this->band, $this->age_bracket, $this->class);
        $this->levy = $this->getRates($this->premium_rate, 'Training Levy', 'Travel');
        $this->levy_rate = $this->returnRate('Training Levy', 'Travel');
        $this->sub_total = $this->premium_rate + $this->levy;
        $this->other_covers = null;
        $this->others = [];
        $_total = 0;
        if (($quote->other_covers === 'yes') && ($quote->howmany > 0)) {
            $this->other_covers = intval($quote->howmany);
            $premium2 = 0;
            foreach ($this->other_entities as $_entity) {
                $each = new \stdClass();
                $entity = json_decode($_entity->entity_values);
                $each->name = $entity->other_name;
                $each->relationship = $entity->other_relationship;
                $each->age_bracket = $entity->other_bracket;
                $each->education = $entity->other_education;
                $each->band = $entity->other_band;
                $each->class = $entity->other_class;

                $f_bracket = $this->formatAgeBracket($each->age_bracket);
                $each->premium_rate = $this->_payments($each->band, $f_bracket, $each->class);

                //get the training levy
                $each->levy = $this->getRates($each->premium_rate, 'Training Levy', 'Travel');

                //get the policy fund
                $each->policy_fund = $this->getRates($each->premium_rate, 'P.H.C.F Fund', 'Travel');

                //get the stamp duty
                $each->stamp_duty = $this->getRates($each->premium_rate, 'Stamp Duty', 'Travel');

                //get the total
                $each->total = ($each->premium_rate + $each->levy);
                $_total += $each->total;
                $premium2 += $each->premium_rate;
                $this->others[] = $each;
            }
            $this->other_total = $_total;
            //get the policy fund
            $this->policy_fund = $this->getRates(($this->premium_rate + $premium2), 'P.H.C.F Fund', 'Travel');
            //get the stamp duty
            $this->stamp_duty = $this->getRates($this->premium_rate, 'Stamp Duty', 'Travel');
            $this->basic_premium = $this->sub_total + $this->policy_fund + $this->stamp_duty;
            $this->total = $this->basic_premium + $this->other_total;
        } else {
            //get the policy fund
            $this->policy_fund = $this->getRates($this->premium_rate, 'P.H.C.F Fund', 'Travel');
            //get the stamp duty
            $this->stamp_duty = $this->getRates($this->premium_rate, 'Stamp Duty', 'Travel');
            //get the total
            $this->basic_premium = ($this->premium_rate + $this->levy + $this->policy_fund + $this->stamp_duty);
            $this->total = $this->basic_premium;
        }

    }


    /**
     * Fetch premium rate values
     * @param $_band
     * @param $_age_bracket
     * @param $_class
     * @return double
     */
    private function _payments($_band, $_age_bracket, $_class)
    {
        $class = "C1";
        if ($_class == "class2") {
            $class = "C2";
        }
        switch ($_band) {
            case "band1":
                $band = "B1";
                break;
            case "band2":
                $band = "B2";
                break;
            case "band3":
                $band = "B3";
                break;
            case "band4":
                $band = "B4";
                break;
            case "band5":
                $band = "B5";
                break;
            case "band6":
                $band = "B6";
                break;
            case "band7":
                $band = "B7";
                break;
            default:
                $band = "B1";
        }

        switch ($_age_bracket) {
            case "56-69":
                $age_bracket = "A3";
                break;
            case "18-55":
                $age_bracket = "A2";
                break;
            default:
                $age_bracket = "A1";
        }
        $result = $this->_pricing_model->where('age_bracket', $age_bracket)->where('class', $class)->where('band', $band);
        if (count($result->show(1)) == 1)
            return $result->show(1)[0]->premium;
        return null;
    }

    /**
     * Calculate the rates to use in calculations for quote
     * @param $tsi
     * @param $rate_name
     * @param $category
     * @param null $rate_type
     * @return float|int
     */
    private function getRates($tsi, $rate_name, $category, $rate_type = null)
    {
        if (!($this->rates instanceof \stdClass)) {
            $this->rates = new \stdClass();
        }
        $rates = $this->_customer->model->table('rates');

        if (empty($rate_type)) {
            $rate = $rates->where('rate_category', $category)->where('rate_name', $rate_name)->first();
        } else {
            $rate = $rates->where('rate_category', $category)->where('rate_name', $rate_name)->where('rate_type', $rate_type)->first();
        }
        if ($rate->rate_type == 'Percentage') {
            $computed_value = (($tsi * $rate->rate_value) / 100);
        } elseif ($rate->rate_type == 'Fixed') {
            $computed_value = $rate->rate_value;
        }
        $this->rates->{$this->strClean($category . " " . $rate_name)} = ['rate' => $rate->rate_value, 'type' => $rate->rate_type];
        return $computed_value;
    }

    /**
     * Perform a clean string like urls
     * @param $text
     * @return string
     */
    private function strClean($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        return (empty($text)) ? 'n-a' : $text;
    }

    /**
     * Special rate for calculations
     * @param $rate_name
     * @param $category
     * @return string
     */
    private function returnRate($rate_name, $category)
    {
        $rates = $this->_customer->model->table('rates');
        $rate_model = $rates->find(['rate_category' => $category, 'rate_name' => $rate_name]);
        return ($rate_model->rate_type == 'Percentage' ? $rate_model->rate_value . '%' : $rate_model->rate_value);
    }

    /**
     * Important fields to show in quote total
     * @return array
     */
    public function quoteFields()
    {
        return ['policy_fund', 'stamp_duty', 'total', 'other_total', 'basic_premium'];
    }
}