<?php
namespace Jenga\MyProject\Accident\Repositories;

use Jenga\App\Core\App;
use Jenga\App\Request\Session;
use Jenga\MyProject\Accident\Models\PersonalCoverPricing;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

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
     */
    public function __construct()
    {
        $this->customer_id = Session::get('customer_id');
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entities = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        $this->_pricing_model = App::get(PersonalCoverPricing::class);
        $this->customer = $this->_customer->getCustomerById($this->customer_id, null);
        $this->quote = $this->_quotes->getQuoteById(Session::get('quote_id'));
        $this->main_entity = $this->_entities->getEntityDataByfinder(Session::get('main_id'));
        $others = unserialize(Session::get('other_id'));
        $all = [];
        foreach ($others as $other) {
            $all[] = $this->_entities->getEntityDataByfinder($other);
        }
        $this->other_entities = $all;
        $this->calculateValues();
        $this->updateQuote();
        unset($this->_customer, $this->_entities, $this->_quotes, $this->_pricing_model);
    }


    private function updateQuote()
    {
        if (!empty($this->quote->amount)) {
            return;
        }
        $copy = clone $this;
        $mine = $this->_quotes->model->find($this->quote->id);
        unset($copy->quote, $copy->customer, $copy->main_entity, $copy->other_entities, $copy->rates, $copy->customer_id);
        unset($copy->_customer, $copy->_entities, $copy->_quotes,$copy->_pricing_model);
        $copy->insurer_id = 14;//@todo Get insurer id
        $total = json_encode(get_object_vars($copy));
        $mine->amount = $total;
        $mine->save();
    }

    private function calculateValues()
    {
        $more_info = json_decode($this->customer->additional_info);
        $quote = json_decode($this->quote->product_info);
        $this->band = $quote->cover_type;
        $this->class = $quote->cover_class;
        //process the age bracket
        switch ($more_info->age_bracket) {
            case '3 - 17':
                $this->age_bracket = '3-17';
                break;
            case '18-21':
            case '19-30':
            case '22-25':
            case '26-30':
            case '31-40':
            case '41-50':
            case '51-60':
                $this->age_bracket = '18-55';
                break;
            case '61 - 69':
            case '70 or over':
                $this->age_bracket = '56-69';
                break;
            default:
                $this->age_bracket = $this->step1->age_bracket;
                break;
        }
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

                $each->premium_rate = $this->_payments($each->band, $each->age_bracket, $each->class);

                //get the training levy
                $each->levy = $this->_payments($each->premium_rate, 'Training Levy', 'Travel');

                //get the policy fund
                $each->policy_fund = $this->_payments($each->premium_rate, 'P.H.C.F Fund', 'Travel');

                //get the stamp duty
                $each->stamp_duty = $this->_payments($each->premium_rate, 'Stamp Duty', 'Travel');

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
            $this->total = $this->sub_total + $this->other_total + $this->policy_fund + $this->stamp_duty;
        } else {
            //get the policy fund
            $this->policy_fund = $this->getRates($this->premium_rate, 'P.H.C.F Fund', 'Travel');
            //get the stamp duty
            $this->stamp_duty = $this->getRates($this->premium_rate, 'Stamp Duty', 'Travel');
            //get the total
            $this->total = ($this->premium_rate + $this->levy + $this->policy_fund + $this->stamp_duty);
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

    private function returnRate($rate_name, $category)
    {
        $rates = $this->_customer->model->table('rates');
        $rate_model = $rates->find(['rate_category' => $category, 'rate_name' => $rate_name]);
        return ($rate_model->rate_type == 'Percentage' ? $rate_model->rate_value . '%' : $rate_model->rate_value);
    }
}