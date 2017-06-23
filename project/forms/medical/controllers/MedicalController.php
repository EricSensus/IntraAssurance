<?php

namespace Jenga\MyProject\Medical\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Helpers\Help;
use Jenga\App\Project\Security\User;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Medical\Lib\Medical;
use Jenga\MyProject\Medical\Models\MedicalModel;
use Jenga\MyProject\Medical\Views\MedicalView;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Users\Controllers\UsersController;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MedicalControllers
 * @property-read MedicalModel $model
 * @property-read MedicalView $view
 * @author developer
 */
class MedicalController extends Controller
{
    /**
     * @var array
     */

    private $inputs = [],
        $countries = [],
        $cities = [],
        $customer,
        $entity,
        $product,
        $entity_id,
        $product_id,
        $insurer_id,
        $data;

    /**
     * @var QuotesController
     */
    private $quote;

    /**
     * @var UsersController
     */
    private $userCtrl;

    /**
     * Set Entities to be used
     * @node\uses 'Customers/CustomersController'
     * @node\uses 'Entities/EntitiesController'
     * @node\uses 'Quotes/QuotesController'
     * @node\uses 'Products/ProductsController'
     */
    public function setEntities()
    {
        $this->customer = Elements::call('Customers/CustomersController');
        $this->entity = Elements::call('Entities/EntitiesController');
        $this->entity_id = $this->entity->getEntityIdByAlias('person')->id;

        $this->quotes = Elements::call('Quotes/QuotesController');
        $this->product_id = Elements::call('Products/ProductsController')
            ->getProductByAlias('medical_insurance')
            ->id;
    }

    /**
     * Deletes any previously created sessions
     */
    public function destroyPrevSessions()
    {
        Session::delete('customer_id');
        Session::delete('quote_id');
        Session::delete('product_info');
        Session::delete('no_of_dependants');
    }

    /**
     * Load the respective form for each step
     * @node\uses 'Quotes/QuotesController'
     */
    public function index()
    {
        $this->quote = Elements::call('Quotes/QuotesController');
        Session::set('linked_agent', Input::post('agent'));

//        if (!Session::has('user_id') && Input::get('step') != 1)
//            Redirect::withNotice(Session::get('step_feed'))->to('/medical/step/1');

        $this->loadData(Input::get('step'));
//        dump(Help::encrypt(76));exit;
        switch (Input::get('step')) {
            case 1:
                $this->view->stepOne($this->countries, $this->cities);
                break;

            case 2:
                $this->view->stepTwo();
                break;

            case 'dependants':
                $this->view->stepDependants($this->countries, Session::get('no_of_dependants'));
                break;

            case 3:
                $this->view->stepThree();
                break;

            case 4:
                // get the quote
                $quote = $this->prepareQuoteData();
                $this->view->stepFour($quote);
                break;
        }
    }

    /**
     * Load navigation for each step
     */
    public function loadNav()
    {
        $this->set('step', Input::get('step'));
    }

    /**
     * Prepares all the data to be displayed in the quote
     * @node\uses 'Entities/EntitiesController->mvc@getEntityDataByFinder'
     * @node\uses 'Quotes/QuotesController->mvc@getQuoteById'
     * @return array
     */
    public function prepareQuoteData()
    {
        $this->setEntities();
        $quote_data = $this->quote->getQuotations(Session::get('quote_id'));
        return $quote_data;
        $customer = json_decode($quote_data->customer_info, true);
        $plan_data = json_decode($quote_data->product_info, true);
        $amounts = json_decode($quote_data->amount, true);
        $entity_data_ids = json_decode($quote_data->customer_entity_data_id, true);
        $dep_names = [];
        if (count($entity_data_ids)) {
            foreach ($entity_data_ids as $data_id) {
                $entity_values = json_decode($this->entity->getEntityDataByfinder($data_id)->entity_values);
                $dep_name = $entity_values->proposer_surname . ' ' . $entity_values->other_names;
                $dep_names[] = $dep_name;
            }
        }

        $quote = [
            'quote_id' => $quote_data->id,
            'name' => $customer['surname'] . ' ' . $customer['names'],
            'core_premium' => $amounts['basic_premium'],
            'core_optional_benefits' => $amounts['core_optional_benefits'],
            'selected_plan' => $plan_data['core_plans'],
            'levy' => $amounts['training_levy'],
            'phcf' => $amounts['policy_levy'],
            'stamp_duty' => $amounts['stamp_duty'],
            'subtotal' => $amounts['subtotal'],
            'grand_total' => $amounts['total']
        ];

        if (isset($amounts['other_covers'])) {
            if (count($amounts['other_covers'])) {
                $i = 0;
                foreach ($amounts['other_covers'] as $dependant) {
                    $deps[] = [
                        'dep_premium' => $dependant['basic_premium'],
                        'dep_benefits' => $dependant['core_optional_benefits'],
                        'dep_name' => $dep_names[$i]
                    ];
                    $i++;
                }
            }
            $quote['dependants'] = $deps;
        }
        return $quote;
    }

