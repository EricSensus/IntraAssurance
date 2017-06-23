<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Motor\Repositories;

use Jenga\App\Request\Session;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

/**
 * Class Payments
 */
class MotorQuotation
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
     * @var string
     */
    public $cover_type;
    /**
     * @var float
     */
    public $other_totals;

    /**
     * Constructor
     * @param null|int $quote
     */
    public function __construct($quote = null)
    {
        $this->quote = $quote;
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
        return $this;
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
     * Perform database quote updates on the amount column
     */

    private function updateQuote()
    {
        $available = get_object_vars($this);
        $to_save = array_only($available, $this->quoteFields());
        $others = [];
        foreach ($this->cars as $en) {
            $others[$en->reg] = array_only(get_object_vars($en), $this->quoteFields());
        }
        $mine = $this->_quotes->model->find($this->quote->id);
        $to_save = array_prepend($to_save, $others, 'other_covers');
        $to_save = array_prepend($to_save, 14, 'insurer_id');
        $total = json_encode($to_save);
        $mine->amount = $total;
        $mine->save();
    }

    /**
     * Calculate each quotation for a single car
     * @param $_car
     * @param bool $is_main
     * @return \stdClass
     */
    private function calculateEachValues($_car, $is_main = false)
    {
        $car = new \stdClass();
        $car->tsi = $_car->valueestimate;
        if ($is_main) {
            $car->tsi = $tsi = json_decode($this->main_entity->entity_values)->valueestimate;
            $this->cover_type = $_car->covertype;
        }
        $car->reg = $_car->regno;
        switch ($this->cover_type) {
            case 'Comprehensive';
                //$basic_premium = (($tsi*7.75)/100);
                $car->basic_premium = $this->getRates($car->tsi, 'Comprehensive', 'Motor', 'percentage');
                $car->cover_type = 'Comprehensive';
                break;
            case 'Third Party Fire and Theft';
                //$basic_premium = (($tsi*4.5)/100);
                $car->basic_premium = $this->getRates($car->tsi, 'Third Party Fire and Theft', 'Motor', 'percentage');
                $car->cover_type = 'Third Party Fire and Theft';
                break;
            case 'Third Party Only':
                //$basic_premium = 12500;
                $car->basic_premium = $this->getRates($car->tsi, 'Third Party Only', 'Motor', 'fixed');
                $car->cover_type = 'Third Party Fire and Theft';
                break;
        }
        //calculate riot and strikes value
        switch ($_car->riotes) {
            case 'yes':
                $car->riotes = $this->getRates($car->tsi, 'Riots and Strikes', 'Motor', 'percentage');
                break;
            default :
                $car->riotes = null;
                break;
        }
        //if riots is set, put zero
        if (($car->cover_type == 'Third Party Only') && ($car->riotes == 'yes')) {
            $car->riotes = null;
        }
        //calculate terrorism value
        switch ($_car->terrorism) {
            case 'yes':
                $car->terrorism = $this->getRates($car->tsi, 'Terrorism', 'Motor', 'percentage');
                break;
            default :
                $car->terrorism = null;
                break;
        }
        //$windscreen = 0; Calculate Windscreen
        switch ($_car->windscreen) {
            case 'yes':
                $car->windscreen = $this->getRates($car->tsi, 'Windscreen', 'Motor', 'percentage');
                break;
            default :
                $car->windscreen = null;
                break;
        }
        //$radio_cassette = 0;
        switch ($_car->audio) {
            case 'yes':
                $car->audio = $this->getRates($car->tsi, 'Audio System', 'Motor', 'percentage');
                break;
            default :
                $car->audio = null;
                break;
        }
        //$passenger_legal = 0;
        switch ($_car->passenger) {
            case 'yes':
                $car->passenger = $this->getRates($car->tsi, 'Passenger Liability', 'Motor', 'percentage');
                break;
            default :
                $car->passenger = null;
                break;
        }

        //set the NDC amount
        if (!empty($_car->ncd_percent)) {
            $car->ncd_percent = $_car->ncd_percent;
            $car->ncd_amount = $car->basic_premium * ($car->ncd_percent / 100);
            //if Third Party Only is set, put zero
            if ($car->cover_type == 'Third Party Only') {
                $car->ncd_amount = null;
            }
            $car->basic_premium2 = $car->basic_premium - $car->ncd_amount;
        } else {
            $car->basic_premium2 = $car->basic_premium;
        }

        //check if amount is
        if ($car->basic_premium2 < 12500) {
            switch ($car->cover_type) {
                case 'Comprehensive';
                    //$basic_premium = (($tsi*7.75)/100);
                    $car->basic_premium2 = 15000;
                    break;

                case 'Third Party Fire and Theft';
                    //$basic_premium = (($tsi*4.5)/100);
                    $car->basic_premium2 = 12500;
                    break;

                case 'Third Party Only':
                    //$basic_premium = 12500;
                    $car->basic_premium2 = 12500;
                    break;
            }

            $car->minimum2 = $car->basic_premium2;
        }

        //calculate the net premium
        $car->net_premium = ($car->basic_premium2 + $car->riotes + $car->windscreen + $car->audio + $car->passenger + $car->terrorism);

        $this->total += $car->net_premium;
        return $car;
    }

    /**
     * Use already set values to calculate the values for the quote
     */
    private function calculateValues()
    {
        $this->total = 0;
        $quote = json_decode($this->quote->product_info);
        $this->main = $this->calculateEachValues($quote, true);
        $cars = [];
        foreach ($this->other_entities as $entity) {
            $cars[] = $this->calculateEachValues(json_decode($entity->entity_values));
        }
        $this->basic_premium = $cars[0]->basic_premium;
        $this->cars = $cars;
        $this->total_net_premiums = $this->total;
        //calculate the training levy
        $training = $this->returnRate('Training Levy', 'Travel');
        $this->training_levy = (($this->total * $training) / 100);

        //calculate the policy levy
        $levyvalue = $this->returnRate('Motor Policy Levy', 'Motor');
        $this->policy_levy = (($this->total * $levyvalue) / 100);

        //get the stamp duty
        $this->stamp_duty = $this->returnRate('Stamp Duty', 'Travel');

        $this->total = ($this->total + $this->policy_levy + $this->training_levy + $this->stamp_duty);

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
        return ['training_levy', 'policy_levy',
            'stamp_duty', 'total', 'passenger', 'audio',
            'windscreen', 'terrorism', 'basic_premium',
            'net_premium', 'total_net_premiums', 'ncd_amount'];
    }
}
