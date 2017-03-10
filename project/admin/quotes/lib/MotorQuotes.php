<?php
namespace Jenga\MyProject\Quotes\Lib;

use Jenga\App\Request\Input;

class MotorQuotes extends Quotes implements QuotesInterface
{

    /**
     * @inheritdoc
     * @return $this
     */
    public function previewQuote()
    {
        $this->calculateValues();
        return $this;
    }

    private function getCarValue($entity)
    {
        $post = Input::post();
        $car = new \stdClass();
        $_car = json_decode($entity->entity_values);
        $car->tsi = $_car->valueestimate;
        if (empty($car->tsi)) {
            $car->tsi = $_car->Estimated_Value;
        }
        $car->reg = strtoupper($_car->Registration_no);
        switch ($post['Cover_Type']) {
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
        switch ($post['Riotes']) {
            case 'Yes':
                $car->riotes = $this->getRates($car->tsi, 'Riots and Strikes', 'Motor', 'percentage');
                break;
            default :
                $car->riotes = null;
                break;
        }
        //if riots is set, put zero
        if (($post['Cover_Type'] == 'Third Party Only') && ($car->riotes == '1')) {
            $car->riotes = null;
        }
        //calculate terrorism value
        switch ($post['Terrorism']) {
            case 'Yes':
                $car->terrorism = $this->getRates($car->tsi, 'Terrorism', 'Motor', 'percentage');
                break;
            default :
                $car->terrorism = null;
                break;
        }
        //$windscreen = 0; Calculate Windscreen
        switch ($post['Windscreen']) {
            case 'Yes':
                $car->windscreen = $this->getRates($car->tsi, 'Windscreen', 'Motor', 'percentage');
                break;
            default :
                $car->windscreen = null;
                break;
        }
        //$radio_cassette = 0;
        switch ($post['Audio']) {
            case 'Yes':
                $car->audio = $this->getRates($car->tsi, 'Audio System', 'Motor', 'percentage');
                break;
            default :
                $car->audio = null;
                break;
        }
        //$passenger_legal = 0;
        switch ($post['Passenger']) {
            case 'Yes':
                $car->passenger = $this->getRates($car->tsi, 'Passenger Liability', 'Motor', 'percentage');
                break;
            default :
                $car->passenger = null;
                break;
        }
        //set the NDC amount
        if (!empty($post['Ncd_Percent'])) {
            $car->ncd_percent = $post['Ncd_Percent'];
            $car->ncd_amount = $car->basic_premium * ($car->ncd_percent / 100);
            //if Third Party Only is set, put zero
            if ($car->cover_type == 'Third Party Only') {
                $car->ncd_amount = null;
            }
            $car->basic_premium2 = $car->basic_premium - $car->ncd_amount;
            /*   $ncdtxt = ' (less ' . $ncd_percent . '% NCD)';
              $oncd[0] = '<strong>NCD Amount</strong>';
              $oncd[1] = '<strong>ksh ' . $ncd_amount . '</strong>'; */
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

    public function calculateValues()
    {
        $this->total = 0;
        $cars = [];
        foreach ($this->entities as $entity) {
            $cars[] = $this->getCarValue($entity);
        }
        $this->cars = $cars;
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
     * Set some additional data
     * @return Quotes
     */
    public function setAdditionalValues()
    {
        return $this;
    }

    /**
     * Important fields to show in quote total
     * @return array
     */
    public function quoteFields()
    {
        return ['training_levy', 'policy_levy', 'stamp_duty', 'total'];
    }
}