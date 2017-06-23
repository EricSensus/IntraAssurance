<?php
namespace Jenga\MyProject\Travel\Views;
use Jenga\App\Views\View;
use Jenga\App\Html\Generate;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TravelView
 *
 * @author developer
 */
class TravelView extends View{
    /**
     * Personal Details
     * @param type $countries
     */
    public function stepOne($countries){
        $form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/travel/save/1',
            'attributes' => ['data-parsley-validate'=>''],
            'map'=>[3,3,2,3,2,2,1,3,2,1],
            'controls' => [
                'Select Title *' => ['select','title', '',[
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Ms' => 'Ms',
                    'Dr' => 'Dr',
                    'Prof' => 'Prof',
                    'Eng' => 'Eng'
                ], ['class'=>'form-control', 'required'=>'', 'autofocus'=>'']],
                'Proposer Surname *' => ['text','surname', '', ['class'=>'form-control', 'required'=>'']],
                'Other Names *' => ['text','names', '', ['class'=>'form-control', 'required'=>'']],
                'Date of Birth *' => ['text','dob', '', ['class'=>'form-control datepicker','required'=>'']],
                'Passport No *' => ['text','passport_no', '', ['class'=>'form-control', 'required'=>'']],
                'Postal Address *' => ['text','address', '', ['class'=>'form-control', 'required'=>'']],
                'Mobile No *' => ['text','mobile', '', ['class'=>'form-control', 'required'=>'']],
                'Email Address *' => ['text','email', '', ['class'=>'form-control', 'required'=>'']],

                'Destination *' => ['text','destination', '', ['class'=>'form-control', 'required'=>'']],
                'Country *' => ['select','country', '', $countries, ['class'=>'form-control', 'required'=>'']],
                'Address at Destination' => ['text','addess_at_destination', '', ['class'=>'form-control']],
                'Phone at Destination' => ['text','phone_at_destination', '', ['class'=>'form-control']],
                'Travel Airline Company' => ['text','travel_airline_company', '', ['class'=>'form-control']],
                'Trip Type(Holiday/Business) *' => ['select','trip_type', '',[
                    'Holiday' => 'Holiday',
                    'Business' => 'Business'
                ], ['class'=>'form-control', 'required'=>'']],
                'Other Destination' => ['text', 'other_destination', '', ['class' => 'form-control']],
                '{next}' => [
                    'note', 'next_of_kin', 'next_of_kin', '<p>Please let us know here about your next of Kin</p>'],
                'Name' => ['text', 'nok_name', '', ['class' => 'form-control']],
                'Relationship' => ['text', 'nok_relationship', '', ['class' => 'form-control']],
                'Postal Address' => ['text', 'nok_postal_address', '', ['class' => 'form-control']],
                'Contact No' => ['text', 'nok_contact_no', '', ['class' => 'form-control']],
                'Email' => ['text', 'nok_email', '', ['class' => 'form-control']],
                '{form_step}' => ['hidden', 'form_step', 'form_1', ['class' => 'form-control']],
                '{submit}' => ['submit', 'btnsubmit', 'Proceed to Motor Details >',['class'=>'btn btn-primary pull-right']]
            ]
        ];

