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
            'map' => [1, 1, 1, 2, 1, 1, 1, 1],
            'controls' => [
                'Class' => ['select', 'cover_class', '', ['classI' => 'Class I', 'classII' => 'Class II'], ['class' => 'form-control']],
                '{deta}' => ['note', 'cool_note', '<strong>NOTE:</strong><small>Free air evacuation to Nairobi and delivery after evacuation to a hospital'
                    . ' of your choice in Nairobi as well as free evacuation'
                    . ' as a result of life threatening accident or sickness.</small>'],
                '{bands}' => ['radios', 'cover_type', $this->data->bands, '', ['class' => 'form-control']],
                'In addition to taking cover on yourself, do you wish to cover your spouse and children?'
                => ['radios', 'other_covers', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If yes, How many others would you like to cover?' => ['select', 'howmany', '', ['1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9, '10'=>10], ['class' => 'form-control']],
                '{trap}' => ['note', 'others', '<div id="other_covers_div"></div>'],
                '{dec}' => ['note', 'declaration', 'accceptterms',
                    '<p><strong>The liability of the Jubilee Insurance Company of Kenya Limited does not commence until the proposal has been accepted and the premium paid and cover confirmed by Jubilee.</strong></p><p></p>
                    <p><strong>DECLARATION</strong></p><p></p>
                    <p>I/We do hereby declare that the above answers and statements are true and that I/We have withheld no material information regarding this proposal. I/We agree that this Declaration and the answers given above, as well as any proposal or declaration or statement made in writing by me/us or anyone acting on my/our behalf shall form the basis of the contract between me/us and The Jubilee Insurance Company of Kenya Limited, and I/We further agree to accept indemnity subject to the conditions in and endorsed on the The Jubilee Insurance Company of Kenya Limited Policy. I/We also declare that any sums expressed in this proposal represent not less that the full value of the insurable property mentioned above.</p>'],
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
            'map' => [1, 2, 2, 2, 2, 2, 2, 1],
            'controls' => [
                '{quest}' => ['note', 'question3', 'Has any company in respect of life assurance or accident insurance ever:'],
                'Refused you cover?' => ['radios', 'refused_cover', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, Please provide particulars' => ['textarea', 'cover_refused_particulars', '', ['class' => 'form-control', 'rows' => 2]],
                'Declined to renew your insurance?' => ['radios', 'decline_cover', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, Please provide particulars ' => ['textarea', 'cover_decline_particulars', '', ['class' => 'form-control', 'rows' => 2]],
                'Demanded an increased rate?' => ['radios', 'demand_increased_rate', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, Please provide particulars  ' => ['textarea', 'increased_rate_particulars', '', ['class' => 'form-control', 'rows' => 2]],
                'Imposed any special terms?' => ['radios', 'imposed_special_terms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, Please provide particulars   ' => ['textarea', 'special_terms_particulars', '', ['class' => 'form-control', 'rows' => 2]],
                'Are there circumstances connected with your pursuits or mode of life or
                    hobbies which render you specially liable for injury?'
                => ['radios', 'hobbies_injury_liable', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, Please provide particulars    ' => ['textarea', 'injury_liable_particulars', '', ['class' => 'form-control', 'rows' => 2]],
                'Has any proposed insured ever made a claim in respect to Personal Accident?' =>
                    ['radios', 'ever_made_claims', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, Please provide particulars     ' => ['textarea', 'made_claims_particulars', '', ['class' => 'form-control', 'rows' => 2]],
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
            'map' => [3, 3, 2, 3, 2, 1, 1, 3, 1],
            'action' => '/accident/save/1',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                'Title *' => ['select', 'title', '', $this->data->titles, ['class' => 'form-control', 'required' => '']],
                'Surname *' => ['text', 'surname', '', ['class' => 'form-control', 'required' => '']],
                'Other Names *' => ['text', 'names', '', ['class' => 'form-control', 'required' => '']],
                'Occupation' => ['text', 'occupation', '', ['class' => 'form-control', 'required' => '']],
                'Date of Birth *' => ['text', 'dob', '', ['class' => 'form-control', 'required' => '', 'readonly' => 'true']],
                'Age Bracket *' => ['select', 'age_bracket', '', $this->data->age_bracket, ['class' => 'form-control', 'required' => '']],
                'Height *' => ['text', 'height', '', ['class' => 'form-control', 'placeholder' => 'Metres', 'required' => '']],
                'Weight *' => ['text', 'weight', '', ['class' => 'form-control', 'placeholder' => 'Ponds', 'required' => '']],
                'Postal Address' => ['text', 'address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code *' => ['text', 'code', '', ['class' => 'form-control', 'required' => '']],
                'Town *' => ['select', 'town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                'Email*' => ['text', 'email', '', ['class' => 'form-control', 'required' => '']],
                'Mobile Number *' => ['text', 'mobile', '', ['class' => 'form-control', 'required' => '']],
                '{notice}' => ['note', 'notice2', '<p>Please let us know here about your Beneficiary (s) or Trustee in the event of Accidental Death.</p>'],
                'Name *' => ['text', 'beneficiary_name', '', ['class' => 'form-control', 'required' => '']],
                'P.O Box' => ['text', 'beneficiary_address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code ' => ['text', 'beneficiary_code', '', ['class' => 'form-control', 'required' => '']],
                'Town' => ['select', 'beneficiary_town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Personal Accident Details >>', ['class' => 'btn btn-success']]
            ]
        ];
        $form = Generate::Form('accident_personal_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('personal_details');
    }

    private function showQuotations()
    {
        $this->set('data', $this->data);
        $this->setViewPanel('quotations');
    }

}
