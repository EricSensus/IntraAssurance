<?php

namespace Jenga\MyProject\Travel\Views;

use Jenga\App\Request\Url;
use Jenga\App\Views\View;
use Jenga\App\Html\Generate;
use Jenga\MyProject\Elements;

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
class TravelView extends View
{
    private $data;
    private $btn_type = 'submit';
    private $want_schematic = false;
    private $controls;

    /**
     * Load the appropriate schematic form
     * @param null $data
     * @param bool $controls
     * @return array|mixed|null|void
     */
    public function getSchematic($data = null, $controls = false)
    {
        $this->controls = $controls;
        $this->want_schematic = true;
        $this->btn_type = 'button';
        return $this->wizard($data);
    }

    /**
     * show the respective form for each step
     * @param null $data
     * @return array|mixed|null|void
     */
    public function wizard($data = null)
    {
        $this->data = $data;
        $x = null;
        switch ($this->data->step) {
            case "1":
                $x = $this->stepOne();
                break;
            case "2":
                $x = $this->stepTwo();
                break;
            case "3":
                $x = $this->stepThree();
                break;
            case "4":
                $x = $this->stepFour();
                break;
        }
        if ($this->want_schematic) {
            return ($this->controls) ? $x : $x['form'];
        }
    }

    /**
     * Personal Details
     * @param type $countries
     * @return array
     * @return array
     */
    public function stepOne($countries = null)
    {
        if (is_null($countries))
            $countries = $this->data->countries;

        $controls = [
            'Select Title *' => ['select', 'title', '', [
                'Mr' => 'Mr',
                'Mrs' => 'Mrs',
                'Ms' => 'Ms',
                'Dr' => 'Dr',
                'Prof' => 'Prof',
                'Eng' => 'Eng'
            ], ['class' => 'form-control', 'required' => '', 'autofocus' => '']],
            'Proposer Surname *' => ['text', 'surname', '', ['class' => 'form-control', 'required' => '']],
            'Other Names *' => ['text', 'names', '', ['class' => 'form-control', 'required' => '']],
            'Date of Birth *' => ['text', 'dob', '', ['class' => 'form-control datepicker', 'required' => '']],
            'Passport No *' => ['text', 'id_passport_no', '', ['class' => 'form-control', 'required' => '']],
            'Postal Address *' => ['text', 'address', '', ['class' => 'form-control', 'required' => '']],
            'Mobile No *' => ['text', 'mobile', '', ['class' => 'form-control', 'required' => '']],
            'Email Address *' => ['text', 'email', '', ['class' => 'form-control', 'required' => '']],

            'Destination *' => ['text', 'destination', '', ['class' => 'form-control', 'required' => '']],
            'Country *' => ['select', 'country', '', $countries, ['class' => 'form-control', 'required' => '']],
            'Address at Destination' => ['text', 'addess_at_destination', '', ['class' => 'form-control']],
            'Phone at Destination' => ['text', 'phone_at_destination', '', ['class' => 'form-control']],
            'Travel Airline Company' => ['text', 'travel_airline_company', '', ['class' => 'form-control']],
            'Trip Type(Holiday/Business) *' => ['select', 'trip_type', '', [
                'Holiday' => 'Holiday',
                'Business' => 'Business'
            ], ['class' => 'form-control', 'required' => '']],
            'Other Destination' => ['text', 'other_destination', '', ['class' => 'form-control']],
            '{next}' => [
                'note', 'next_of_kin', 'next_of_kin', '<p>Please let us know here about your next of Kin</p>'],
            'Name' => ['text', 'nok_name', '', ['class' => 'form-control']],
            'Relationship' => ['text', 'nok_relationship', '', ['class' => 'form-control']],
            'Postal Address' => ['text', 'nok_postal_address', '', ['class' => 'form-control']],
            'Contact No' => ['text', 'nok_contact_no', '', ['class' => 'form-control']],
            'Email' => ['text', 'nok_email', '', ['class' => 'form-control']],
            '{form_step}' => ['hidden', 'form_step', 'form_1', ['class' => 'form-control']],
            '{submit}' => [$this->btn_type, 'btnsubmit', 'Proceed to Cover Details >', $this->btn_type, ['class' => 'btn btn-primary pull-right']]
        ];
        $map = [3, 3, 2, 3, 2, 2, 1, 3, 2, 1];
        if ($this->want_schematic) {
            unset($controls['{submit}']);
            array_pop($map);
        }
        $form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/travel/save/1',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => $map,
            'controls' => $controls
        ];

