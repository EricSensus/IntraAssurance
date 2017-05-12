<?php

namespace Jenga\MyProject\Quotes\Library\Companies;

use Jenga\MyProject\Quotes\Library\QuotesBlueprint;

class JubileeQuotes extends QuotesBlueprint
{
    /**
     * @var int
     */
    protected $insurer_id = 14;
    /**
     * @var float
     */
    public $total = 0.0;


    /**
     * Do calculation for Motor
     * @return mixed
     */
    protected function getMotorQuote()
    {
        $this->main = $this->calculateEachValuesForMotor($this->quote_product_info, true);
        $cars = [];
        foreach ($this->other_entities as $entity) {
            $cars[] = $this->calculateEachValuesForMotor($entity);
        }
//        $this->basic_premium = $cars[0]->basic_premium;
        $this->cars = $cars;
        $this->total_net_premiums = $this->total;
        //calculate the training levy
        $training = $this->getReturnRate('Training Levy', 'Travel');
        $this->training_levy = (($this->total * $training) / 100);

        //calculate the policy levy
        $levyvalue = $this->getReturnRate('Motor Policy Levy', 'Motor');
        $this->policy_levy = (($this->total * $levyvalue) / 100);

        //get the stamp duty
        $this->stamp_duty = $this->getReturnRate('Stamp Duty', 'Travel');

        $this->total = ($this->total + $this->policy_levy + $this->training_levy + $this->stamp_duty);
    }