    /**
     * Set countries to be displayed on form select lists
     */
    public function setCountries()
    {
        $countries = $this->model->table('countries')->all();
        if (count($countries)) {
            foreach ($countries as $country) {
                $this->countries[$country->country_name] = $country->country_name;
            }
        }
    }

    /**
     * Set cities to be displayed on form select lists
     */
    public function setCities()
    {
        $cities = ['Baragoi', 'Bungoma', 'Busia', 'Busia', 'Butere', 'Dadaab', 'Diani Beach', 'Eldoret', 'Embu', 'Garissa', 'Gedi', 'Hola', 'Homa Bay', 'Isiolo', 'Kajiado', 'Kakamega', 'Kakuma', 'Kapenguria', 'Kericho', 'Kiambu', 'Kilifi', 'Kisii town', 'Kisumu', 'Kitale', 'Lamu', 'Langata', 'Lodwar', 'Lokichoggio', 'Londiani', 'Loyangalani', 'Machakos', 'Malindi', 'Mandera', 'Marala', 'Marsabit', 'Meru', 'Mombasa', 'Moyale', 'Mumias', 'Muranga', 'Nairobi', 'Naivasha', 'Nakuru', 'Namanga', 'Nanyuki', 'Naro Moru', 'Narok', 'Nyahururu', 'Nyeri', 'Ruiru', 'Shimoni', 'Takaungu', 'Thika', 'Vihiga', 'Voi', 'Wajir', 'Watamu', 'Webuye', 'Wundanyi'];
        foreach ($cities as $city) {
            $this->cities[$city] = $city;
        }
    }

    /**
     * Save form details for each step
     * @node\uses 'Users/UsersController'
     * @param $step
     */
    public function saveForm($step)
    {
//        if (!Session::has('customer_id') && $step != 1)
//            Redirect::to('/medical/step/1');

        $this->userCtrl = Elements::call('Users/UsersController');
        switch ($step) {
            case 1:
                if (!$this->isAjax())
                    $this->destroyPrevSessions();
                $this->inputs = $this->getInputForStep('1');
                $this->saveStepOne();
                break;

            case 2:
                $this->inputs = $this->getInputForStep('2');
                $this->saveStepTwo();
                break;

            case 'dependants':
                $this->inputs = Input::post();
                $this->saveDependants();
                break;

            case 3:
                $this->inputs = $this->getInputForStep('3');
                $this->saveStepThree();
                break;
        }
    }

    /**
     * Save step one details
     * @node\uses 'Customers/CustomersController->mvc@saveCustomer'
     * @node\uses 'Quotes/QuotesController->mvc@saveQuoteRemotely'
     * @node\uses 'Products/ProductsController->mvc@getProductByAlias'
     */
    public function saveStepOne()
    {
        $this->quote = Elements::call('Quotes/QuotesController');
        $this->product = Elements::call('Products/ProductsController');
        $this->product_id = $this->product->getProductByAlias('medical_insurance')->id;
        $this->customer = Elements::call('Customers/CustomersController');

        $internal = false;
        if ($this->isAjax())
            $internal = true;

        $saved = $this->customer->saveCustomer('medical', null, $internal);
        if ($saved) {
            $this->deleteEntityData();

            // partially save the quote
            $quote_id = $this->quote->saveQuoteRemotely([
                'customer_id' => Session::get('customer_id'),
                'product_id' => $this->product_id
            ], json_encode($this->inputs), [], [], null);

//            $this->userCtrl->logInfo('Setting the quote id session');
            Session::set('quote_id', $quote_id);

//            $this->userCtrl->logInfo('Session Quote Id: '.$quote_id);

            if ($this->isAjax()) {
                $this->view->disable();
                echo json_encode(['success' => true]);
                exit;
            } else {
                $notification = 'Saved Please proceed to step two';
                // get the verification notification if new customer registration
                if (Session::has('sent_confirmation')) {
                    $notification = Session::get('sent_confirmation');
                }

                Redirect::withNotice($notification, 'success')->to('/medical/step/2');
            }
        }
    }