        $step_one_form = Generate::Form('step_one', $form)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-6, col-sm-9'], TRUE);

        if ($this->want_schematic) {
            return [
                'form' => $step_one_form,
                'controls' => $controls
            ];
        }

        $this->set('modal_link', '<a href="' . Url::route('/customer/loadlogin/{element}', ['element' => 'travel']) . '"
         data-target="#customer_login_modal" id="load_login_btn" data-toggle="modal"></a>');

        $this->set('form', $step_one_form);
        $this->setViewPanel('step_one');
    }

    /**
     * Travel Details
     */
    public function stepTwo()
    {
        $controls = [
            '{note1}' => ['note', 'note1', 'note1', '<p>Please enter your other additional details below so that we can find the best insurance quote for you. Do not forget that you can always come back later to retrieve your quote or change your details</p>'],
            'Does any proposed insured suffer from physical defects or infirmities? *' => [
                'radios', 'physical_disability', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If Yes, Please provide particulars  ' => [
                'textarea', 'phy_dis_particulars', '', ['class' => 'form-control']
            ],
            'Are all proposed insured now in good health? *' => [
                'radios', 'good_health', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If Yes, Please provide particulars' => [
                'textarea', 'health_particulars', '', ['class' => 'form-control']
            ],
            'Is any of the proposed insured travelling for the purpose of receiving medical treatment?? *' => [
                'radios', 'medical_treatment', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If Yes, Please provide particulars ' => [
                'textarea', 'med_treat_particulars', '', ['class' => 'form-control']
            ],
            'Has any proposed insured been treated for or told they had diabetes, abnormal blood pressure, any disorder or disease of the heart, lung back or spine, a mental, nervous or weight condition, cancer,kidney or liver disease, alcoholism or drug addiction of any other disease? *' => [
                'radios', 'disorders', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If Yes, Please provide particulars    ' => [
                'textarea', 'dis_particulars', '', ['class' => 'form-control']
            ],
            'Has any proposed insured had any personal accident, sickness, baggage or travel insurance cancelled or declined or renewal refused? *' => [
                'radios', 'cancelled_prev_insurance', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If Yes, Please provide particulars     ' => [
                'textarea', 'prev_ins_cancelled', '', ['class' => 'form-control']
            ],
            'Is any proposed insured already a member of any medical/rescue insurance scheme?' => [
                'radios', 'already_insured', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If Yes, Please provide particulars      ' => [
                'textarea', 'already_ins_particulars', '', ['class' => 'form-control']
            ],
            'Has any proposed insured ever made a claim in respect travel insurance or loss of baggage? *' => [
                'radios', 'claimed', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If Yes, Please provide particulars        ' => [
                'textarea', 'claimed79_particulars', '', ['class' => 'form-control']
            ],
            '{form_step}' => ['hidden', 'form_step', 'form_2', ['class' => 'form-control']],
//                '{submit}' => [$this->btn_type, 'submitsteptwoform', 'Proceed to Cover Details >',$this->btn_type, ['class' => 'btn btn-primary pull-right']]
            '{submit}' => [$this->btn_type, 'btnsubmit', 'Proceed to Cover Details >', $this->btn_type, ['class' => 'btn btn-primary pull-right']]
        ];
        $map = [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1];
        if ($this->want_schematic) {
            unset($controls['{submit}']);
            array_pop($map);
        }
        $form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/travel/save/2',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => $map,
            'controls' => $controls
        ];

        $step_two_form = Generate::Form('step_two', $form)
            ->render([
                'orientation' => 'horizontal',
                'columns' => 'col-sm-6, col-sm-9'
            ], true);

        if ($this->want_schematic) {
            return [
                'form' => $step_two_form,
                'controls' => $controls
            ];
        }

