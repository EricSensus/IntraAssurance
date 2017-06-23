<?php

namespace Jenga\MyProject\Travel\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Helpers\Help;
use Jenga\App\Project\Security\User;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Travel\Models\TravelModel;
use Jenga\MyProject\Travel\Views\TravelView;
use Jenga\MyProject\Users\Controllers\UsersController;

/**
 * Description of TravelController
 * @property-read TravelView $view
 * @property-read TravelModel $model;
 * @author developer
 */
class TravelController extends Controller
{

    /**
     * @var array
     */
    public $data;
    private $countries = [],
        $inputs = [],
        $companions = [],
        $entity,
        $insurer_id,
        $entity_id,
        $product_id,
        $medical,
        $quote,
        $product;

    /**
     * @var UsersController
     */
    private $userCtrl;

    /**
     * Set the necessary controllers
     * @node\uses 'Customers/CustomersController'
     * @node\uses 'Entities/EntitiesController'
     * @node\uses 'Quotes/QuotesController'
     */
    public function setEntities()
    {
        $this->customer = Elements::call('Customers/CustomersController');
        $this->entity = Elements::call('Entities/EntitiesController');
        $this->quote = Elements::call('Quotes/QuotesController');
    }

    /**
     * Deletes any previously created sessions i.e customer_id and quote_id
     */
    public function destroyPrevSessions()
    {
        Session::delete('customer_id');
        Session::delete('quote_id');
    }

    /**
     * Load data and display forms for each step
     * @node\uses 'Quotes/QuotesController'
     */
    public function index()
    {
        $this->loadData(Input::get('step'));
        $this->quote = Elements::call('Quotes/QuotesController');

//        if (!Session::has('user_id') && Input::get('step') != 1)
//            Redirect::withNotice(Session::get('step_feed'))->to('/travel/step/1');

        switch (Input::get('step')) {
            case 1:
                $this->setCountries();
                $this->view->stepOne($this->countries);
                break;

            case 2:
                $this->view->stepTwo();
                break;

            case 3:
                $this->setTravelCompanions();
                $this->view->stepThree($this->companions);
                break;

            case 4:
                $quote = $this->prepareQuoteData();
                $this->view->stepFour($quote);
                break;
        }
    }

    /**
     * Prepare the quote data and how it will be displayed
     * @node\uses 'Quotes/QuotesController->mvc@getQuoteById'
     * @return array
     */
    public function prepareQuoteData()
    {
        $quote = $this->quote->getQuoteById(Session::get('quote_id'));
        $quote_data = $this->quote->getQuotations(Session::get('quote_id'));
        return $quote_data;
        $product_info = json_decode($quote->product_info);
        $customer_info = json_decode($quote->customer_info);
        $amounts = json_decode($quote->amount);

        // get the rate
        $cover = $this->getCover($product_info->cover_plan);

        $quote = [
            'quote_id' => $quote->id,
            'name' => $customer_info->surname . ' ' . $customer_info->names,
            'cover_plan' => $cover,
            'days_of_travel' => $product_info->no_travel_days,
            'basic_premium' => $amounts->basic_premium,
            'training_levy' => $amounts->training_levy,
            'phcf' => $amounts->policy_levy,
            'stamp_duty' => $amounts->stamp_duty
        ];

        // check if there are any companions
        if ($companions_no = $product_info->no_of_travel_companions) {
            for ($i = 1; $i <= $companions_no; $i++) {
                $companion_data[] = [
                    'companion_name' => $product_info->{'companion_name' . $i},
                ];
            }
            $quote['companions'] = $companion_data;
        }

        return $quote;
    }

    /**
     * reset the navigation for each step
     */
    public function loadNav()
    {
        $this->set('step', Input::get('step'));
    }

    /**
     * Countries getter
     * @return array
     */
    public function getCountries()
    {
        $this->setCountries();
        return $this->countries;
    }

    /**
     * Countries setter
     */
    public function setCountries()
    {
        $countries = $this->model->table('countries')->all();
        if (count($countries)) {
            foreach ($countries as $key => $country) {
                $this->countries[$country->country_name] = $country->country_name;
            }
        }
    }

    /**
     * No of companions field setter
     */
    public function setTravelCompanions()
    {
        for ($i = 1; $i <= 8; $i++) {
            $this->companions[$i] = $i;
        }
    }

    /**
     * Save details for each step
     * @node\uses 'Users/UsersController'
     * @param $step
     */
    public function saveForm($step)
    {
        // check if sub id is set
        if (!Session::has('customer_id') && $step != 1 && !Input::has('request_type'))
            Redirect::to('/travel/step/1')->withNotice('Please fill in your personal details first!');

        $this->inputs = $this->getInputForStep($step);
        $this->userCtrl = Elements::call('Users/UsersController');
        switch ($step) {
            case 1:
                if (!$this->isAjax())
                    $this->destroyPrevSessions();
                $this->saveFormOne();
                break;

            case 2:
                $this->saveFormTwo();
                break;

            case 3:
                $this->inputs = Input::post();
                $this->saveFormThree();
                break;
        }
    }

