<?php

namespace Jenga\MyProject\Medical\Views;

use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\View;
use Jenga\App\Html\Generate;
use Jenga\MyProject\Elements;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MedicalView
 *
 * @author developer
 */
class MedicalView extends View
{
    /**
     * @var
     */
    private $data;
    /**
     * @var string
     */
    private $btn_type = 'submit';
    /**
     * @var bool
     */
    private $want_schematic = false;
    /**
     * @var
     */
    private $controls;

    /**
     * Get the schematic array for each step
     * @param null $data
     * @param bool $controls
     * @return mixed
     */
    public function getSchematic($data = null, $controls = false)
    {
        $this->controls = $controls;
        $this->want_schematic = true;
        $this->btn_type = 'button';
        return $this->wizard($data);
    }

    /**
     * Show the correct form for each step
     * @param null $data
     * @return mixed
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
            case "22":
                $x = $this->stepDependants();
                break;
            case "3":
                $x = $this->stepThree();
                break;
            case "4":
                $x = $this->stepFour();
                break;
        }
        return ($this->controls) ? $x['controls'] : $x['form'];
    }

    /**
     * Show step one form
     * @param null $countries
     * @param null $cities
     * @return array
     */
    public function stepOne($countries = null, $cities = null)
    {
        if (!is_null($countries))
            $countries = $countries;
        else
            $countries = $this->data->countries;

        if (!is_null($cities))
            $cities = $cities;
        else
            $cities = $this->data->cities;

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
            'Gender *' => ['select', 'gender', '', ['Male' => 'Male', 'Female' => 'Female'], ['class' => 'form-control', 'required' => '']],
            'Enter age range bracket *' => ['select', 'age_range_bracket', '', [
                '1-18' => '1-18',
                '19-30' => '19-30',
                '31-40' => '31-40',
                '41-50' => '41-50',
                '51-59' => '51-59',
                '60-65' => '60-65'
            ], ['class' => 'form-control', 'required' => '']],
            'ID/Passport No ' => ['text', 'id_passport_no', '', ['class' => 'form-control', 'required' => '']],
            'NHIF ' => ['text', 'nhif', '', ['class' => 'form-control']],
            'Blood Type ' => ['select', 'blood_type', '', [
                'A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'
            ], ['class' => 'form-control']],
            'Nationality *' => ['select', 'country', '', $countries, ['class' => 'form-control', 'required' => '']],
            'Postal Address *' => ['text', 'address', '', ['class' => 'form-control', 'required' => '']],
            'Postal Code *' => ['text', 'code', '', ['class' => 'form-control', 'required' => '']],
            'City/Town *' => ['select', 'city_town', '', $cities, ['class' => 'form-control', 'required' => '']],
            'Residential/Physical Address *' => ['text', 'residential_physical_address', '', ['class' => 'form-control', 'required' => '']],
            'Road *' => ['text', 'road', '', ['class' => 'form-control', 'required' => '']],
            'Occupation/Profession *' => ['text', 'occupation_profession', '', ['class' => 'form-control', 'required' => '']],
            'Mobile Number *' => ['text', 'mobile', '', ['class' => 'form-control', 'required' => '']],
            'Email Address *' => ['text', 'email', '', ['class' => 'form-control', 'required' => '']],

            '{next}' => ['note', 'next_of_kin', 'next_of_kin', '<p>Please let us know here about your next of Kin</p>'],
            'Name' => ['text', 'nok_name', '', ['class' => 'form-control']],
            'Email' => ['text', 'nok_email', '', ['class' => 'form-control']],
            'Telephone No' => ['text', 'nok_contact_no', '', ['class' => 'form-control']],
            'Relationship' => ['text', 'nok_relationship', '', ['class' => 'form-control']],
            'ID/Passport No' => ['text', 'nok_id_pass', '', ['class' => 'form-control']],
            'Blood Group' => ['select', 'nok_blood_group', '', [
                'A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'
            ], ['class' => 'form-control']],
            'Postal Address' => ['text', 'nok_postal_address', '', ['class' => 'form-control']],
            'Postal Code' => ['text', 'nok_postal_code', '', ['class' => 'form-control']],
            'City/Town' => ['select', 'nok_city_town', '', $cities, ['class' => 'form-control']],
            '{form_step}' => ['hidden', 'form_step', 'form_1', ['class' => 'form-control']],
            '{submit}' => [$this->btn_type, 'btnsubmit', 'Proceed to Step 2 >', $this->btn_type, ['class' => 'btn btn-primary pull-right']]
        ];
        $map = [3, 3, 4, 4, 4, 1, 3, 3, 3, 1];
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
            'action' => '/medical/save/1',
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