    /**
     * Save step two details
     */
    public function saveStepTwo()
    {

        Session::set('no_of_dependants', 0);

        // save product info into the customer quotes table
        Session::set('product_info', json_encode($this->inputs));

        if (isset($this->inputs['additional_covers']) && $this->inputs['additional_covers'] > 0) {
            Session::set('no_of_dependants', $this->inputs['additional_covers']);

            if ($this->isAjax()) {
                $this->view->disable();
                echo json_encode(['success' => true]);
                exit;
            } else {
                Redirect::to('/medical/step/dependants')->withNotice('Saved! Please Add Dependant cover details');
            }
        }

        if ($this->isAjax()) {
            $this->view->disable();
            echo json_encode(['success' => true]);
            exit;
        } else {
            Redirect::to('/medical/step/3')->withNotice('Saved! Please proceed...');
        }
    }

    /**
     * Save dependant details
     */
    public function saveDependants()
    {
        $this->setEntities();
        $dependants_no = Session::get('no_of_dependants');

        $entity_data_ids = [];
        $dependant_data = [];
        if ($dependants_no) {
            for ($dep = 1; $dep <= $dependants_no; $dep++) {
                $dependant_data = [
                    'title' => $this->inputs['title_' . $dep],
                    'proposer_surname' => $this->inputs['proposer_surname_' . $dep],
                    'other_names' => $this->inputs['other_names_' . $dep],
                    'date_of_birth' => $this->inputs['dob_' . $dep],
                    'gender' => $this->inputs['gender_' . $dep],
                    'age_range_bracket' => $this->inputs['age_range_bracket_' . $dep],
                    'id/passport' => $this->inputs['id_passport_no_' . $dep],
                    'nhif' => $this->inputs['nhif_' . $dep],
                    'blood_type' => $this->inputs['blood_type_' . $dep],
                    'nationality' => $this->inputs['nationality_' . $dep],
                    'relation_to_spouse' => $this->inputs['relation_to_proposer_' . $dep],
                    'occupation' => $this->inputs['occupation_' . $dep]
                ];

                $this->entity->saveEntityDataRemotely(
                    Session::get('customer_id'), $this->entity_id, json_encode($dependant_data), $this->product_id, null);
            }
        }

        if ($this->isAjax()) {
            $this->view->disable();
            echo json_encode(['success' => true]);
            exit;
        } else {
            Redirect::to('/medical/step/3')->withNotice('Saved! Please proceed...');
        }
    }

    /**
     * Delete the related Entity Data by Quote Id
     * @node\uses 'Quotes/QuotesController->mvc@getQuoteById'
     */
    public function deleteEntityData()
    {
        if (Session::has('quote_id')) {
            $quote_id = Session::get('quote_id');

            // get entity data from customer quotes
            $quote_ctrl = Elements::call('Quotes/QuotesController');

            // get the quote by id
            $quote = $quote_ctrl->getQuoteById($quote_id);

            // get entity data ids
            $entity_data_ids = json_decode($quote->customer_entity_data_id, true);

            // delete entity data by the ids
            if (count($entity_data_ids)) {
                foreach ($entity_data_ids as $id) {
                    $this->model->table('customer_entity_data')->where('id', $id)->delete();
                }
            }

            // delete quote
            $quote_ctrl->model->where('id', $quote_id)->delete();

            // delete quote id session
            Session::delete('quote_id');
        }
    }

    /**
     * Save step three details
     * @node\uses 'Customers/CustomersController->mvc@saveCustomer'
     * @node\uses 'Quotes/QuotesController->mvc@getQuoteById'
     * @node\uses 'Quotes/QuotesController->mvc@saveQuoteRemotely'
     * @node\uses 'Products/ProductsController->mvc@getProductByAlias'
     */
    public function saveStepThree()
    {
        $this->quote = Elements::call('Quotes/QuotesController');
        $this->product = Elements::call('Products/ProductsController');
        $this->product_id = $this->product->getProductByAlias('medical_insurance')->id;
        $this->userCtrl = Elements::call('Users/UsersController');

        $this->userCtrl->logInfo('Get Customer Id: ' . Session::get('customer_id') . ' and Product Id: ' . $this->product_id);
        $ids = [
            'customer_id' => Session::get('customer_id'),
            'product_id' => $this->product_id
        ];

        $product_info = json_decode(Session::get('product_info'), true);
        $product_info = array_merge($product_info, $this->inputs);

        $this->userCtrl->logInfo('Get Quote Id: ' . Session::get('quote_id'));

        // get customer info from customer quotes;
        $customer_info = $this->quote->getQuoteById(Session::get('quote_id'))->customer_info;
        $customer_info = json_decode($customer_info, true);

        $amounts = $this->calculateAmounts($customer_info, $product_info);
//        dump($amounts);exit;

        // update quote details
        $this->quote->source = ($this->isAjax()) ? 'Internal' : 'External';
        $quote_id = $this->quote->saveQuoteRemotely(
            $ids, json_encode($customer_info), $product_info, /*$amounts['amounts']*/
            null, $amounts['entity_data_ids'], Session::get('quote_id'));

        if ($this->isAjax()) {
            $this->view->disable();
            echo json_encode([
                'success' => true,
                'quote_id' => $quote_id
            ]);
        } else {
            Redirect::to('/medical/step/4')->withNotice('Saved! Please proceed...');
        }
    }

