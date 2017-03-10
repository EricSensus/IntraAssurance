<?php
namespace Jenga\MyProject\Quotes\Lib;


use Jenga\App\Core\App;
use Jenga\App\Request\Input;
use Jenga\MyProject\Accident\Models\PersonalCoverPricing;

class AccidentQuotes extends Quotes implements QuotesInterface
{
    /**
     * @var PersonalCoverPricing
     */
    protected $_pricing_model;

    /**
     * Set some additional data
     * @return Quotes
     */
    public function setAdditionalValues()
    {
        $this->_pricing_model = App::get(PersonalCoverPricing::class);
        return $this;
    }

    /**
     * Preview a quote without actually saving to database
     * @return Quotes
     */
    public function previewQuote()
    {
        $this->calculateValues();
        return $this;
    }

    /**
     * Calculate individual quote
     * @return \stdClass
     */
    private function getQuoteForPerson($entity)
    {
        $person = new \stdClass();
        $cover_info = json_decode($entity->entity_values);
        $person->band = $cover_info->Band;
        $person->class = $cover_info->Class;
        switch ($cover_info->Age_Bracket) {
            case '3 - 17':
                $person->age_bracket = '3-17';
                break;
            case '18-21':
            case '19-30':
            case '22-25':
            case '26-30':
            case '31-40':
            case '41-50':
            case '51-60':
                $person->age_bracket = '18-55';
                break;
            case '61 - 69':
            case '70 or over':
                $person->age_bracket = '56-69';
                break;
            default:
                $person->age_bracket = '18-55';
                break;
        }
        $person->name = strtoupper($cover_info->Name);
        $person->relationship = $cover_info->Relationship;
        $person->education = $cover_info->Education;
        $person->premium_rate = $this->_payments($person->band, $person->age_bracket, $person->class);

        //get the training levy
        $person->levy = $this->_payments($person->premium_rate, 'Training Levy', 'Travel');

        //get the policy fund
        $person->policy_fund = $this->_payments($person->premium_rate, 'P.H.C.F Fund', 'Travel');

        //get the stamp duty
        $person->stamp_duty = $this->_payments($person->premium_rate, 'Stamp Duty', 'Travel');

        //get the total
        $person->total = ($person->premium_rate + $person->levy);
        $this->totals += $person->total;
        $this->premiums += $person->premium_rate;
        return $person;
    }

    public function calculateValues()
    {
        $this->totals = 0;
        $people = [];
        $this->premiums = 0;
        foreach ($this->entities as $entity) {
            $people[] = $this->getQuoteForPerson($entity);
        }
        $this->people = $people;
        //get the policy fund
        $this->policy_fund = $this->getRates($this->premiums, 'P.H.C.F Fund', 'Travel');
        //get the stamp duty
        $this->stamp_duty = $this->getRates($this->premiums, 'Stamp Duty', 'Travel');
        $this->total = $this->totals + $this->policy_fund + $this->stamp_duty;
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
     * Important fields to show in quote total
     * @return array
     */
    public function quoteFields()
    {
        return ['policy_fund', 'stamp_duty', 'total'];
    }
}