        $step_one_form = Generate::Form('step_one', $form)->render(['orientation' => 'horizontal','columns' => 'col-sm-6, col-sm-9'],TRUE);
        $this->set('form', $step_one_form);
        $this->setViewPanel('step_one');
    }

    /**
     * Travel Details
     */
    public function stepTwo(){
        $form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/travel/save/2',
            'attributes' => ['data-parsley-validate'=>''],
            'map'=>[1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
            'controls' => [
                '{note1}' => ['note', 'note1', 'note1', '<p>Please enter your other additional details below so that we can find the best insurance quote for you. Do not forget that you can always come back later to retrieve your quote or change your details</p>'],
                'Does any proposed insured suffer from physical defects or infirmities? *' => [
                    'radios', 'physical_disability', [1 => 'Yes', 0 => 'No']
                ],
                'If Yes, Please provide particulars  ' => [
                    'textarea', 'phy_dis_particulars', '', ['class' => 'form-control']
                ],
                'Are all proposed insured now in good health? *' => [
                    'radios', 'good_health', [1 => 'Yes', 0 => 'No']
                ],
                'If Yes, Please provide particulars' => [
                    'textarea', 'health_particulars', '', ['class' => 'form-control']
                ],
                'Is any of the proposed insured travelling for the purpose of receiving medical treatment?? *' => [
                    'radios', 'medical_treatment', [1 => 'Yes', 0 => 'No']
                ],
                'If Yes, Please provide particulars ' => [
                    'textarea', 'med_treat_particulars', '', ['class' => 'form-control']
                ],
                'Has any proposed insured been treated for or told they had diabetes, abnormal blood pressure, any disorder or disease of the heart, lung back or spine, a mental, nervous or weight condition, cancer,kidney or liver disease, alcoholism or drug addiction of any other disease? *' => [
                    'radios', 'disorders', [1 => 'Yes', 0 => 'No']
                ],
                'If Yes, Please provide particulars    ' => [
                    'textarea', 'dis_particulars', '', ['class' => 'form-control']
                ],
                'Has any proposed insured had any personal accident, sickness, baggage or travel insurance cancelled or declined or renewal refused? *' => [
                    'radios', 'cancelled_prev_insurance', [1 => 'Yes', 0 => 'No']
                ],
                'If Yes, Please provide particulars     ' => [
                    'textarea', 'prev_ins_cancelled', '', ['class' => 'form-control']
                ],
                'Is any proposed insured already a member of any medical/rescue insurance scheme?' => [
                    'radios', 'already_insured', [1 => 'Yes', 0 => 'No']
                ],
                'If Yes, Please provide particulars      ' => [
                    'textarea', 'already_ins_particulars', '', ['class' => 'form-control']
                ],
                'Has any proposed insured ever made a claim in respect travel insurance or loss of baggage? *' => [
                    'radios', 'claimed', [1 => 'Yes', 0 => 'No']
                ],
                'If Yes, Please provide particulars        ' => [
                    'textarea', 'claimed79_particulars', '', ['class' => 'form-control']
                ],
                '{form_step}' => ['hidden', 'form_step', 'form_2', ['class' => 'form-control']],
                '{submit}' => ['submit', 'submitsteptwoform', 'Proceed to Cover Details >', ['class' => 'btn btn-primary pull-right']]
            ]
        ];

        $step_two_form = Generate::Form('step_two', $form)
            ->render([
                'orientation' => 'horizontal',
                'columns' => 'col-sm-6, col-sm-9'
            ], true);

        $this->set('form', $step_two_form);
        $this->setViewPanel('step_two');
    }

    /**
     * Cover Details
     */
    public function stepThree($no_travel_companions){
        $controls = $this->loadControls($no_travel_companions);

        $st3form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/travel/save/3',
            'attributes' => ['data-parsley-validate'=>''],
            'controls' => $controls
        ];

        $step_three_form = Generate::Form('step_three', $st3form)
            ->render(ABSOLUTE_PROJECT_PATH.DS.'forms'.DS.'travel'.DS.'views'.DS.'panels'.DS.'custom.php',TRUE);

        $this->set('step3fullform', $step_three_form);
        $this->setViewPanel('step_three');
    }
    /*
     africa basic plan:Africa Basic Plan - Covers you upto a limit of US$15000 for medical expenses and hospitalization abroad plus...,europe plus plan:Europe Plus Plan - Covers you upto a limit of US$45000 for medical expenses and hospitalisation abroad plus...,world wide basic plan:World Wide Basic Plan - Covers you upto a limit of US$45000 for medical expenses and hospitalisation abroad plus...,world wide plus plan:World Wide Plus Plan - Covers you upto a limit of US$67500 for medical expenses and hospitalisation abroad plus,world wide extra:World Wide Extra - Covers you upto a limit of US$150000 for medical expenses and hospitalisation abroad plus...,haj and umra basic plan:Haj & Umra Basic Plan - Covers you upto a limit of US$10000,haj and umra plus plan:Haj & Umra Plus Plan - Covers you upto a limit of US$10000,haj and umra extra plan:Haj & Umra Extra Plan - Covers you upto a limit of US$10000
     */
    public function loadControls($no_travel_companions){
        $controls =
        [
            '{note1}' => ['note', 'note1', 'note1', '<p>Please now enter your cover details below so that we can find the best insurance quote for you. Do not forget that you can always come back later to retrieve your quote or change your details</p>'],
            'Approximately how many days will you be on travel?' => ['select', 'no_travel_days', '', [
                7 => 'Upto 7 days',
                10 => 'Upto 10 days',
                15 => 'Upto 15 days',
                21 => 'Upto 21 days',
                30 => 'Upto 30 days',
                60 => 'Upto 60 days',
                92 => 'Upto 92 days',
                183 => 'Upto 183 days',
                365 => 'Upto 365 days'
            ], ['class' => 'form-control', 'required' => '']],
            'Do you want to add any travel companions to this policy?' => [
                'radios', 'add_travel_companions', [1 => 'Yes', 0 => 'No']
            ],
            'Plans' => [
                'radios', 'cover_plan', [
                    'africa basic plan' => 'Africa Basic Plan - Covers you upto a limit of US$15000 for medical expenses and hospitalization abroad plus...',
                    'europe plus plan' => 'Europe Plus Plan - Covers you upto a limit of US$45000 for medical expenses and hospitalisation abroad plus...',
                    'world wide basic plan' => 'World Wide Basic Plan - Covers you upto a limit of US$45000 for medical expenses and hospitalisation abroad plus...',
                    'world wide plus plan' => 'World Wide Plus Plan - Covers you upto a limit of US$67500 for medical expenses and hospitalisation abroad plus...',
                    'world wide extra' => 'World Wide Extra - Covers you upto a limit of US$150000 for medical expenses and hospitalisation abroad plus...',
                    'haj and umra basic plan' => 'Haj & Umra Basic Plan - Covers you upto a limit of US$10000',
                    'haj and umra plus plan' => 'Haj & Umra Plus Plan - Covers you upto a limit of US$10000',
                    'haj and umra extra plan' => 'Haj & Umra Extra Plan - Covers you upto a limit of US$10000'
                ],[
                    'required' => ''
                ]
            ],
            'If yes, How many others would you like to cover?' => [
                'select' , 'no_of_travel_companions', '', $no_travel_companions, ['class' => 'form-control', 'disabled' => '']
            ],
            '{note2}' => ['note', 'note2', 'note2', '<p><strong>The liability of the Jubilee Insurance Company of Kenya 
Limited does not commence until the proposal has been accepted and the premium paid and cover confirmed by Jubilee.</strong></p>
<p><strong>DECLARATION</strong></p><p></p>
<p>I/We do hereby declare that the above answers and statements are true and that I/We have withheld no material information regarding this proposal. I/We agree that this Declaration and the answers given above, as well as any proposal or declaration or statement made in writing by me/us or anyone acting on my/our behalf shall form the basis of the contract between me/us and The Jubilee Insurance Company of Kenya Limited, and I/We further agree to accept indemnity subject to the conditions in and endorsed on the The Jubilee Insurance Company of Kenya Limited’s Policy. I/We also declare that any sums expressed in this proposal represent not less that the full value of the insurable property mentioned above.</p>'],
            'I hereby agree to all the above terms and conditions *' => [
                'radios', 'i_agree', [1 => 'Yes', 0 => 'No']
            ],
            '{submit}' => ['submit', 'submitstepthreeform', 'Proceed to get a Quotation >', ['class' => 'btn btn-success pull-right']]
        ];
        return $controls;
    }

    public function stepFour($quote)
    {

        if(isset($quote['companions'])){
            $companions = $quote['companions'];

            $tr = '';
            $subtotal = 0;
            foreach($companions as $companion){
                $other_total = $quote['basic_premium'];
                $subtotal += $other_total;

                $tr .= '<tr>';
                $tr .= '<th colspan="2">Name: '.$companion['companion_name'].'</th>';
                $tr .= '</tr>';

                $tr .= '<tr>';
                $tr .= '<td>Chosen Cover Plan: </td>';
                $tr .= '<td>'.$quote['cover_plan'].'</td>';
                $tr .= '</tr>';

                $tr .= '<tr>';
                $tr .= '<td>Days of Travel:</td>';
                $tr .= '<td>'.$quote['days_of_travel'].' day(s)</td>';
                $tr .= '</tr>';

                $tr .= '<tr>';
                $tr .= '<td>Basic Premium: </td>';
                $tr .= '<td>Ksh. '.number_format($other_total,2).'</td>';
                $tr .= '</tr>';

                $tr .= '<tr><td colspan="2">&nbsp;</td></tr>';
            }
        }

        $grand_total = $subtotal + $quote['basic_premium'] + $quote['training_levy'] + $quote['phcf'] + $quote['stamp_duty'];
        $quote['full_total'] = $grand_total;

        $this->set('companions', $tr);
        $this->set('quote', $quote);
        $this->setViewPanel('quotation');
    }

    public function getCover($plan){
        if($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if($plan == 'africa_basic_plan')
            return 'Africa Basic Plan';
        else if($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if($plan == 'world_wide_basic_plan')
            return 'World Wide Basic Plan';
        else if($plan == 'world_wide_plus_plan')
            return 'World Wide Plus Plan';
        else if($plan == 'world_wide_extra')
            return 'World Wide Extra';
        else if($plan == 'haj_and_umra_basic_plan')
            return 'Haj and Umra Basic Plan';
        else if($plan == 'haj_and_umra_plus_plan')
            return 'Haj and Umra Plus Plan';
        else if($plan == 'haj_and_umra_extra_plan')
            return 'Haj and Umra Extra Plan';
    }
}