        $this->set('modal_link', '<a href="' . Url::route('/customer/loadlogin/{element}', ['element' => 'medical']) . '"
         data-target="#customer_login_modal" id="load_login_btn" data-toggle="modal"></a>');

        $this->set('form', $step_one_form);
        $this->setViewPanel('step_one');
    }

    /**
     * Show step two form
     * @return array
     */
    public function stepTwo()
    {

        $controls = [
            'Please pick your core plan.' => ['radios', 'core_plans', [
                'premier' => 'Premier',
                'advanced' => 'Advanced',
                'executive' => 'Executive',
                'royal' => 'Royal'
            ]],
            'Out Patient' => ['checkboxes', 'ba1', ['ba1' => 'Yes']],
            'Last Expense' => ['checkboxes', 'bc1', ['bc1' => 'Yes']],
            'Personal Accident' => ['checkboxes', 'bd1', ['bd1' => 'Yes']],

            'Out Patient ' => ['checkboxes', 'ba2', ['ba2' => 'Yes']],
            'Last Expense ' => ['checkboxes', 'bc2', ['bc2' => 'Yes']],
            'Personal Accident ' => ['checkboxes', 'bd2', ['bd2' => 'Yes']],

            'Out Patient  ' => ['checkboxes', 'ba3', ['ba3' => 'Yes']],
            'Last Expense  ' => ['checkboxes', 'bc3', ['bc3' => 'Yes']],
            'Personal Accident  ' => ['checkboxes', 'bd3', ['bd3' => 'Yes']],
            'Executive Normal - Overall Limit Per year 60000' => ['checkboxes', 'bb1', ['bb1' => 'Yes']],
            'Executive Caesarrean - Overall limit Per year 120000' => ['checkboxes', 'bb3', ['bb3' => 'Yes']],

            'Out Patient   ' => ['checkboxes', 'ba4', ['ba4' => 'Yes']],
            'Last Expense   ' => ['checkboxes', 'bc4', ['bc4' => 'Yes']],
            'Personal Accident    ' => ['checkboxes', 'bd4', ['bd4' => 'Yes']],
            'Royal Normal - Overall Limit Per year 60000' => ['checkboxes', 'bb2', ['bb2' => 'Yes']],
            'Royal Caesarrean - Overall limit Per year 120000' => ['checkboxes', 'bb4', ['bb4' => 'Yes']],

            'In addition to taking cover on yourself, do you wish to cover your Spouse, children or any other dependants?' => [
                'radios', 'have_dependants', ['yes' => 'Yes', 'no' => 'No']
            ],
            'How many others would you like to cover? Choose number' => ['select', 'additional_covers', '', [
                1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8
            ], ['class' => 'form-control']],
            '{form_step}' => ['hidden', 'form_step', 'form_2', ['class' => 'form-control']],
            '{submit}' => [$this->btn_type, 'btnsubmit', 'Proceed to Step 2 >', $this->btn_type, ['class' => 'btn btn-primary pull-right']]
        ];
        if ($this->want_schematic) {
            unset($controls['{submit}']);
        }
        $form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/medical/save/2',
            'attributes' => ['data-parsley-validate' => ''],
//            'map'=>[3,3,2,3,4,3,1,2,2,1,1],
            'controls' => $controls
        ];

