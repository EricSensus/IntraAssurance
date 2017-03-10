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


    public function __construct()
    {
        $this->customer_id = Session::get('customer_id');
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entities = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        $this->customer = $this->_customer->getCustomerById($this->customer_id, null);
        $this->quote = $this->_quotes->getQuoteById(Session::get('quote_id'));
        $this->main_entity = $this->_entities->getEntityDataByfinder(Session::get('main_id'));
        $this->calculateValues();
        $this->updateQuote();
        unset($this->_customer, $this->_entities, $this->_quotes);
    }

    private function updateQuote()
    {
        if (!empty($this->quote->amount)) {
            return;
        }
        $copy = clone $this;
        $mine = $this->_quotes->model->find($this->quote->id);
        unset($copy->quote, $copy->customer, $copy->main_entity, $copy->other_entities, $copy->rates, $copy->customer_id);
        unset($copy->_customer, $copy->_entities, $copy->_quotes);
        $copy->insurer_id = 14;//@todo Get insurer id
        $total = json_encode(get_object_vars($copy));
        $mine->amount = $total;
        $mine->save();
    }

    private function calculateValues()
    {
        $quote = json_decode($this->quote->product_info);
        $this->tsi_a = $quote->a_premium;
        $this->tsi_b = $quote->b_premium;
        $this->tsi_c = $quote->c_premium;
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