    /**
     * Calculate Quote Amounts for medical
     * @param array $customer_info
     * @param array $product_info
     * @return array
     * @node\uses 'Insurers/InsurersController->mvc@getInsurerByFinder'
     * @node\uses 'Entities/EntitiesController->mvc@getEntityIdByAlias'
     * @node\uses 'Entities/EntitiesController->mvc@getEntityDataByCustomerAndEntityId'
     */
    public function calculateAmounts($customer_info = [], $product_info = [])
    {
        $this->insurer_id = Elements::call('Insurers/InsurersController')
            ->getInsurerByFinder(['email_address' => 'info@jubilee.co.ke'])
            ->id;

        // get the entity id
        $this->entity = Elements::call('Entities/EntitiesController');
        $this->entity_id = $this->entity->getEntityIdByAlias('person')->id;

        $age_bracket = $this->determineAgeBracket($customer_info['age_range_bracket'], $customer_info['gender']);
//        dd($age_bracket);
        $selected_plan = Medical::determinePlan($product_info['core_plans']);

        // get the optional benefits
        $core_optional_benefits = Medical::getTheOptionalBenefits($product_info, 'core');

        // get core premium
        $core_premium = $this->model->table('medical_pricing')->find(['agerange_benefits' => $age_bracket])->$selected_plan;

        // get core optional benefits total
        $optional_total = $this->getOptionalBenefitsTotal($selected_plan, $core_optional_benefits);

        $amounts = [
            'insurer_id' => $this->insurer_id,
            'basic_premium' => $core_premium,
            'core_optional_benefits' => $optional_total
        ];

        // add other covers amounts
        if ($product_info['have_dependants'] == 'yes') {
            if ($product_info['additional_covers']) {

                $dependants = $this->entity->getEntityDataByCustomerAndEntityId(Session::get('customer_id'), $this->entity_id, $this->product_id);
//                dump(count($dependants));
                if (count($dependants)) {
                    $i = 1;
                    $dt_total = 0;
                    $entity_data_ids = [];
                    foreach ($dependants as $dependant) {
                        $entity_values = json_decode($dependant->entity_values);
                        $dep_age_bracket = $this->determineAgeBracket($entity_values->age_range_bracket, $entity_values->gender);
                        $dep_premium = $this->model->table('medical_pricing')->find(['agerange_benefits' => $dep_age_bracket])->$selected_plan;

                        $subtotal = $dep_premium + $optional_total;

                        $dependant_name = $entity_values->{'proposer_surname'} . ' ' . $entity_values->{'other_names'};
                        $deps[$dependant_name] = [
                            'basic_premium' => $dep_premium,
                            'core_optional_benefits' => $optional_total
                        ];
                        $dt_total += $subtotal;


                        // get the entity data ids
                        $entity_data_ids[] = $dependant->id;
                        $i++;
                    }
                    $amounts['other_covers'] = $deps;
                }
            }
        }

        $premium_rate = $core_premium + $optional_total + $dt_total;
        $amounts['subtotal'] = $premium_rate;
        $amounts['training_levy'] = $this->determineRate($premium_rate, 'Medical Levy', 'Medical');
        $amounts['policy_levy'] = $this->determineRate($premium_rate, 'P.H.C.F Fund', 'Medical');
        $amounts['stamp_duty'] = $this->determineRate($premium_rate, 'Stamp Duty', 'Medical');
        $amounts['total'] = $premium_rate + $amounts['training_levy'] + $amounts['policy_levy'] + $amounts['stamp_duty'];

        return [
            'amounts' => $amounts,
            'entity_data_ids' => $entity_data_ids
        ];
    }