    /**
     * Determine if request is ajax or not
     * @return bool
     */
    private function isAjax()
    {
        if (Input::post('request_type') == '__ajax')
            return true;
        return false;
    }

    /**
     * get the quote id for the currently generated quote
     */
    public function getQuote()
    {
        $this->view->disable();
        echo json_encode([
            'quote_id' => Help::encrypt(Session::get('quote_id'))
        ]);
    }

    /**
     * Save step one details
     * @node\uses 'Quotes/QuotesController->mvc@saveQuoteRemotely'
     * @node\uses 'Customers/CustomersController->mvc@saveCustomer'
     * @node\uses 'Medical/MedicalController->mvc@deleteEntityData'
     */
    public function saveFormOne()
    {
        $this->quote = Elements::call('Quotes/QuotesController');
        $customer = Elements::call('Customers/CustomersController');
        $this->product = Elements::call('Products/ProductsController');
        $this->product_id = $this->product->getProductByAlias('travel_insurance')->id;

        $internal = false;
        if ($this->isAjax())
            $internal = true;

        $customer->saveCustomer('travel', null, $internal);

        Elements::call('Medical/MedicalController')->deleteEntityData();

        // partially save the quote
        $quote_id = $this->quote->saveQuoteRemotely([
            'customer_id' => Session::get('customer_id'),
            'product_id' => $this->product_id
        ], json_encode($this->inputs), [], [], null);

        Session::set('quote_id', $quote_id);
        if ($this->isAjax()) {

            $this->view->disable();
            echo json_encode(['success' => true]);
            exit;
        } else {
            $notification = 'Saved Please proceed to step two';
            // get the verification notification if new customer registration
            if (Session::has('sent_confirmation'))
                $notification = Session::get('sent_confirmation');

            Redirect::withNotice($notification)->to('/travel/step/2');
        }
    }

    /**
     * Save step two details - travel
     */
    public function saveFormTwo()
    {
        Session::set('product_info', json_encode($this->inputs));

        $this->userCtrl->logInfo('Step 2: Quote Id: ' . Session::get('quote_id'));
        if ($this->isAjax()) {
            $this->view->disable();
            echo json_encode(['success' => true]);
            exit;
        } else {
            Redirect::to('/travel/step/3')->withNotice('Saved! Please proceed...');
        }
    }

