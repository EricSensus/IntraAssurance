<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Domestic\Repositories;

use Jenga\App\Request\Session;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

/**
 * Class DomesticQuotation
 */
class DomesticQuotation
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
        $mine = $this->_quotes->model->find($this->quote->id);
        $to_save = array_prepend($to_save, [], 'other_covers');
        $to_save = array_prepend($to_save, 14, 'insurer_id');
        $total = json_encode($to_save);
        $mine->amount = $total;
        $mine->save();
    }

    /**
     * Use already set values to calculate the values for the quote
     */
    private function calculateValues()
    {
        $quote = json_decode($this->quote->product_info);
        $this->tsi_a = (empty($quote->a_premium)) ? 0 : $quote->a_premium;
        $this->tsi_b = (empty($quote->b_premium)) ? 0 : $quote->b_premium;
        $this->tsi_c = (empty($quote->c_premium)) ? 0 : $quote->c_premium;
        $section_a_rate = $this->returnRate('Section A', 'Property');
        $this->section_a = ($this->tsi_a * $section_a_rate) / 100;
        $section_b_rate = $this->returnRate('Section B', 'Property');
        $this->section_b = ($this->tsi_b * $section_b_rate) / 100;
        $section_c_rate = $this->returnRate('Section C', 'Property');
        $this->section_c = ($this->tsi_c * $section_c_rate) / 100;
        $workrate = $this->returnRate('Workmen Compensation', 'Property');
        if (!empty($quote->domestic_servants) && ($quote->domestic_servants > 0)) {
            $this->workmen = $quote->domestic_servants * $workrate;
        } else {
            $this->workmen = null;
        }
        switch ($quote->owner_liabilty) {
            case '2 million':
            case '3 million':
            case '4 million':
            case '5 million':
            case '6 million':
                $owner_rate = $this->returnRate('Owners Liability', 'Property');
                $this->owner_liability = (substr($quote->owner_liabilty, 0, 1) * $owner_rate);
                break;
            default:
                $this->owner_liability = null;
                break;
        }
        switch ($quote->occupiers_liabilty) {
            case '2 million':
            case '3 million':
            case '4 million':
            case '5 million':
            case '6 million':
                $owner_rate = $this->returnRate('Occupier Liability', 'Property');
                $this->occupier_liability = (substr($quote->owner_liabilty, 0, 1) * $owner_rate);
                break;
            default:
                $this->occupier_liability = null;
                break;
        }

        $this->gross_premium = ($this->section_a + $this->section_b
            + $this->section_c + $this->workmen + $this->owner_liability + $this->occupier_liability);

        //calculate the training levy
        $this->training_rate = $this->returnRate('Training Levy', 'Travel');
        $this->training_levy = (($this->gross_premium * $this->training_rate) / 100);

        //calculate the policy levy
        $this->levy_value = $this->returnRate('Property Policy Levy', 'Property');
        $this->policy_levy = (($this->gross_premium * $this->levy_value) / 100);

        $this->stamp_duty = $this->returnRate('Stamp Duty', 'Travel');

        $this->total = $this->gross_premium + $this->policy_levy + $this->stamp_duty + $this->training_levy;
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
        return ['training_levy', 'policy_levy', 'stamp_duty', 'total', 'section_a', 'section_b', 'section_c',
            'workmen', 'owner_liability', 'occupiers_liability', 'gross_premium', 'basic_premium'];
    }
}
