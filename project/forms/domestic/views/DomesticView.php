<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Domestic\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Views\View;

/**
 * Class DomesticView
 */
class DomesticView extends View {

    private $data;

    public function wizard($data = null) {
        $this->data = $data;
        switch ($this->data->step) {
            case "1":
                $this->personalDetails();
                break;
            case "2":
                $this->properyDetails();
                break;
            case "3":
                $this->coverDetails();
                break;
            case "4":
                $this->showQuotation();
                break;
        }
    }

    public function coverDetails() {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/domestic/save/3',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [2, 3, 2, 2, 1, 2, 1, 2, 1, 2, 2, 2, 1, 1, 2, 1, 1, 4, 4, 4, 4, 4, 1, 1, 1, 1],
            'controls' => [
                'Period of Insurance: From:' => ['select', 'period_of_insurance_from', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                'Period of Insurance: To:' => ['select', 'period_of_insurance_to', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                'Current or past insurer *' => ['select', 'previous_insurer', '', $this->data->insurer, ['class' => 'form-control', 'required' => '']],
                'Policy No' => ['text', 'policyno', '', ['class' => 'form-control']],
                'Currrent renewal premium (Kshs)' => ['text', 'premium', '', ['class' => 'form-control']],
                'Do you require SECTION A: BUILDINGS * cover?' =>
                ['radios', 'section_a_cover', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Please indicate total sum insured for SECTION A Kshs' => ['text', 'a_premium', '', ['class' => 'form-control']],
                'Do you require SECTION B: CONTENTS* cover?' =>
                ['radios', 'section_b_cover', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Please indicate total sum insured for SECTION B Kshs' => ['text', 'b_premium', '', ['class' => 'form-control']],
                '{funitures}' => ['note', 'funitures', '<strong>Furniture and fittings: Specify any item over Kshs. 50,000/= or 5% of Total Sum Insured under section B</strong>'],
                '{furniture1}' => ['textarea', 'funiture1', '', ['placeholder' => 'Item (s) and value (s)', 'rows' => 2, 'class' => 'form-control']],
                '{furniture2}' => ['textarea', 'funiture2', '', ['placeholder' => 'Value (s) in Kshs - figures only separate using commas', 'rows' => 2, 'class' => 'form-control']],
                //furnishing
                '{furnishing}' => ['note', 'furnishing', '<strong>Furnishing, linen, clothing (including beddings, carpets, curtains, showers etc.): Specify any item over Kshs. 50,000/= or 5% of Total Sum Insured under section B</strong>'],
                '{furnishing1}' => ['textarea', 'furnishing1', '', ['placeholder' => 'Item (s) and value (s)', 'rows' => 2, 'class' => 'form-control']],
                '{furnishing2}' => ['textarea', 'furnishing2', '', ['placeholder' => 'Value (s) in Kshs - figures only separate using commas', 'rows' => 2, 'class' => 'form-control']],
                //furnishing
                '{miscellaneous}' => ['note', 'miscellaneous', '<strong>Miscellaneous (including wines and spirits, tools, toys, gadgets, cutery, crockery, lighting accessories, etc.): Specify any item over Kshs. 50,000/= or 5% of Total Sum Insured under section B</strong>'],
                '{miscellaneous1}' => ['textarea', 'miscellaneous1', '', ['placeholder' => 'Item (s) and value (s)', 'rows' => 2, 'class' => 'form-control']],
                '{miscellaneous2}' => ['textarea', 'miscellaneous2', '', ['placeholder' => 'Value (s) in Kshs - figures only separate using commas', 'rows' => 2, 'class' => 'form-control']],
                //lasty
                'Do you require SECTION C All RISKS* cover?' =>
                ['radios', 'section_c_cover', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Please indicate total sum insured for SECTION C Kshs' => ['text', 'c_premium', '', ['class' => 'form-control']],
                //creepy
                //furnishing
                // '{furnishing}' => ['note', 'furnishing', '<strong>Furnishing, linen, clothing (including beddings, carpets, curtains, showers etc.): Specify any item over Kshs. 50,000/= or 5% of Total Sum Insured under section B</strong>'],
                '{extra1}' => ['textarea', 'extra1', '', ['placeholder' => 'Item (s) and value (s)', 'rows' => 2, 'class' => 'form-control']],
                '{extra2}' => ['textarea', 'extra2', '', ['placeholder' => 'Value (s) in Kshs - figures only separate using commas', 'rows' => 2, 'class' => 'form-control']],
                // '{noted3}' => ['note', 'noted1', "noted1", "If the answer is yes, please provide details for the last five years below"],
                'Do you require any of of the following additional cover? (subject to additional charge)' =>
                ['checkboxes', 'addons', $this->data->addons],
                'Specify No of Domestic servants' => ['text', 'domestic_servants', '', ['class' => 'form-control']],
                'Owner Liablity' => ['select', 'owner_liabilty', '', $this->data->liabilities, ['class' => 'form-control']],
                'Occupiers Liability' => ['select', 'occupiers_liabilty', '', $this->data->liabilities, ['class' => 'form-control']],
                'Have you had any claims or losses in respect of any of the risks to which this proposal applies' =>
                ['radios', 'claims_lost', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                '{space4}' => ['note', 'space4', '<p>If the answer is yes, please provide details for the last five years below</p>'],
                // '{note1}' => ['note', 'note1', "note1", "<h4>Year 1</h4><hr/>"],
                "Year 1: Claim No" => ['text', "claim_no_yr1", '', ['class' => 'form-control',]],
                "Year 1: Claim Amount" => ['text', "claim_amount_yr1", '', ['class' => 'form-control',]],
                "Year 1: Claim Details" => ['text', "claim_details_yr1", '', ['class' => 'form-control',]],
                "Year 1: Insurer" => ['select', "insurer_yr1", '', $this->data->insurer, ['class' => 'form-control',]],
                //2
                // '{note2}' => ['note', 'note2', "note2", "<h4>Year 2</h4><hr/>"],
                "Year 2: Claim No" => ['text', "claim_no_yr2", '', ['class' => 'form-control',]],
                "Year 2: Claim Amount" => ['text', "claim_amount_yr2", '', ['class' => 'form-control',]],
                "Year 2: Claim Details" => ['text', "claim_details_yr2", '', ['class' => 'form-control',]],
                "Year 2: Insurer" => ['select', "insurer_yr2", '', $this->data->insurer, ['class' => 'form-control',]],
                //3
                //'{note3}' => ['note', 'note3', "note3", "<h4>Year 3</h4><hr/>"],
                "Year 3: Claim No" => ['text', "claim_no_yr3", '', ['class' => 'form-control',]],
                "Year 3: Claim Amount" => ['text', "claim_amount_yr3", '', ['class' => 'form-control',]],
                "Year 3: Claim Details" => ['text', "claim_details_yr3", '', ['class' => 'form-control',]],
                "Year 3: Insurer" => ['select', "insurer_yr3", '', $this->data->insurer, ['class' => 'form-control',]],
                //4
                // '{note4}' => ['note', 'note4', "note4", "<h4>Year 4</h4><hr/>"],
                "Year 4: Claim No" => ['text', "claim_no_yr4", '', ['class' => 'form-control',]],
                "Year 4: Claim Amount" => ['text', "claim_amount_yr4", '', ['class' => 'form-control',]],
                "Year 4: Claim Details" => ['text', "claim_details_yr4", '', ['class' => 'form-control',]],
                "Year 4: Insurer" => ['select', "insurer_yr4", '', $this->data->insurer, ['class' => 'form-control',]],
                //5
                //'{note5}' => ['note', 'nots5', "note5", "<h4>Year 5</h4><hr/>"],
                "Year 5: Claim No" => ['text', "claim_no_yr5", '', ['class' => 'form-control',]],
                "Year 5: Claim Amount" => ['text', "claim_amount_yr5", '', ['class' => 'form-control',]],
                "Year 5: Claim Details" => ['text', "claim_details_yr5", '', ['class' => 'form-control',]],
                "Year 5: Insurer" => ['select', "insurer_yr5", '', $this->data->insurer, ['class' => 'form-control',]],
                //end
                ' Where or how would you like to get your motor certificate? (Please note: You will need an ID or PIN certificate, No Claim Discount (NCD) Letter, and log book copy to collect your motor certificate)'
                => ['select', 'pickat', '', $this->data->pick_cert, ['class' => 'form-control', 'required' => '']],
                '{dec}' => ['note', 'declaration', 'accceptterms',
                    '<p><strong>The liability of the Jubilee Insurance Company of Kenya Limited does not commence until the proposal has been accepted and the premium paid and cover confirmed by Jubilee.</strong></p><p></p>
                    <p><strong>DECLARATION</strong></p><p></p>
                    <p>I/We do hereby declare that the above answers and statements are true and that I/We have withheld no material information regarding this proposal. I/We agree that this Declaration and the answers given above, as well as any proposal or declaration or statement made in writing by me/us or anyone acting on my/our behalf shall form the basis of the contract between me/us and The Jubilee Insurance Company of Kenya Limited, and I/We further agree to accept indemnity subject to the conditions in and endorsed on the The Jubilee Insurance Company of Kenya Limited Policy. I/We also declare that any sums expressed in this proposal represent not less that the full value of the insurable property mentioned above.</p>'],
                'I hereby agree to all the above terms and conditions' => ['radios', 'acceptterms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Quotation and Payment >>', ['class' => 'btn btn-success']]
            ],
        ];
        $form = Generate::Form('domestic_cover_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('cover_details');
    }

    private function properyDetails() {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            'action' => '/domestic/save/2',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => [3, 1, 2, 1, 2, 1, 1, 1, 3, 2, 2, 1, 1, 1, 2, 2, 1, 1, 1, 1],
            'controls' => [
                'Situation of Premises: Plot No: ' => ['text', 'plot_no', '', ['class' => 'form-control', 'required' => '']],
                'Road/Street *' => ['text', 'road', '', ['class' => 'form-control', 'required' => '']],
                'Town *' => ['text', 'town', '', ['class' => 'form-control', 'required' => '']],
                '{note1}' => ['note', 'my_note', '<p>What material has been used to construct the building(s) </p>'],
                'Walls *' => ['select', 'wall_materials', '', $this->data->walls, ['class' => 'form-control', 'required' => '']],
                'Roofs *' => ['select', 'roof_material', '', $this->data->roofs, ['class' => 'form-control', 'required' => '']],
                'Are there any outbuildings?' => ['radios', 'outbuildimgs', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Outbuilding Walls *' => ['select', 'outbuilding_wall_materials', '', $this->data->walls, ['class' => 'form-control', 'required' => '']],
                'Outbuilding Roofs *' => ['select', 'outbuilding_roof_material', '', $this->data->roofs, ['class' => 'form-control', 'required' => '']],
                '* Is any business, profession or trade carried on in any portion of the premises of which the dwelling forms a part?' =>
                ['radios', 'anybusiness', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, please give particulars below' => ['textarea', 'particulars', '', ['class' => 'form-control', 'rows' => 2]],
                'What type of dwelling is it?' =>
                ['select', 'dwelling_type', '', $this->data->dwelling, ['class' => 'form-control', 'required' => '']],
                'Do you own the dwelling?' => ['radios', 'ownership', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If No, who owns it' => ['text', 'owner_of_dwelling', '', ['class' => 'form-control', 'required']],
                'Give Financier Name' => ['text', 'financier', '', ['class' => 'form-control', 'required']],
                'Is the dwelling solely in your occupation?' => ['radios', 'sole_occupation_dwelling', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'if No, do you let or receive boarders?' => ['radios', 'to_let', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Will the dwelling be left without an inhabitant for more than seven consecutive days?' =>
                ['radios', 'dwelling_inhabitation', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'if so, state to what extent (in weeks)' =>
                ['select', 'state_period', '', [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6], ['class' => 'form-control',]],
                'Are the Buildings in a good state of repair and will they be so maintained' =>
                ['radios', 'good_state_building', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'What security is in place (tick all that is appropriate)' =>
                ['checkboxes', 'security', $this->data->security, ['class' => 'form-control', 'rows' => 2]],
                'If Others, please specify' => ['textarea', 'other_security', '', ['class' => 'form-control', 'rows' => 2]],
                'Has any Company or Insurer, in respect of any of the risks to which the proposal applies, declined to insure you' =>
                ['radios', 'previous_declined', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Required special terms?' => ['radios', 'special_terms', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Cancelled or refused to renew your Insurance?' => ['radios', 'cancelled_insurance', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'Increased your premium at renewal?' => ['radios', 'increased_premium', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If the answer to any of the above is Yes, then please provide details below' =>
                ['textarea', 'specific_details', '', ['class' => 'form-control', 'rows' => 2]],
                'Do you have any other policies in force covering the property to which the proposal applies' =>
                ['radios', 'other_policies', ['yes' => 'Yes', 'no' => 'No'], 'no'],
                'If Yes, please give particulars' =>
                ['textarea', 'details_other_policies', '', ['class' => 'form-control', 'rows' => 2]],
                '{submit}' => ['submit', 'btnSubmitSpecial', 'Proceed to Cover Details >>', ['class' => 'btn btn-success',]]
            ]
        ];
        $form = Generate::Form('domestic_property_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('property_details');
    }

    private function personalDetails() {
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => 'none',
            'method' => 'post',
            'map' => [2, 2, 2, 2, 2, 1],
            'action' => '/domestic/save/1',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                'Proposer Surname *' => ['text', 'surname', '', ['class' => 'form-control', 'required' => '']],
                'Other Names *' => ['text', 'names', '', ['class' => 'form-control', 'required' => '']],
                'PIN No. *' => ['text', 'pin', '', ['class' => 'form-control', 'required' => '']],
                'ID or Passport No. *' => ['text', 'idnumber', '', ['class' => 'form-control', 'required' => '']],
                'Telephone Contacts*' => ['text', 'telephone_contacts', '', ['class' => 'form-control', 'required' => '']],
                'Email*' => ['text', 'email', '', ['class' => 'form-control', 'required' => '']],
                'Postal Address: P.O Box*' => ['text', 'address', '', ['class' => 'form-control', 'required' => '']],
                'Postal Code *' => ['text', 'code', '', ['class' => 'form-control', 'required' => '']],
                'Street *' => ['text', 'street', '', '', ['class' => 'form-control', 'required' => '']],
                'Town *:' => ['select', 'town', '', $this->data->towns, ['class' => 'form-control', 'required' => '']],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Property Details >>', ['class' => 'btn btn-success pull-right']]
            ]
        ];
        $form = Generate::Form('domestic_personal_details', $schematic)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        $this->set('form', $form);
        $this->setViewPanel('personal_details');
    }

    private function showQuotation() {
        $this->set('data', $this->data);
        $this->setViewPanel('quotations');
    }

}