    /**
     * Save step three details
     * @node\uses 'Quotes/QuotesController->mvc@getQuoteById'
     * @node\uses 'Entities/EntitiesController->mvc@getEntityIdByAlias'
     * @node\uses 'Products/ProductsController->mvc@getProductByAlias'
     */
    public function saveFormThree()
    {
        $this->quote = Elements::call('Quotes/QuotesController');
        $this->entity = Elements::call('Entities/EntitiesController');
        $this->entity_id = $this->entity->getEntityIdByAlias('person')->id;
        $this->product_id = Elements::call('Products/ProductsController')
            ->getProductByAlias('travel_insurance')->id;

        $product_info = json_decode(Session::get('product_info'), true);
        $product_info = array_merge($product_info, $this->inputs);

        // save all entities if there's any
        $companions = [];
        $entity_values = [];
        $companion_no = $this->inputs['no_of_travel_companions'];
        for ($i = 1; $i <= $companion_no; $i++) {
            $entity_values = [
                'name' => $this->inputs['companion_name' . $i],
                'occupation' => $this->inputs['companion_occupation' . $i],
                'date_of_birth' => $this->inputs['companion_dob' . $i],
                'relationship' => $this->inputs['companion_relationship' . $i],
                'passport' => $this->inputs['companion_passport' . $i],
                'plan' => $this->inputs['companion_plan' . $i],
                'travel_days' => $this->inputs['companion_no_of_days' . $i]
            ];

            $this->entity->saveEntityDataRemotely(
                Session::get('customer_id'), $this->entity_id, json_encode($entity_values), $this->product_id, null);
        }

        // get customer info from customer quotes;
        $customer_info = $this->quote->getQuoteById(Session::get('quote_id'))->customer_info;
        $customer_info = json_decode($customer_info, true);

        $ids = [
            'customer_id' => Session::get('customer_id'),
            'entity_id' => $this->entity_id
        ];

        $amounts = $this->calculateAmounts($this->inputs['cover_plan'], $this->inputs['no_travel_days']);

        // save quote details
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
            exit;
        } else {
            Redirect::to('/travel/step/4')->withNotice('Saved! Please proceed...');
        }
    }

    /**
     * Determine the rate based on selected plan and days
     * @param $plan
     * @param $days
     * @return mixed
     */
    public function getRate($plan, $days)
    {
        $plan_id = $this->model->table('travel_pricing')->find([
            'plan' => $plan
        ])->id;

        if (($plan != "Haj and Umrah Plan Basic") && ($plan != "Haj and Umrah Plan Plus") && ($plan != "Haj and Umrah Plan Extra")) {
            $rate = $this->model->table('travel_pricing')
                ->join("travel_plan_details tpd", TABLE_PREFIX . "travel_pricing.id = tpd.plan_id", "LEFT")
                ->where('days', '=', $days)
                ->where('plan_id', '=', $plan_id)
                ->get();
            $premium_rate = $rate[0]->premium;
        } else {
            $rates = $this->model->table('travel_pricing')
                ->join("travel_plan_details tpd", TABLE_PREFIX . "travel_pricing.id = tpd.plan_id")
                ->where('plan_id', '=', $plan_id)
                ->get();

            if (count($rates)) {
                foreach ($rates as $rate) {
                    $range = $rate->days;
                    $range = explode("-", $range);

                    if (($days >= $range[0]) && ($days <= $range[1])) {
                        $premium_rate = $rate->premium;
                        break;
                    } else {
                        continue;
                    }
                }
            }
        }

        return $premium_rate;
    }

    /**
     * Get the correct name for cover plan
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
            return 'Haj and Umrah Plan Basic';
        else if ($plan == 'haj_and_umra_plus_plan')
            return 'Haj and Umrah Plan Plus';
        else if ($plan == 'haj_and_umra_extra_plan')
            return 'Haj and Umrah Plan Extra';
    }

    /**
     * Get all the companions
     * @param $other_covers
     * @return array
     */
    public function getDependants($other_covers)
    {
        $companions = json_decode($other_covers->details);
        $comps = [];
        foreach ($companions as $key => $companion) {
            $comps[$key] = $companion;
        }
        return $comps;
    }

    /**
     * Calculate amounts for travel quote
     * @param $cover_plan
     * @param $travel_days
     * @return array
     * @node\uses 'Medical/MedicalController->mvc@determineRate'
     * @node\uses 'Insurers/InsurersController->mvc@getInsurerByFinder'
     * @node\uses 'Entities/EntitiesController->mvc@getEntityDataByCustomerAndEntityId'
     */
    public function calculateAmounts($cover_plan, $travel_days)
    {
        $this->setEntities();
        $this->medical = Elements::call('Medical/MedicalController');

        // get the rate
        $cover = $this->getCover($cover_plan);
        $premium_rate = $this->getRate($cover, $travel_days);

        $levy = $this->medical->determineRate($premium_rate, 'Training Levy', 'Travel');
        $phcf = $this->medical->determineRate($premium_rate, 'P.H.C.F Fund', 'Travel');
        $stamp_duty = $this->medical->determineRate($premium_rate, 'Stamp Duty', 'Travel');

        $this->insurer_id = Elements::call('Insurers/InsurersController')
            ->getInsurerByFinder(['email_address' => 'info@jubilee.co.ke'])
            ->id;

        $bp = $premium_rate * 90;
        $amounts = [
            'insurer_id' => $this->insurer_id,
            'basic_premium' => $bp,
            'training_levy' => $levy,
            'policy_levy' => $phcf,
            'stamp_duty' => $stamp_duty
        ];

        $companions = $this->entity->getEntityDataByCustomerAndEntityId(Session::get('customer_id'), $this->entity_id, $this->product_id);
        $companions_total = 0;
        if (count($companions)) {
            $entity_data_ids = [];
            $others_amounts = [];

            $i = 1;
            foreach ($companions as $companion) {
                // get the entity data ids
                $entity_data_ids[] = $companion->id;

                // other cover amounts
                $entity_item = json_decode($companion->entity_values, true);
                $companion_name = $entity_item['name'];
                $others_amounts[$companion_name] = [
                    'Basic Premium' => $bp
                ];

                $companions_total += $bp;
                $i++;
            }

            $amounts['other_covers'] = $others_amounts;
        }

        $amounts['total'] = $companions_total + $bp + $levy + $phcf + $stamp_duty;

        return [
            'amounts' => $amounts,
            'entity_data_ids' => $entity_data_ids
        ];
    }

    /**
     * Get the schematic for each step and join them into one big schematic array
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
     * Load the necessary data for each step
     * @param $step
     * @node\uses 'Users/UsersController'
     */
    public function loadData($step)
    {
        $this->setCountries();
        $this->setTravelCompanions();

        $this->data = new \stdClass();
        $this->data->step = $step;
        $this->data->countries = $this->countries;
        $this->data->no_travel_companions = $this->companions;
        $this->userCtrl = Elements::call('Users/UsersController');
    }

    /**
     * Show/display the travel quote
     * @node\uses 'Quotes/QuotesController'
     */
    public function showQuote()
    {
        $this->quote = Elements::call('Quotes/QuotesController');
        $quote = Elements::call('Quotes/QuotesController')->getQuotations(Session::get('quote_id'));
        $this->view->stepFour($quote, true);
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
        foreach ($schematic['controls'] as $schema) {
            $my_array[] = $schema[1];
        }
        return array_only(Input::post(), $my_array);
    }

}
