<?php
namespace Jenga\MyProject\Quotes\Lib;

use Jenga\App\Request\Input;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Rates\Controllers\RatesController;

/**
 * Class Quotes
 * @author Samuel Dervis <samuel@sensussystems.com>
 * @package Jenga\MyProject\Quotes\Lib
 */
abstract class Quotes
{
    /**
     * Subscriber ID
     * @var int
     */
    protected $customer_id;
    /**
     * @var CustomersController
     */
    protected $_customer;
    /**
     * @var EntitiesController
     */
    protected $_entities;
    /**
     * @var QuotesController
     */
    protected $_quotes;
    /**
     * Quote id
     * @var int
     */

    public $quote_id;
    /**
     * @var
     */
    public $customer;
    /**
     * Entity id
     * @var int
     */
    public $entity_id;
    /**
     * @var RatesController
     */
    protected $_rates;
    /**
     * @var array
     */
    public $entities;


    public function __construct()
    {
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entities = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        $this->_rates = Elements::call('Rates/RatesController');
        //preloader
        $this->customer_id = Input::post('customerid');
        $this->ent_ids = Input::post('entids');
        foreach ($this->ent_ids as $other) {
            $all[] = $this->_entities->getEntityDataByfinder($other);
        }
        $this->entities = $all;
        $this->customer = $this->_customer->getCustomerById($this->customer_id, null);
        $this->setAdditionalValues();
    }

    /**
     * Set some additional data
     * @return QuotesInterface
     */
    abstract public function setAdditionalValues();

    /**
     * Do calculations
     */
    abstract public function calculateValues();


    /**
     * Update a quote in the database
     * @param bool $save Save o database if the quote is already created
     * @return string|void
     */
    public function updateQuote($save = false)
    {
        $array_show = [];
        if (!empty($this->quote->amount)) {
            return;
        }
        $fields = $this->quoteFields();
        $array_show['insurer_id'] = 14;
        foreach ($fields as $field) {
            $array_show[$field] = $this->$field;
        }
        if ($save) {
            $mine = $this->_quotes->model->find($this->quote->id);
        }
        $total = json_encode($array_show);
        if ($save) {
            $mine->amount = $total;
            $mine->save();
        }
        return $total;
    }


    protected function strClean($text)
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

    public function getRates($tsi, $rate_name, $category, $rate_type = null)
    {
        return $this->_rates->getRates($tsi, $rate_name, $category, $rate_type);
    }

    public function returnRate($rate_name, $category)
    {
        return $this->_rates->getReturnRate($rate_name, $category);
    }

    /**
     * Important fields to show in quote total
     * @return array
     */
    abstract public function quoteFields();
}