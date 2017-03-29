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
     * Determines whether the motor type is private or commercial
     * @var type 
     */
    public $type;

    public function __construct($type = 'private'){
        
        $this->type = $type;
        $this->customer_id = Session::get('customer_id');
        
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entities = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        
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
        
        if (!empty($this->other_entities)) {
            $this->other_covers();
        }
        
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

    private function other_covers()
    {
        
        $this->other_totals = 0;
        $cars = [];
        foreach ($this->other_entities as $entity) {
            
            $car = new \stdClass();
            
            $_car = json_decode($entity->entity_values);
            $car->tsi = $_car->valueestimate;
            $car->reg = $_car->regno;
            
            switch ($this->cover_type) {
                case 'Comprehensive';
                    //$basic_premium = (($tsi*7.75)/100);
                    $car->basic_premium = $this->getRates($_car->valueestimate, 'Comprehensive', 'Motor', 'percentage');
                    $car->cover_type = 'Comprehensive';
                    break;
                case 'Third Party Only':
                    //$basic_premium = 12500;
                    $car->basic_premium = $this->getRates($_car->valueestimate, 'Third Party Only', 'Motor', 'fixed');
                    $car->cover_type = 'Third Party Fire and Theft';
                    break;
            }
            
            //calculate riot and strikes value
            switch ($_car->riotes) {
                case 'yes':
                    $car->riotes = $this->getRates($_car->valueestimate, 'Riots and Strikes', 'Motor', 'percentage');
                    break;
                default :
                    $car->riotes = null;
                    break;
            }
            //if riots is set, put zero
            if (($car->cover_type == 'Third Party Only') && ($car->riotes == '1')) {
                $car->riotes = null;
            }
            //calculate terrorism value
            switch ($_car->terrorism) {
                case 'yes':
                    $car->terrorism = $this->getRates($_car->valueestimate, 'Terrorism', 'Motor', 'percentage');
                    break;
                default :
                    $car->terrorism = null;
                    break;
            }
            //$windscreen = 0; Calculate Windscreen
            switch ($_car->windscreen) {
                case 'yes':
                    $car->windscreen = $this->getRates($_car->valueestimate, 'Windscreen', 'Motor', 'percentage');
                    break;
                default :
                    $car->windscreen = null;
                    break;
            }
            //$radio_cassette = 0;
            switch ($_car->audio) {
                case 'yes':
                    $car->audio = $this->getRates($_car->valueestimate, 'Audio System', 'Motor', 'percentage');
                    break;
                default :
                    $car->audio = null;
                    break;
            }
            //$passenger_legal = 0;
            switch ($_car->passenger) {
                case '1':
                    $car->passenger = $this->getRates($_car->valueestimate, 'Passenger Liability', 'Motor', 'percentage');
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
                $car->basic_premium = $car->basic_premium - $car->ncd_amount;
                /*   $ncdtxt = ' (less ' . $ncd_percent . '% NCD)';
                  $oncd[0] = '<strong>NCD Amount</strong>';
                  $oncd[1] = '<strong>ksh ' . $ncd_amount . '</strong>'; */
            } else {
                $car->basic_premium = $car->basic_premium;
            }

            //check if amount is
            if ($car->basic_premium < 12500) {
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

            $this->other_totals += $car->net_premium;
            $cars[] = $car;
        }
        $this->cars = $cars;
        $this->total += $this->other_totals;
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


    private function calculateValues(){
        
        $entity_values = json_decode($this->main_entity->entity_values);
        $this->tsi = $tsi = $entity_values->ValueEstimate;
        
        $quote = json_decode($this->quote->product_info);
        
        switch ($quote->covertype) {
            case 'Comprehensive';
                
                if($entity_values->type == 'private'){
                    $this->basic_premium = $this->getRates($tsi, 'comprehensive-private', 'Motor', 'percentage');
                }
                elseif($entity_values->type == 'commercial'){
                    $this->basic_premium = $this->getRates($tsi, 'comprehensive-commercial', 'Motor', 'percentage');
                }
                
                $this->cover_type = 'Comprehensive';
                
                //calculate additional benefits
                $this->_calculateAdditionalBenefits($tsi, $quote);                
                break;
                
            case 'Third Party Only':
                
                if($entity_values->type == 'private'){
                    $this->basic_premium = $this->getRates($tsi, 'Third Party Only', 'Motor', 'fixed');
                }
                elseif($entity_values->type == 'commercial'){
                    $this->basic_premium = $this->getRates($tsi, $entity_values->tonnage, 'Motor', 'fixed');
                }
                
                $this->cover_type = 'Third Party Only';
                break;
        }
        
        //if riots is set, put zero
        if (($this->cover_type == 'Third Party Only') && (!empty($this->riotes))) {
            $this->riotes = null;
        }
        
        //calculate the net premium
        $this->net_premium = $this->basic_premium 
                                    + $this->riotes 
                                    + $this->windscreen 
                                    + $this->audio 
                                    + $this->passenger 
                                    + $this->terrorism
                                    + $this->excess_protector
                                    + $this->loss_of_use;
        
        //calculate the policy levy
        $this->levy_value = $this->returnRate('Motor Policy Levy', 'Motor');
        $this->policy_levy = (($this->net_premium * $this->levy_value) / 100);
        
        //calculate the ITL
        $this->itl = $this->returnRate('ITL', 'Motor');
        $this->itl_value = (($this->net_premium * $this->itl) / 100);
        
        //get the stamp duty
        $this->stamp_duty = $this->returnRate('Stamp Duty', 'Travel');
        $this->no_of_covers = ($this->step2->addothercars == 'no') ? null : $this->step2->howmanycars;
        
        if (empty($this->no_of_covers)) {
            
            $training = $this->returnRate('Training Levy', 'Travel');
            
            $this->training_levy = (($this->net_premium * $training) / 100);
            $this->total = ($this->net_premium + $this->training_levy + $this->policy_levy + $this->stamp_duty + $this->itl_value);
        } else {
            //calculate total premium
            $this->total = $this->net_premium;
        }

    }

    private function _calculateAdditionalBenefits($tsi, $quote) {
        
        //calculate srcc
        switch ($quote->srcc) {
            case 'yes':
                $this->riotes = $this->getRates($tsi, 'srcc', 'Motor', 'percentage');
                break;
            default:
                $this->riotes = 0;
                break;
        }
        
        //calculate political violence
        switch ($quote->political_violence) {
            case 'yes':
                if ($this->cover_type != 'Third Party Only') {
                    $this->terrorism = $this->getRates($tsi, 'political-violence', 'Motor', 'percentage');
                }
                
                break;
            default:
                $this->terrorism = 0;
                break;
        }
        
        //$windscreen = 0; Calculate Windscreen
        switch ($quote->windscreen) {
            case 'yes':
                $this->windscreen = $this->getRates($tsi, 'Windscreen', 'Motor', 'percentage', $quote);
                break;
            default:
                $this->windscreen = 0;
                break;
        }
        
        //$entertainment = 0;
        switch ($quote->entertainment_equipment) {
            case 'yes':
                $this->audio = $this->getRates($tsi, 'entertainment', 'Motor', 'percentage');
                break;
            default:
                $this->audio = 0;
                break;
        }
        
        //$excess_protector = 0;
        switch ($quote->excess_protector) {
            case 'yes':
                $this->excess_protector = $this->getRates($tsi, 'excess-protector', 'Motor', 'percentage');
                break;
            default:
                $this->excess_protector = 0;
                break;

        }
        
        //$loss_of_use = 0;
        switch ($quote->loss_of_use) {
            case 'yes':
                $this->loss_of_use = $this->getRates($tsi, 'loss-of-use', 'Motor', 'percentage');
                break;
            default:
                $this->loss_of_use = 0;
                break;
        }
    }
    
    private function getRates($tsi, $name_alias, $category, $rate_type = null, $quote = null){
        
        if (!($this->rates instanceof \stdClass)) {
            $this->rates = new \stdClass();
        }
        
        $rates = $this->_customer->model->table('rates');

        if (empty($rate_type)) {
            
            $rate = $rates->where('rate_category', $category)
                            ->where('rate_name', $name_alias)
                            ->orWhere('alias', $name_alias)
                            ->first();
        } else {
            
            $rate = $rates->where('rate_category', $category)
                            ->where('rate_name', $name_alias)
                            ->orWhere('alias', $name_alias)
                            ->where('rate_type', $rate_type)
                            ->first();
        }
        if ($rate->rate_type == 'Percentage') {
            $computed_value = (($tsi * $rate->rate_value) / 100);
        } 
        elseif ($rate->rate_type == 'Fixed') {
            $computed_value = $rate->rate_value;
        }
        
        //imposed rates minimum and maximum
        switch ($name_alias) {
            case 'loss-of-use':
            case 'Loss of Use':

                $min = $rates->where('alias','loss-of-use-min')->first();
                $max = $rates->where('alias','loss-of-use-max')->first();
                    
                if($computed_value < $min->rate_value){
                    $computed_value = $min->rate_value;
                }
                elseif($computed_value > $max->rate_value){
                    $computed_value = $max->rate_value;
                }
                break;
            
            case 'Windscreen':
            case 'windscreen':
                
                if($tsi < 1000000){
                    
                    if($quote->Windscreen > 30000){
                        $computed_value = (($quote->Windscreen - 30000) * $rate->rate_value) / 100;
                    }
                }
                else{
                    $computed_value = 0;
                }
                break;
                
            case 'entertainment':
                
                if($tsi < 1000000){
                    
                    if($quote->entertainment_equipment > 30000){
                        $computed_value = (($quote->entertainment_equipment - 30000) * $rate->rate_value) / 100;
                    }
                }
                else{
                    $computed_value = 0;
                }
                break;
        }
        
        $this->rates->{$this->strClean($category . " " . $name_alias)} = ['rate' => $rate->rate_value, 'type' => $rate->rate_type];
        
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