    /**
     * Determines the optional benefits for medical
     * @param $selected_plan
     * @param $core_optional_benefits
     * @return int
     */
    public function getOptionalBenefitsTotal($selected_plan, $core_optional_benefits)
    {
        $optional_total = 0;
        // get core optional benefits
        $core_optional_benefits = $this->model->table('medical_pricing')
            ->where('agerange_benefits', 'IN', $core_optional_benefits)
            ->get();

        if (count($core_optional_benefits)) {
            foreach ($core_optional_benefits as $benefit) {
                $benefit_amount = $benefit->$selected_plan;
                $optional_total += $benefit_amount;
            }
        }
        return $optional_total;
    }

    /**
     * Loads other covers i.e. dependants
     * @param $sbid
     * @return mixed
     */
    public function getOtherCovers($sbid)
    {
        $dependants = $this->model->table('other_covers')->find(['sbid' => $sbid]);
        return json_decode($dependants->details);
    }

    /**
     * Determine rate to be used for calculation
     * @param $value
     * @param $ratename
     * @param $category
     * @param string $ratetype
     * @return float|int
     */
    public function determineRate($value, $ratename, $category, $ratetype = '')
    {
        if ($ratetype == '') {
            $rate = $this->model->table('rates')->find([
                'rate_category' => $category,
                'rate_name' => $ratename
            ]);
        } else {
            $rate = $this->model->table('rates')->find([
                'rate_category' => $category,
                'rate_name' => $ratename,
                'rate_type' => $ratetype
            ]);
        }
        if ($rate->rate_type == 'Percentage')
            $computed_value = (($value * $rate->rate_value) / 100);
        else if ($rate->rate_type == 'Fixed')
            $computed_value = $rate->rate_value;

        return $computed_value;
    }

    /**
     * Get the schematic for each form step
     * @return array
     */
    public function getSchematic()
    {
        $forms = [1, 2, 3];
        $schematic = [];
        $this->setEntities();
        $count = 0;
        foreach ($forms as $form) {
            $this->loadData(++$count);
            $schematic[$form] = $this->view->getSchematic($this->data, false);
        }
        return $schematic;
    }

    /**
     * Load the necessary data to be used for each step
     * @param $step
     */
    public function loadData($step)
    {
        $this->setCountries();
        $this->setCities();

        $this->data = new \stdClass();
        $this->data->step = $step;
        $this->data->countries = $this->countries;
        $this->data->cities = $this->cities;
    }

    /**
     * Determine if request is ajax or not
     * @return bool
     */
    private function isAjax()
    {
        if (Input::has('request_type')) {
            if (Input::post('request_type') == '__ajax')
                return true;
        }
        return false;
    }

    /**
     * Load dependants based on the no
     * @param $deps
     */
    public function loadDependants($deps)
    {
        $this->setCountries();

        Session::set('no_of_dependants', $deps);
        $dep_schema = $this->view->stepDependants($this->countries, $deps, true);

        $this->view->disable();
        echo json_encode([
            'success' => true,
            'form' => $dep_schema
        ]);
    }

    /**
     * Prepare quote data and display the quote(internal)
     */
    public function getQuote()
    {
        $this->view->disable();
        $quote_schematic = $this->view->stepFour($this->prepareQuoteData(), true);
        echo json_encode($quote_schematic);
    }

    /**
     * Prepare quote data and display the quote (external/frontend)
     * @node\uses 'Quotes/QuotesController'
     */
    public function showQuote()
    {
        $this->quote = Elements::call('Quotes/QuotesController');
        $quote_data = Elements::call('Quotes/QuotesController')->getQuotations(Session::get('quote_id'));
//        $quote_data = $this->prepareQuoteData();
        $this->view->stepFour($quote_data, true);
    }

    /**
     * Return only specified input for a step
     * @param $step
     * @return array
     */
    public function getInputForStep($step)
    {
        $this->setEntities();
        $this->loadData($step);

        $schematic = $this->view->getSchematic($this->data, true);
        $my_array = [];
        foreach ($schematic as $schema) {
            $my_array[] = $schema[1];
        }
        return array_only(Input::post(), $my_array);
    }

    /**
     * Determine Age bracket
     * @param $agebracket
     * @param $gender
     * @return string
     */
    public function determineAgeBracket($agebracket, $gender)
    {
        if ($agebracket == '1-18') {
            $return = 'Ac';
        } else if ($agebracket == '19-30') {
            $return = 'A1';
        } else if ($agebracket == '31-40') {
            $return = 'A2';
        } else if ($agebracket == '41-50') {
            $return = 'A3';
        } else if ($agebracket == '51-59') {
            $return = 'A4';
        } else if ($agebracket == '60-65') {
            $return = 'A5';
        }
        return $return;
    }

}