        $step_two_form = Generate::Form('step_two', $form)->render(ABSOLUTE_PROJECT_PATH . DS . 'forms' . DS . 'medical' . DS . 'views' . DS . 'panels' . DS . 'plans.php', TRUE);
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
     * Load dependant's forms/interfaces
     * @param null $countries
     * @param $no_of_dependants
     * @param bool $schema_string
     * @return array
     */
    public function stepDependants($countries = null, $no_of_dependants, $schema_string = false)
    {
        if (!is_null($countries))
            $countries = $countries;
        else
            $countries = $this->data->countries;

        $controls = $this->loadDependantControls($countries, $no_of_dependants);

        if ($schema_string) $this->btn_type = 'button';

        $controls['{form_step}'] = ['hidden', 'form_step', 'dependants', ['class' => 'form-control']];
      //  $controls['{submit}'] = [$this->btn_type, 'btnsubmit', 'Proceed to Step 2 >', $this->btn_type, ['class' => 'btn btn-primary pull-right']];
        if ($this->want_schematic) {
        //    unset($controls['{submit}']);
        }
        $form = [
            'preventjQuery' => TRUE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'POST',
            'action' => '/medical/save/dependants',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => $controls
        ];

        $step_dependants = Generate::Form('step_dependants', $form)
            ->render(ABSOLUTE_PROJECT_PATH . DS . 'forms' . DS . 'medical' . DS . 'views' . DS . 'panels' . DS . 'dependants.php', TRUE);

        if ($this->want_schematic == true || $schema_string == true) {
            return [
                'form' => $step_dependants,
                'controls' => $controls
            ];
        }

        $this->set('form', $step_dependants);
        $this->setViewPanel('step_dependants');
    }