        $this->set('form', $step_two_form);
        $this->setViewPanel('step_two');
    }

    /**
     * Get the descriptive indices
     * @param int $no_of_companions
     * @return array
     */
    public function travelIndices($no_of_companions = 0)
    {
        $indices = [
            // step two
            'physical_disability' => 'Does any proposed insured suffer from physical defects or infirmities?',
            'phy_dis_particulars' => 'If Yes, Please provide particulars',
            'good_health' => 'Are all proposed insured now in good health?',
            'health_particulars' => 'If Yes, Please provide particulars',
            'medical_treatment' => 'Is any of the proposed insured travelling for the purpose of receiving medical treatment?',
            'med_treat_particulars' => 'If Yes, Please provide particulars',
            'disorders' => 'Has any proposed insured been treated for or told they had diabetes, abnormal blood pressure, any disorder or disease of the heart, lung back or spine, a mental, nervous or weight condition, cancer,kidney or liver disease, alcoholism or drug addiction of any other disease?',
            'dis_particulars' => 'If Yes, Please provide particulars',
            'cancelled_prev_insurance' => 'Has any proposed insured had any personal accident, sickness, baggage or travel insurance cancelled or declined or renewal refused?',
            'prev_ins_cancelled' => 'If Yes, Please provide particulars',
            'already_insured' => 'Is any proposed insured already a member of any medical/rescue insurance scheme?',
            'already_ins_particulars' => 'If Yes, Please provide particulars',
            'claimed' => 'Has any proposed insured ever made a claim in respect travel insurance or loss of baggage?',
            'claimed79_particulars' => 'If Yes, Please provide particulars',

            // step three
            'no_travel_days' => 'Approximately how many days will you be on travel?',
            'add_travel_companions' => 'Do you want to add any travel companions to this policy?',
            'cover_plan' => 'Cover Plan',
            'no_of_travel_companions' => 'If yes, How many others would you like to cover?',
            'i_agree' => 'Agree to terms and conditions'
        ];

        // other covers
        for ($i = 1; $i <= $no_of_companions; $i++) {
            $indices['companion_name' . $i] = 'Companion Name';
            $indices['companion_occupation' . $i] = 'Companion Occupation';
            $indices['companion_dob' . $i] = 'Companion Date of Birth';
            $indices['companion_relationship' . $i] = 'Relationship to Proposer';
            $indices['companion_passport' . $i] = 'Companion Passport';
            $indices['companion_plan' . $i] = 'Companion Cover Plan';
            $indices['companion_no_of_days' . $i] = 'Companion Travel Days';
        }

        return $indices;
    }

    /**
     * Cover Details
     */
    public function stepThree($no_travel_companions = null)
    {
        if (is_null($no_travel_companions))
            $no_travel_companions = $this->data->no_travel_companions;

        $controls = $this->loadControls($no_travel_companions);
        if ($this->want_schematic) {
            unset($controls['{submit}']);
        }
        $st3form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/travel/save/3',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => $controls
        ];

        $step_three_form = Generate::Form('step_three', $st3form)
            ->render(ABSOLUTE_PROJECT_PATH . DS . 'forms' . DS . 'travel' . DS . 'views' . DS . 'panels' . DS . 'custom.php', TRUE);

        if ($this->want_schematic) {
            return [
                'form' => $step_three_form,
                'controls' => $controls
            ];
        }

        $this->set('step3fullform', $step_three_form);
        $this->setViewPanel('step_three');
    }

    /**
     * Africa basic plan:Africa Basic Plan - Covers you upto a limit of US$15000 for medical expenses and hospitalization abroad plus...,europe plus plan:Europe Plus Plan - Covers you upto a limit of US$45000 for medical expenses and hospitalisation abroad plus...,world wide basic plan:World Wide Basic Plan - Covers you upto a limit of US$45000 for medical expenses and hospitalisation abroad plus...,world wide plus plan:World Wide Plus Plan - Covers you upto a limit of US$67500 for medical expenses and hospitalisation abroad plus,world wide extra:World Wide Extra - Covers you upto a limit of US$150000 for medical expenses and hospitalisation abroad plus...,haj and umra basic plan:Haj & Umra Basic Plan - Covers you upto a limit of US$10000,haj and umra plus plan:Haj & Umra Plus Plan - Covers you upto a limit of US$10000,haj and umra extra plan:Haj & Umra Extra Plan - Covers you upto a limit of US$10000
     */
    public function loadControls($no_travel_companions)
    {
        $controls = [
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
                'radios', 'add_travel_companions', ['yes' => 'Yes', 'no' => 'No']
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
                ], [
                    'required' => ''
                ]
            ],
            'If yes, How many others would you like to cover?' => [
                'select', 'no_of_travel_companions', '', $no_travel_companions, ['class' => 'form-control', 'disabled' => '']
            ],
            '{note2}' => ['note', 'note2', 'note2', '<p><strong>The liability of the Jubilee Insurance Company of Kenya 
            Limited does not commence until the proposal has been accepted and the premium paid and cover confirmed by Jubilee.</strong></p>
            <p><strong>DECLARATION</strong></p><p></p>
            <p>I/We do hereby declare that the above answers and statements are true and that I/We have withheld no material information regarding this proposal. I/We agree that this Declaration and the answers given above, as well as any proposal or declaration or statement made in writing by me/us or anyone acting on my/our behalf shall form the basis of the contract between me/us and The Jubilee Insurance Company of Kenya Limited, and I/We further agree to accept indemnity subject to the conditions in and endorsed on the The Jubilee Insurance Company of Kenya Limited’s Policy. I/We also declare that any sums expressed in this proposal represent not less that the full value of the insurable property mentioned above.</p>'],
            'I hereby agree to all the above terms and conditions *' => [
                'radios', 'i_agree', ['yes' => 'Yes', 'no' => 'No']
            ],
            '{submit}' => [$this->btn_type, 'btnsubmit', 'Proceed to get a Quotation >', $this->btn_type, ['class' => 'btn btn-success pull-right']]
        ];
        return $controls;
    }

    /**
     * Display the quote on step four
     * @param $quote
     * @param bool $partial
     */
    public function stepFour($quote, $partial = false)
    {
        /*
        $quote = $quote[0];
        if (isset($quote['companions'])) {
            $companions = $quote['companions'];
            $tr = '';
            $subtotal = 0;
            foreach ($companions as $companion) {
                $other_total = $quote['basic_premium'];
                $subtotal += $other_total;

                $tr .= '<tr>';
                $tr .= '<th colspan="2">Name: ' . $companion['companion_name'] . '</th>';
                $tr .= '</tr>';

                $tr .= '<tr>';
                $tr .= '<td>Chosen Cover Plan: </td>';
                $tr .= '<td>' . $quote['cover_plan'] . '</td>';
                $tr .= '</tr>';

                $tr .= '<tr>';
                $tr .= '<td>Days of Travel:</td>';
                $tr .= '<td>' . $quote['days_of_travel'] . ' day(s)</td>';
                $tr .= '</tr>';

                $tr .= '<tr>';
                $tr .= '<td>Basic Premium: </td>';
                $tr .= '<td>Ksh. ' . number_format($other_total, 2) . '</td>';
                $tr .= '</tr>';

                $tr .= '<tr><td colspan="2">&nbsp;</td></tr>';
            }
        }

        $grand_total = $subtotal + $quote['basic_premium'] + $quote['training_levy'] + $quote['phcf'] + $quote['stamp_duty'];
        $quote['full_total'] = $grand_total;

        $this->set('companions', $tr);*/
        $this->set('_quote', $quote);
        $this->setViewPanel(($partial) ? 'quote_partial' : 'quotation');
    }

    /**
     * Get the cover plan name
     * @param $plan
     * @return string
     */
    public function getCover($plan)
    {
        if ($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if ($plan == 'africa_basic_plan')
            return 'Africa Basic Plan';
        else if ($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if ($plan == 'world_wide_basic_plan')
            return 'Worldwide Basic Plan';
        else if ($plan == 'world_wide_plus_plan')
            return 'Worldwide Plus Plan';
        else if ($plan == 'world_wide_extra')
            return 'Worldwide Extra';
        else if ($plan == 'haj_and_umra_basic_plan')
            return 'Haj and Umra Basic Plan';
        else if ($plan == 'haj_and_umra_plus_plan')
            return 'Haj and Umra Plus Plan';
        else if ($plan == 'haj_and_umra_extra_plan')
            return 'Haj and Umra Extra Plan';
    }

    /**
     * Create entity data array
     * @param $entity_data
     * @return array
     */
    public function createEntityDataArr($entity_data)
    {
        $entity_data_arr = [];

        $i = 0;
        foreach ($entity_data as $value) {
            $dep = $i + 1;

            $array = [
                'companion_name' . $dep => $value['name'],
                'companion_occupation' . $dep => $value['occupation'],
                'companion_dob' . $dep => $value['date_of_birth'],
                'companion_relationship' . $dep => $value['relationship'],
                'companion_passport' . $dep => $value['passport'],
                'companion_plan' . $dep => $value['plan'],
                'companion_no_of_days' . $dep => $value['travel_days']
            ];

            $entity_data_arr = array_merge($entity_data_arr, $array);

            $i++;
        }

        return $entity_data_arr;
    }
}