    /**getA
     * @param $_car
     * @param bool $is_main
     * @return \stdClass
     */
    private function calculateEachValuesForMotor($_car, $is_main = false)
    {
        $car = new \stdClass();
        $car->tsi = $_car->valueestimate;
        $car->reg = $_car->regno;
        if ($is_main) {
            $car->tsi = $tsi = $this->main_entity->ValueEstimate;
            $car->reg = $this->main_entity->RegNo;
            $this->cover_type = $this->quote_product_info->covertype;
        }

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
        switch ($_car->political_violence) {
            case 'yes':
                $car->terrorism = $this->getRates($car->tsi, 'Political Violence', 'Motor', 'percentage');
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
        switch ($_car->entertainment_equipment) {
            case 'yes':
                $car->audio = $this->getRates($car->tsi, 'Entertainment Equipment', 'Motor', 'percentage');
                break;
            default :
                $car->audio = null;
                break;
        }
        switch ($_car->excess_protector) {
            case 'yes':
                $car->excess_protector = $this->getRates($car->tsi, 'Excess Protector', 'Motor', 'percentage');
                break;
            default :
                $car->excess_protector = null;
                break;
        }
        switch ($_car->srcc) {
            case 'yes':
                $car->srcc = $this->getRates($car->tsi, 'SRCC (Strikes Riots & Civil Commotion)', 'Motor', 'percentage');
                break;
            default :
                $car->srcc = null;
                break;
        }
        switch ($_car->loss_of_use) {
            case 'yes':
                $car->loss_of_use = $this->getRates($car->tsi, 'Loss of Use', 'Motor', 'percentage');
                break;
            default :
                $car->loss_of_use = null;
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
        $car->net_premium = ($car->basic_premium2 + $car->riotes +
            $car->windscreen + $car->audio + $car->passenger + $car->terrorism + $car->srcc + $car->loss_of_use + $car->excess_protector);
        if ($is_main) {
            $this->basic_premium = $car->net_premium;
        }
        $this->total += $car->net_premium;
        return $car;
    }

    /**
     * Do calculation for Travel
     * @return mixed
     */
    protected function getTravelQuote()
    {
        // TODO: Implement getTravelQuote() method.
    }

    /**
     * Do calculation for Accident
     * @return mixed
     */
    protected function getAccidentQuote()
    {

        $this->band = $this->quote_product_info->cover_type;
        $this->class = $this->quote_product_info->cover_class;
        //process the age bracket
        $this->age_bracket = $this->formatAgeBracket($this->customer->age_bracket);
        $this->premium_rate = $this->_payments($this->band, $this->age_bracket, $this->class);
        $this->training_levy = $this->getRates($this->premium_rate, 'Training Levy', 'Travel');
        $this->levy_rate = $this->getReturnRate('Training Levy', 'Travel');
        $this->basic_premium = $this->premium_rate + $this->training_levy;

        $_total = 0;
        if (!empty($this->other_entities)) {
            $this->other_covers = count($this->other_entities);
            $premium2 = 0;
            foreach ($this->other_entities as $entity) {
                $each = new \stdClass();
                $each->name = ucwords(strtolower($entity->other_name));
                $each->relationship = $entity->other_relationship;
                $each->age_bracket = $entity->other_bracket;
                $each->education = $entity->other_education;
                $each->band = $entity->other_band;
                $each->class = $entity->other_class;


                $f_bracket = $this->formatAgeBracket($each->age_bracket);
                $each->premium_rate = $this->_payments($each->band, $f_bracket, $each->class);

                //get the training levy
                $each->training_levy = $this->getRates($each->premium_rate, 'Training Levy', 'Travel');

                //get the policy fund
                $each->policy_fund = $this->getRates($each->premium_rate, 'P.H.C.F Fund', 'Travel');

                //get the stamp duty
                $each->stamp_duty = $this->getRates($each->premium_rate, 'Stamp Duty', 'Travel');

                //get the total
                $each->basic_premium = ($each->premium_rate + $each->training_levy);
                $_total += $each->basic_premium;
                $premium2 += $each->premium_rate;

                $this->others[] = $each;
            }
            $this->other_total = $_total;

            //get the policy fund
            $this->policy_fund = $this->getRates(($this->premium_rate + $premium2), 'P.H.C.F Fund', 'Travel');
            //get the stamp duty
            $this->stamp_duty = $this->getRates($this->premium_rate, 'Stamp Duty', 'Travel');
            //$this->basic_premium = $this->basic_premium + $this->policy_fund + $this->stamp_duty;
            $this->total = $this->basic_premium + $this->other_total + $this->policy_fund + $this->stamp_duty;

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
     * Do calculation for Medical
     * @return mixed
     */
    protected function getMedicalQuote()
    {
        // TODO: Implement getMedicalQuote() method.
    }

    /**
     * Do calculation for Domestic
     * @return mixed
     */
    protected function getDomesticQuote()
    {
        $this->tsi_a = (empty($this->quote_product_info->a_premium)) ? 0 : $this->quote_product_info->a_premium;
        $this->tsi_b = (empty($this->quote_product_info->b_premium)) ? 0 : $this->quote_product_info->b_premium;
        $this->tsi_c = (empty($this->quote_product_info->c_premium)) ? 0 : $this->quote_product_info->c_premium;

        $section_a_rate = $this->getReturnRate('Section A', 'Property');
        $this->section_a = ($this->tsi_a * $section_a_rate) / 100;
        $section_b_rate = $this->getReturnRate('Section B', 'Property');
        $this->section_b = ($this->tsi_b * $section_b_rate) / 100;
        $section_c_rate = $this->getReturnRate('Section C', 'Property');
        $this->section_c = ($this->tsi_c * $section_c_rate) / 100;
        $workrate = $this->getReturnRate('Workmen Compensation', 'Property');
        if (!empty($this->quote_product_info->domestic_servants) && ($this->quote_product_info->domestic_servants > 0)) {
            $this->workmen = $this->quote_product_info->domestic_servants * $workrate;
        } else {
            $this->workmen = null;
        }

        switch ($this->quote_product_info->owner_liabilty) {
            case '2 million':
            case '3 million':
            case '4 million':
            case '5 million':
            case '6 million':
                $owner_rate = $this->getReturnRate('Owners Liability', 'Property');
                $this->owner_liability = (substr($this->quote_product_info->owner_liabilty, 0, 1) * $owner_rate);
                break;
            default:
                $this->owner_liability = null;
                break;
        }
        switch ($this->quote_product_info->occupiers_liabilty) {
            case '2 million':
            case '3 million':
            case '4 million':
            case '5 million':
            case '6 million':
                $owner_rate = $this->getReturnRate('Occupier Liability', 'Property');
                $this->occupier_liability = (substr($this->quote_product_info->owner_liabilty, 0, 1) * $owner_rate);
                break;
            default:
                $this->occupier_liability = null;
                break;
        }

        $this->gross_premium = ($this->section_a + $this->section_b
            + $this->section_c + $this->workmen + $this->owner_liability + $this->occupier_liability);

        //calculate the training levy
        $this->training_rate = $this->getReturnRate('Training Levy', 'Travel');
        $this->training_levy = (($this->gross_premium * $this->training_rate) / 100);

        //calculate the policy levy
        $this->levy_value = $this->getReturnRate('Property Policy Levy', 'Property');
        $this->policy_levy = (($this->gross_premium * $this->levy_value) / 100);

        $this->stamp_duty = $this->getReturnRate('Stamp Duty', 'Travel');

        $this->basic_premium = $this->total = $this->gross_premium + $this->policy_levy + $this->stamp_duty + $this->training_levy;
    }


}