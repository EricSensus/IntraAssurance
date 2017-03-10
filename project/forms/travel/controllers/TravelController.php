<?php
namespace Jenga\MyProject\Travel\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Elements;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TravelController
 *
 * @author developer
 */
class TravelController extends Controller
{
    private $countries = [],
        $inputs = [],
        $companions = [],
        $customer,
        $entity,
        $insurer_id,
        $entity_id,
        $product_id,
        $medical;

    public function __construct($method = '', $params = '')
    {
        parent::__construct($method, $params);
        $this->customer = Elements::call('Customers/CustomersController');
        $this->entity = Elements::call('Entities/EntitiesController');
        $this->quote = Elements::call('Quotes/QuotesController');
        $this->product = Elements::call('Products/ProductsController');
        $this->medical = Elements::call('Medical/MedicalController');

        // set ids
        $this->entity_id = $this->entity->getEntityIdByAlias('person')->id;
        $this->product_id = $this->product->getProductByAlias('medical_insurance')->id;
        $this->insurer_id = Elements::call('Insurers/InsurersController')
            ->getInsurerByFinder(['email_address' => 'info@jubilee.co.ke'])
            ->id;
    }

    public function index()
    {
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
                $quote = $this->quote->getQuoteById(Session::get('quote_id'));
                $product_info = json_decode($quote->product_info);
                $customer_info = json_decode($quote->product_info);
                $amounts = json_decode($quote->amount);

                // get the rate
                $cover = $this->getCover($product_info->cover_plan);

                $quote = [
                    'name' => $customer_info->surname . ' ' . $customer_info->names,
                    'cover_plan' => $cover,
                    'days_of_travel' => $product_info->no_travel_days,
                    'basic_premium' => $amounts->basic_premium,
                    'training_levy' => $amounts->training_levy,
                    'phcf' => $amounts->phcf,
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

                $this->view->stepFour($quote);
                break;
        }
    }

    public function loadNav()
    {
        $this->set('step', Input::get('step'));
    }

    public function getCountries()
    {
        $this->setCountries();
        return $this->countries;
    }

    public function setCountries()
    {
        $countries = $this->model->table('countries')->all();
        if (count($countries)) {
            foreach ($countries as $key => $country) {
                $this->countries[$country->country_name] = $country->country_name;
            }
        }
    }

    public function setTravelCompanions()
    {
        for ($i = 1; $i <= 8; $i++) {
            $this->companions[$i] = $i;
        }
    }

    public function saveForm($step)
    {
        $this->inputs = Input::post();

        // check if sub id is set
        if (!Session::has('customer_id') && $step != 'form_1')
            Redirect::to('/travel/step/1')->withNotice('Please fill in your personal details first!');

        switch ($step) {
            case 1:
                $this->saveFormOne();
                break;

            case 2:
                $this->saveFormTwo();
                break;

            case 3:
                $this->saveFormThree();
                break;
        }
    }

    public function saveFormOne()
    {
        $input = $this->inputs;
        $this->customer->saveCustomer();

        // partially save the quote
        $quote_id = $this->quote->saveQuoteRemotely([
            'customer_id' => Session::get('customer_id'),
            'product_id' => $this->product_id
        ], json_encode($this->inputs), [], [], null);

        Session::set('quote_id', $quote_id);
        Redirect::to('/travel/step/2')->withNotice('Saved! Please Proceed...');
    }

    public function saveFormTwo()
    {
        Session::set('product_info', json_encode($this->inputs));
        Redirect::to('/travel/step/3')->withNotice('Saved! Please proceed...');
    }

    public function saveFormThree()
    {
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
                Session::get('customer_id'),
                $this->entity_id,
                json_encode($entity_values));
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
        $this->quote->saveQuoteRemotely(
            $ids,
            json_encode($customer_info),
            $product_info,
            $amounts['amounts'],
            $amounts['entity_data_ids'],
            Session::get('quote_id'));

        Redirect::to('/travel/step/4')->withNotice('Saved! Please proceed...');
    }


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

    public function getCover($plan)
    {
        if ($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if ($plan == 'africa_basic_plan')
            return 'Africa Basic Plan';
        else if ($plan == 'europe_plus_plan')
            return 'Europe Plus Plan';
        else if ($plan == 'world_wide_basic_plan')
            return 'World Wide Basic Plan';
        else if ($plan == 'world_wide_plus_plan')
            return 'World Wide Plus Plan';
        else if ($plan == 'world_wide_extra')
            return 'World Wide Extra';
        else if ($plan == 'haj_and_umra_basic_plan')
            return 'Haj and Umra Basic Plan';
        else if ($plan == 'haj_and_umra_plus_plan')
            return 'Haj and Umra Plus Plan';
        else if ($plan == 'haj_and_umra_extra_plan')
            return 'Haj and Umra Extra Plan';
    }

    public function getDependants($other_covers)
    {
        $companions = json_decode($other_covers->details);
        $comps = [];
        foreach ($companions as $key => $companion) {
            $comps[$key] = $companion;
        }
        return $comps;
    }

    public function calculateAmounts($cover_plan, $travel_days)
    {
        // get the rate
        $cover = $this->getCover($cover_plan);
        $premium_rate = $this->getRate($cover, $travel_days);

        $levy = $this->medical->determineRate($premium_rate, 'Training Levy', 'Travel');
        $phcf = $this->medical->determineRate($premium_rate, 'P.H.C.F Fund', 'Travel');
        $stamp_duty = $this->medical->determineRate($premium_rate, 'Stamp Duty', 'Travel');

        $amounts = [
            'insurer_id' => $this->insurer_id,
            'basic_premium' => $premium_rate * 90,
            'training_levy' => $levy,
            'phcf' => $phcf,
            'stamp_duty' => $stamp_duty
        ];

        $companions = $this->entity->getEntityDataByCustomerAndEntityId(Session::get('customer_id'), $this->entity_id);
        if (count($companions)) {
            $entity_data_ids = [];
            foreach ($companions as $companion) {
                // get the entity data ids
                $entity_data_ids[] = $companion->id;
            }
        }

        return [
            'amounts' => $amounts,
            'entity_data_ids' => $entity_data_ids
        ];
    }
}
