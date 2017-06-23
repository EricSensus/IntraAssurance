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
use Jenga\App\Request\Url;
use Jenga\App\Views\View;
use Jenga\MyProject\Elements;

/**
 * Class AccidentView
 */
class AccidentView extends View
{
    /**
     * View data from the controller
     * @var \stdClass
     */
    private $data;
    /**
     * Whether to render the form or just get its schematic
     * @var bool
     */
    private $want_schematic = false;

    /**
     * Get the schematic from form wizard
     * @param null $data
     * @return array|null|void
     */
    public function getSchematic($data = null)
    {
        $this->want_schematic = true;
        return $this->wizard($data);
    }

    /**
     * The form wizard to switch between which form to display
     * @param null|\stdClass $data
     * @return array|null|void
     */
    public function wizard($data = null)
    {
        $this->data = $data;
        $x = null;
        switch ($this->data->step) { //switch user steps
            case "1":
                $x = $this->personalDetails();
                break;
            case "2":
                $x = $this->personalAccidentDetails();
                break;
            case "3":
                $x = $this->coverDetails();
                break;
            case "4":
                $x = $this->showQuotations();
                break;
        }
        if ($this->want_schematic) {
            return $x;
        }
    }

    /**
     * Cover details - Step 3 for accident quotation
     * @return array
     */
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
            'map' => [2, 1, 1, 1, 2, 1, 1, 1, 1],
            'controls' => [
                'Cover start date *' => ['text', 'coverstart', '', ['class' => 'form-control', 'required' => '']],
                'Cover End *' => ['text', 'coverend', '', ['class' => 'form-control', 'required' => '',]],
                'Class' => ['select', 'cover_class', '', ['classI' => 'Class I', 'classII' => 'Class II'], ['class' => 'form-control', 'required' => '']],
                '{deta}' => ['note', 'cool_note', '<strong>NOTE:</strong><small>Free air evacuation to Nairobi and delivery after evacuation to a hospital'
                    . ' of your choice in Nairobi as well as free evacuation'
                    . ' as a result of life threatening accident or sickness.</small>'],
                '{bands}' => ['radios', 'cover_type', $this->data->bands, 'band1', ['class' => 'form-control', 'required' => '']],
                'In addition to taking cover on yourself, do you wish to cover your spouse and children?'
                => ['radios', 'other_covers', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If yes, How many others would you like to cover?' => ['select', 'howmany', '', ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10], ['class' => 'form-control']],
                '{trap}' => ['note', 'others', '<div id="other_covers_div"></div>'],
                '{dec}' => ['note', 'declaration', 'accceptterms',
                    '<p><strong>The liability of the Jubilee Insurance Company of Kenya Limited does not commence until the proposal has been accepted and the premium paid and cover confirmed by Jubilee.</strong></p><p></p>
                    <p><strong>DECLARATION</strong></p><p></p>
                    <p>I/We do hereby declare that the above answers and statements are true and that I/We have withheld no material information regarding this proposal. I/We agree that this Declaration and the answers given above, as well as any proposal or declaration or statement made in writing by me/us or anyone acting on my/our behalf shall form the basis of the contract between me/us and The Jubilee Insurance Company of Kenya Limited, and I/We further agree to accept indemnity subject to the conditions in and endorsed on the The Jubilee Insurance Company of Kenya Limited Policy. I/We also declare that any sums expressed in this proposal represent not less that the full value of the insurable property mentioned above.</p>'],
                'I hereby agree to all the above terms and conditions' => ['radios', 'acceptterms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Quotation and Payment >>', ['class' => 'btn btn-success']]
            ]
        ];

        if ($this->want_schematic) {
            return $schematic;
        }
        $form = Generate::Form('accident_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('cover_details');
    }

    /**
     * Step 1 form for personal accidents
     * @return array
     */
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

        if ($this->want_schematic) {
            return $schematic;
        }

        $form = Generate::Form('accident_other_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('accident_details');
    }

    /**
     * Form to capture personal details
     * @return array
     */
    private function personalDetails()
    {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => 'none',
            'method' => 'post',
            'map' => [3, 3, 2, 3, 3, 1, 1, 3, 1],
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
                'ID/Passport No' => ['text', 'id_passport_no', '', ['class' => 'form-control', 'required' => '']],
                'Mobile Number *' => ['text', 'mobile', '', ['class' => 'form-control', 'required' => '']],
                '{notice}' => ['note', 'notice2', '<p>Please let us know here about your Beneficiary (s) or Trustee in the event of Accidental Death.</p>'],
                'Name *' => ['text', 'beneficiary_name', '', ['class' => 'form-control', 'required' => '']],
                'P.O Box' => ['text', 'beneficiary_address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code ' => ['text', 'beneficiary_code', '', ['class' => 'form-control', 'required' => '']],
                'Town' => ['select', 'beneficiary_town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Personal Accident Details >>', ['class' => 'btn btn-success']]
            ]
        ];

        if ($this->want_schematic) {
            return $schematic;
        }

        $modal_container = Elements::call('Customers/CustomersController')->loadLoginContainer('accident');

        $this->set('modal_container', $modal_container);
        $this->set('modal_link', '<a href="' . Url::route('/customer/loadlogin/{element}', ['element' => 'accident']) . '"
         data-target="#customer_login_modal" id="load_login_btn" data-toggle="modal"></a>');

        $form = Generate::Form('accident_personal_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('personal_details');
    }

    /**
     * Display quotations on front end
     */
    private function showQuotations()
    {
        $this->set('data', $this->data);
        $this->set('data_array', $this->data->payments);
        $this->setViewPanel('personal_accident');
    }

    /**
     * Generate human-readable indices for the display
     * @return array
     */
    public function accidentIndices()
    {
        $prefix = 'Has any company in respect of life assurance or accident insurance ever ';
        $particulars = 'If Yes, Please provide particulars';

        $indices = [
            // step two
            'refused_cover' => $prefix . 'refused you cover?',
            'cover_refused_particulars' => $particulars,
            'decline_cover' => $prefix . 'declined to renew your insurance?',
            'cover_decline_particulars' => $particulars,
            'demand_increased_rate' => $prefix . 'demanded an increased rate?',
            'increased_rate_particulars' => $particulars,
            'imposed_special_terms' => $prefix . 'imposed any special terms?',
            'special_terms_particulars' => $particulars,
            'hobbies_injury_liable' => 'Are there circumstances connected with your pursuits or mode of life or
                    hobbies which render you specially liable for injury?',
            'injury_liable_particulars' => $particulars,
            'ever_made_claims' => 'Has any proposed insured ever made a claim in respect to Personal Accident?',
            'made_claims_particulars' => $particulars,

            // step three
            'coverstart' => 'Cover Start Date',
            'coverend' => 'Cover End Date',
            'cover_class' => 'Cover Class',
            'cover_type' => 'Cover Type',
            'other_covers' => 'In addition to taking cover on yourself, do you wish to cover your spouse and children?',
            'howmany' => 'If yes, How many others would you like to cover?',
            'acceptterms' => 'Agree to Terms and Conditions?'
        ];

        return $indices;
    }

    /**
     * Get band details
     * @param $band
     * @return mixed
     */
    public function getBand($band)
    {
        $bands = [
            'band1' => 'Band 1: Covers you upto a limit of 250000 for accidental death',
            'band2' => 'Band 2: Covers you upto a limit of 500000 for accidental death',
            'band3' => 'Band 3: Covers you upto a limit of 1000000 for accidental death',
            'band4' => 'Band 4: Covers you upto a limit of 2000000 for accidental death',
            'band5' => 'Band 5: Covers you upto a limit of 4000000 for accidental death',
            'band6' => 'Band 6: Covers you upto a limit of 8000000 for accidental death',
            'band7' => 'Band 7: Covers you upto a limit of 10000000 for accidental death'
        ];

        return $bands[$band];
    }
}
