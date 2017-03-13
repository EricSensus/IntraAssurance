<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Accident\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Views\View;

/**
 * Class AccidentView
 */
class AccidentView extends View
{

    private $data;

    public function wizard($data = null)
    {
        $this->data = $data;
        switch ($this->data->step) {
            case "1":
                $this->personalDetails();
                break;
            case "2":
                $this->personalAccidentDetails();
                break;
            case "3":
                $this->coverDetails();
                break;
            case "4":
                $this->showQuotations();
                break;
        }
    }

    private function coverDetails()
    {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/accident/save/3',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [2, 2, 2, 1, 1, 3, 1, 1,1],
            'controls' => [
                'Cover start date *' => ['text', 'coverstart', '', ['class' => 'form-control', 'required' => '']],
                'Cover End *' => ['text', 'coverend', '', ['class' => 'form-control', 'required' => '']],
                'Amount of Insurance for death or P.D' => ['text', 'InsuredAmount', '', ['class' => 'form-control']],
                'Class' => ['select', 'cover_class', '', ['classI' => 'Class I', 'classII' => 'Class II', 'classIII' => 'Class III', 'classIV' => 'Class IV'], ['class' => 'form-control']],
                'Accidental Medical Expenses' => ['text', 'MedicalExpenses', '', ['class' => 'form-control']],
                'Temporary Total Disablement' => ['text', 'TotalDisablement', '', ['class' => 'form-control']],
                'Is this Insurance to be additional to any other Accident and/or sickness Policy' => $this->yesNo('ToCompliment'),
                '{deta}' => ['note', 'cool_note', 'If so give particulars of all other policies.'],
                'Name of company' => ['text', 'OtherCompanyName', '', ['class' => 'form-control']],
                'Sum Insured' => ['text', 'OtherCompanySumInsured', '', ['class' => 'form-control']],
                'Policy No' => ['text', 'OtherCompanyPolicyNo', '', ['class' => 'form-control']],

                '{dec}' => ['note', 'declaration', 'accceptterms',
                    '<p><strong>DECLARATION</strong></p><p></p>
                    <p>I hereby warrant and declare the truth of all the above statements and that I have not withheld any material information
and I agree that this proposal shall be the basis of the contract between me and Intra Africa Assurance Co. Ltd. And I
agree to notify the company of any material alteration in my occupation, health or habits and to accept a policy subject
to the terms, exceptions and conditions prescribed by the company</p>'],
                'I hereby agree to all the above terms and conditions' => ['radios', 'acceptterms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Quotation and Payment >>', ['class' => 'btn btn-success']]
            ]
        ];
        $form = Generate::Form('accident_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('cover_details');
    }

    private function personalAccidentDetails()
    {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/accident/save/2',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [2, 2, 1, 1, 3, 3, 3, 3, 3, 2, 2, 1, 2, 2, 1],
            'controls' => [
                'Profession/Occupation' => ['text', 'Occupation', '', ['class' => 'form-control', 'required' => '']],
                'Please describe your occupation fully' => ['textarea', 'OccupationDescription', '', ['class' => 'form-control', 'rows' => 2]],
                'Does your occupation, require you to engage in manual labour' => $this->yesNo('ManualLabour'),
                'Give details' => ['textarea', 'ManualLabourDetails', '', ['class' => 'form-control', 'rows' => 2]],
                'What is your average monthly income' => ['text', 'Salary', '', ['class' => 'form-control', 'required' => '']],
                //ETRA Fileds
                '{quest3}' => ['note', 'question34', '<strong>Have you suffered from:</strong>'],
                'Rupture(hernia)' => $this->yesNo('Rupture(hernia)'),
                'Varicose veins' => $this->yesNo('Varicose veins'),
                'Slipped disc' => $this->yesNo('Slipped disc'),
                'Impairment of sight' => $this->yesNo('Impairment of sight'),
                'Infection of eyes' => $this->yesNo('Infection of eyes'),
                'Heart disease' => $this->yesNo('Heart disease'),
                'Fits or blackouts' => $this->yesNo('Fits or blackouts'),
                'Any form of chronic' => $this->yesNo('Any form of chronic'),
                'Back strain' => $this->yesNo('Back strain'),
                'Impairment of hearing' => $this->yesNo('Impairment of hearing'),
                'Hearing complaint' => $this->yesNo('Hearing complaint'),
                'Discharge from the ear' => $this->yesNo('Discharge from the ear'),
                'Duodenal or gastric ulcer' => $this->yesNo('Duodenal or gastric ulcer'),
                'Any form or paralysis' => $this->yesNo('Any form or paralysis'),
                //other details
                'Have you any physical defect or infirmity?' => $this->yesNo('Have you any physical defect or infirmity?'),
                'Have you sustained injury by accident(s) during the last five years' => $this->yesNo('RecentInjuries'),
                'If so, give dates, nature of injury(ies) and period(s) of disablement' => ['textarea', 'RecentInjuryDetails', '', ['class' => 'form-control', 'rows' => 2]],
                'Have you ever proposed for Personal Accident and/or Life Insurance' => $this->yesNo('Have you ever proposed for Personal Accident and/or Life Insurance'),
                //'If so, give name of each Company and amount of Insurance' => ['textarea', 'RecentInsurances', '', ['class' => 'form-control', 'rows' => 2]],

                //company details

                '{quest}' => ['note', 'question3', 'Has any company in respect of life assurance or accident insurance ever:'],
                'Declined to issue a policy to you?' => $this->yesNo('Declined to issue a policy to you?'),
                'Declined to continue your insurance?' => $this->yesNo('Declined to continue your insurance'),
                'Not invited the renewal of your policy' => $this->yesNo('Not invited the renewal of your policy'),
                'Imposed any restrictions or special conditions' => $this->yesNo('Imposed any restrictions or special conditions'),
                'If so, give names of each Company' => ['textarea', 'CoverDeclineParticulars', '', ['class' => 'form-control', 'rows' => 2]],
                '{submit}' => ['submit', 'btnSubmitSpecial', 'Proceed to Cover Details >>', ['class' => 'btn btn-success',]]
            ]
        ];
        $form = Generate::Form('accident_other_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('accident_details');
    }

    private function personalDetails()
    {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => 'none',
            'method' => 'post',
            'map' => [3, 3, 3, 3, 1, 3, 1],
            'action' => '/accident/save/1',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                'Title *' => ['select', 'title', '', $this->data->titles, ['class' => 'form-control', 'required' => '']],
                'Full Name *' => ['text', 'FullName', '', ['class' => 'form-control', 'required' => '']],

                'PIN No *' => ['text', 'PIN', '', ['class' => 'form-control', 'required' => '']],
                'Certificate of Registration/Incorporation/ID/Passport *' =>
                    ['text', 'Certificate', '', ['class' => 'form-control', 'required' => '']],
                'Mobile Number *' => ['text', 'Mobile', '', ['class' => 'form-control', 'required' => '']],
                'Email*' => ['text', 'Email', '', ['class' => 'form-control', 'required' => '']],
                'Postal Address' => ['text', 'Address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code *' => ['text', 'Code', '', ['class' => 'form-control', 'required' => '']],
                'Town *' => ['select', 'Town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                'Website *' => ['text', 'Website', '', ['class' => 'form-control', 'required' => '']],
                'Telephone Number *' => ['text', 'Telephone', '', ['class' => 'form-control',]],
                'Fax *' => ['text', 'Fax', '', ['class' => 'form-control', 'required' => '']],
                '{notice}' => ['note', 'notice2', '<p>Beneficiary in the event of death:</p>'],
                'Name *' => ['text', 'BeneficiaryName', '', ['class' => 'form-control', 'required' => '']],
                'Age' => ['text', 'beneficiaryAge', '', ['class' => 'form-control', 'required' => '']],
                'Relationship' => ['select', 'BeneficiaryRelationship', '', $this->data->relationships, ['class' => 'form-control', 'required' => '']],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Personal Accident Details >>', ['class' => 'btn btn-success']]
            ]
        ];
        $form = Generate::Form('accident_personal_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('personal_details');
    }

    private function yesNo($name)
    {
        return ['radios', $name, ['yes' => 'Yes', 'no' => 'No'], 'no'];
    }

    private function showQuotations()
    {
        $this->set('data', $this->data);
        $this->setViewPanel('quotations');
    }

}
