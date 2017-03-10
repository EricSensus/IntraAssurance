<?php

namespace Jenga\MyProject\Quotes\Lib;


class DomesticQuotes extends Quotes implements QuotesInterface
{
    private function calculatePropertyValue($entity)
    {
        $property = new \stdClass();
        $quote = json_decode($entity->entity_values);
        $property->tsi_a = $quote->Please_indicate_total_sum_insured_for_SECTION_A_Kshs;
        $property->tsi_b = $quote->Please_indicate_total_sum_insured_for_SECTION_B_Kshs;
        $property->tsi_c = $quote->Please_indicate_total_sum_insured_for_SECTION_C_Kshs;
        $section_a_rate = $this->returnRate('Section A', 'Property');
        $property->section_a = ($property->tsi_a * $section_a_rate) / 100;
        $section_b_rate = $this->returnRate('Section B', 'Property');
        $property->section_b = ($property->tsi_b * $section_b_rate) / 100;
        $section_c_rate = $this->returnRate('Section C', 'Property');
        $property->section_c = ($property->tsi_c * $section_c_rate) / 100;
        $workrate = $this->returnRate('Workmen Compensation', 'Property');
        if (!empty($quote->domestic_servants) && ($quote->domestic_servants > 0)) {
            $property->workmen = $quote->Specify_No_of_Domestic_servants * $workrate;
        } else {
            $property->workmen = null;
        }
        switch ($quote->Owner_Liablity) {
            case '2 million':
            case '3 million':
            case '4 million':
            case '5 million':
            case '6 million':
                $owner_rate = $this->returnRate('Owners Liability', 'Property');
                $property->owner_liability = (substr($quote->Owner_Liablity, 0, 1) * $owner_rate);
                break;
            default:
                $property->owner_liability = null;
                break;
        }
        switch ($quote->Occupiers_Liability) {
            case '2 million':
            case '3 million':
            case '4 million':
            case '5 million':
            case '6 million':
                $owner_rate = $this->returnRate('Occupier Liability', 'Property');
                $property->occupier_liability = (substr($quote->Occupiers_Liability, 0, 1) * $owner_rate);
                break;
            default:
                $property->occupier_liability = null;
                break;
        }
        $property->gross_premium = ($property->section_a + $property->section_b
            + $property->section_c + $property->workmen + $property->owner_liability + $property->occupier_liability);
        $this->gross_premium += $property->gross_premium;
        return $property;
    }

    public function calculateValues()
    {
        $this->gross_premium = 0;
        $property = [];
        foreach ($this->entities as $entity) {
            $property[] = $this->calculatePropertyValue($entity);
        }
        $this->properties = $property;
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
     * @return QuotesInterface
     */
    public function previewQuote()
    {
        $this->calculateValues();
        return $this;
    }

    /**
     * Set some additional data
     * @return QuotesInterface
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