    /**
     * Show step three form
     * @return array
     */
    public function stepThree()
    {
        $controls = [
            '{note1}' => ['note', 'note1', 'note1', '<p>Please enter your personal details below so that we can find the best insurance quote for you.Don\'t forget that you can always came back later to retrieve your quote or change your details.</p>'],
            '{note2}' => ['note', 'note2', 'note2', '<p><b>Please provide us here with details of any previous membership of a medical scheme</b></p>'],
            '{note3}' => ['note', 'note3', 'note3', '<b>Name of scheme/plan – Principal Applicant:</b>'],

            'Insurer' => ['text', 'pricipal_insurer', '', ['class' => 'form-control']],
            'Day' => ['text', 'pricipal_day', '', ['class' => 'form-control']],
            'Month' => ['text', 'pricipal_month', '', ['class' => 'form-control']],
            'Year' => ['text', 'pricipal_year', '', ['class' => 'form-control']],

            '{note4}' => ['note', 'note4', 'note4', '<b>Name of scheme/plan – Spouse:</b>'],
            'Insurer ' => ['text', 'spouse_insurer', '', ['class' => 'form-control']],
            'Day ' => ['text', 'spouse_day', '', ['class' => 'form-control']],
            'Month ' => ['text', 'spouse_month', '', ['class' => 'form-control']],
            'Year ' => ['text', 'spouse_year', '', ['class' => 'form-control']],

            'Have you or any of your dependants ever been declined, or had exclusions applied to them by a medical scheme? *' => [
                'radios', 'ever_cover_declined', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If yes, please provide particulars' => ['textarea', 'declined_particulars', '', ['class' => 'form-control']],

            'Have you or any of your dependants lodged a claim in the last one year under any other medical scheme? *' => [
                'radios', 'dependant_claimed_cover', ['yes' => 'Yes', 'no' => 'No']
            ],
            'If yes, please provide particulars ' => ['textarea', 'dep_claimed_particulars', '', ['class' => 'form-control']],
            '{note5}' => ['note', 'note5', 'note5', '<p><strong>The liability of the Jubilee Insurance Company of Kenya 
                Limited does not commence until the proposal has been accepted, the premium paid, and cover confirmed 
                by Jubilee Insurance Company of Kenya.</strong></p>
                <p><strong>DECLARATION</strong></p>
                <p>I/We do hereby declare that the above answers and statements are true and that I/We have withheld no material information regarding this proposal. I/We agree that this Declaration and the answers given above, as well as any proposal or declaration or statement made in writing by me/us or anyone acting on my/our behalf shall form the basis of the contract between me/us and The Jubilee Insurance Company of Kenya Limited, and I/We further agree to accept indemnity subject to the conditions in and endorsed on the The Jubilee Insurance Company of Kenya Limited’s Policy.</p>
                '],
            'I hereby agree to all the above terms and conditions *' => [
                'radios', 'i_agree', ['yes' => 'Yes', 'no' => 'No']
            ],
            '{form_step}' => ['hidden', 'form_step', 'form_3', ['class' => 'form-control']],
            '{submit}' => [$this->btn_type, 'btnsubmit', 'Get your Quotation >', $this->btn_type, ['class' => 'btn btn-primary pull-right']]
        ];
        $map = [1, 1, 1, 4, 1, 4, 1, 1, 1, 1, 1, 1, 1];
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
            'action' => '/medical/save/3',
            'attributes' => ['data-parsley-validate' => ''],
            'map' => $map,
            'controls' => $controls
        ];

        $step_three_form = Generate::Form('step_three', $form)->render(['orientation' => 'horizontal', 'columns' => 'col-sm-6, col-sm-9'], TRUE);

        if ($this->want_schematic) {
            return [
                'form' => $step_three_form,
                'controls' => $controls
            ];
        }

        $this->set('form', $step_three_form);
        $this->setViewPanel('step_three');
    }

    /**
     * Show dependants controls
     * @param $countries
     * @param $no_of_dependants
     * @return array
     */
    public function loadDependantControls($countries, $no_of_dependants)
    {
        $controls_array = [];
        for ($i = 1; $i <= $no_of_dependants; $i++) {
            $inputs = [
                'Dependant ' . $i . ': Select Title *' => ['select', 'title_' . $i, '', [
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Ms' => 'Ms',
                    'Dr' => 'Dr',
                    'Prof' => 'Prof',
                    'Eng' => 'Eng'
                ], ['class' => 'form-control', 'required' => '', 'autofocus' => '']
                ],
                'Dependant ' . $i . ': Proposer Surname *' => ['text', 'proposer_surname_' . $i, '', ['class' => 'form-control', 'required' => '']],
                'Dependant ' . $i . ': Other Names *' => ['text', 'other_names_' . $i, '', ['class' => 'form-control', 'required' => '']],
                'Dependant ' . $i . ': Date of Birth' => ['text', 'dob_' . $i, '', ['class' => 'form-control datepicker']],
                'Dependant ' . $i . ': Gender *' => ['select', 'gender_' . $i, '', ['Male' => 'Male', 'Female' => 'Female'], ['class' => 'form-control', 'required' => '']],
                'Dependant ' . $i . ': Enter age range bracket *' => ['select', 'age_range_bracket_' . $i, '', [
                    '1-18' => '1-18',
                    '19-30' => '19-30',
                    '31-40' => '31-40',
                    '41-50' => '41-50',
                    '51-59' => '51-59',
                    '60-65' => '60-65'
                ], ['class' => 'form-control', 'required' => '']],
                'Dependant ' . $i . ': ID/Passport No ' => ['text', 'id_passport_no_' . $i, '', ['class' => 'form-control']],
                'Dependant ' . $i . ': NHIF ' => ['text', 'nhif_' . $i, '', ['class' => 'form-control']],
                'Dependant ' . $i . ': Blood Type ' => ['select', 'blood_type_' . $i, '', [
                    'A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O'
                ], ['class' => 'form-control']],
                'Dependant ' . $i . ': Nationality' => ['select', 'nationality_' . $i, '', $countries, ['class' => 'form-control']],
                'Dependant ' . $i . ': Relation to Proposer' => ['select', 'relation_to_proposer_' . $i, '', [
                    'Wife', 'Brother', 'Sister', 'Parent', 'Son', 'Daughter'
                ], ['class' => 'form-control']],
                'Dependant ' . $i . ': Occupation' => ['text', 'occupation_' . $i, '', ['class' => 'form-control']],

                // Core Plans
                'Dependant ' . $i . ' Core Plan' => ['radios', 'core_plans_' . $i, [
                    'premier' => 'Premier',
                    'advanced' => 'Advanced',
                    'executive' => 'Executive',
                    'royal' => 'Royal'
                ]],
                'Dependant ' . $i . ' Premier Out Patient' => ['checkboxes', 'ba1_' . $i, ['ba1' => 'Yes']],
                'Dependant ' . $i . ' Premier Last Expense' => ['checkboxes', 'bc1_' . $i, ['bc1' => 'Yes']],
                'Dependant ' . $i . ' Premier Personal Accident' => ['checkboxes', 'bd1_' . $i, ['bd1' => 'Yes']],

                'Dependant ' . $i . ' Advanced Out Patient ' => ['checkboxes', 'ba2_' . $i, ['ba2' => 'Yes']],
                'Dependant ' . $i . ' Advanced Last Expense ' => ['checkboxes', 'bc2_' . $i, ['bc2' => 'Yes']],
                'Dependant ' . $i . ' Advanced Personal Accident ' => ['checkboxes', 'bd2_' . $i, ['bd2' => 'Yes']],

                'Dependant ' . $i . ' Executive Out Patient  ' => ['checkboxes', 'ba3_' . $i, ['ba3' => 'Yes']],
                'Dependant ' . $i . ' Executive Last Expense  ' => ['checkboxes', 'bc3_' . $i, ['bc3' => 'Yes']],
                'Dependant ' . $i . ' Executive Personal Accident    ' => ['checkboxes', 'bd3_' . $i, ['bd3' => 'Yes']],
                'Dependant ' . $i . ' Executive Normal - Overall Limit Per year  ' => ['checkboxes', 'bb1_' . $i, ['bb1' => 'Yes']],
                'Dependant ' . $i . ' Executive Caesarrean - Overall limit Per year  ' => ['checkboxes', 'bb3_' . $i, ['bb3' => 'Yes']],

                'Dependant ' . $i . ' Royal Out Patient   ' => ['checkboxes', 'ba4_' . $i, ['ba4' => 'Yes']],
                'Dependant ' . $i . ' Royal Last Expense   ' => ['checkboxes', 'bc4_' . $i, ['bc4' => 'Yes']],
                'Dependant ' . $i . ' Royal Personal Accident    ' => ['checkboxes', 'bd4_' . $i, ['bd4' => 'Yes']],
                'Dependant ' . $i . ' Royal Normal - Overall Limit Per year  ' => ['checkboxes', 'bb2_' . $i, ['bb2' => 'Yes']],
                'Dependant ' . $i . ' Royal Caesarrean - Overall limit Per year  ' => ['checkboxes', 'bb4_' . $i, ['bb4' => 'Yes']],

                // end core plans
                'Dependant ' . $i . ' Last Expense  ' => ['checkboxes', 'executivelastexpense_' . $i, [1 => 'Yes']],


            ];

            $controls_array = array_merge($controls_array, $inputs);
        }
        return $controls_array;
    }

    /**
     * Show step four which is actually the medical quote
     * @param $quote
     * @param bool $new_panel
     */
    public function stepFour($quote, $new_panel = false)
    {

        $this->set('_quote', $quote);
        $this->setViewPanel(($new_panel) ? 'quote_partial' : 'medical_quote');
    }

    /**
     * Load the descriptive names for indicies
     * @param int $no_of_dependants
     * @return array
     */
    public function medicalIndices($no_of_dependants = 0)
    {
        $indices = [
            // step two
            'core_plans' => 'Core Plan',
            'have_dependants' => 'In addition to taking cover on yourself, do you wish to cover your Spouse, children or any other dependants?',
            'additional_covers' => 'No of Dependants',
            'pricipal_insurer' => 'Previous Principal Insurer',
            'pricipal_day' => 'Previous Principal Insurer Day',
            'pricipal_month' => 'Previous Principal Insurer Month',
            'spouse_insurer' => 'Previous Spouse Insurer Insurer',
            'spouse_day' => 'Previous Spouse Insurer Day',
            'spouse_month' => 'Previous Spouse Insurer Month',
            'spouse_year' => 'Previous Spouse Insurer Year',
            'ever_cover_declined' => 'Have you or any of your dependants ever been declined, or had exclusions applied to them by a medical scheme?',
            'declined_particulars' => 'If yes, please provide particulars',
            'dependant_claimed_cover' => 'Have you or any of your dependants lodged a claim in the last one year under any other medical scheme?',
            'dep_claimed_particulars' => 'If yes, please provide particulars',
            'i_agree' => 'Agree to Terms and Conditions'
        ];

        return $indices;
    }

    /**
     * Create entity data array
     * @param $entity_data
     * @return array
     */
    public function createEntityDataArr($entity_data)
    {
        $entity_data_arr = [];

        $count = 0;
        foreach ($entity_data as $value) {
            $dep = $count + 1;

            $array = [
                'title_' . $dep => $value['title'],
                'proposer_surname_' . $dep => $value['proposer_surname'],
                'other_names_' . $dep => $value['other_names'],
                'dob_' . $dep => $value['date_of_birth'],
                'gender_' . $dep => $value['gender'],
                'age_range_bracket_' . $dep => $value['age_range_bracket'],
                'id_passport_no_' . $dep => $value['id/passport'],
                'nhif_' . $dep => $value['nhif'],
                'blood_type_' . $dep => $value['blood_type'],
                'nationality_' . $dep => $value['nationality'],
                'relation_to_proposer_' . $dep => $value['relation_to_spouse'],
                'occupation_' . $dep => $value['occupation']
            ];

            $entity_data_arr = array_merge($entity_data_arr, $array);

            $count++;
        }

        return $entity_data_arr;
    }
}
