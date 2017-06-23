<?php
namespace Jenga\MyProject\Medical\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Medical\Lib\Medical;
use Symfony\Component\HttpKernel\HttpCache\Ssi;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MedicalControllers
 *
 * @author developer
 */
class MedicalController extends Controller{
    private $inputs = [],
            $countries = [],
            $cities = [],
            $customer,
            $entity,
            $product,
            $quote,
            $entity_id,
            $product_id,
            $insurer_id;

    public function __construct($method = '', $params = '')
    {
        parent::__construct($method, $params);
        $this->customer = Elements::call('Customers/CustomersController');
        $this->entity = Elements::call('Entities/EntitiesController');
        $this->product = Elements::call('Products/ProductsController');
        $this->quote = Elements::call('Quotes/QuotesController');

        // get the entity id
        $this->entity_id = $this->entity->getEntityIdByAlias('person')->id;
        $this->product_id = $this->product->getProductByAlias('medical_insurance')->id;
        $this->insurer_id = Elements::call('Insurers/InsurersController')
            ->getInsurerByFinder(['email_address' => 'info@jubilee.co.ke'])
            ->id;
    }

    public function index(){
        $this->setCountries();
        $this->setCities();

        switch(Input::get('step')){
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
                $quote_data = $this->quote->getQuoteById(Session::get('quote_id'));
                $customer = json_decode($quote_data->customer_info, true);
                $plan_data = json_decode($quote_data->product_info, true);
                $amounts = json_decode($quote_data->amount, true);
                $entity_data_ids = json_decode($quote_data->customer_entity_data_id, true);
                $dep_names = [];
                if (count($entity_data_ids)){
                    foreach ($entity_data_ids as $data_id){
                        $entity_values = json_decode($this->entity->getEntityDataByfinder($data_id)->entity_values);
                        $dep_name = $entity_values->proposer_surname.' '.$entity_values->other_names;
                        $dep_names[] = $dep_name;
                    }
                }

                $quote = [
                    'name' => $customer['surname'].' '.$customer['names'],
                    'core_premium' => $amounts['core_premium'],
                    'core_optional_benefits' => $amounts['core_optional_benefits'],
                    'selected_plan' => $plan_data['core_plans'],
                    'levy' => $amounts['levy'],
                    'phcf' => $amounts['phcf'],
                    'stamp_duty' => $amounts['stamp_duty'],
                    'subtotal' => $amounts['subtotal'],
                    'grand_total' => $amounts['grand_total']
                ];

                if (isset($amounts['dependants'])){
                    if (count($amounts['dependants'])){
                        $i = 0;
                        foreach ($amounts['dependants'] as $dependant){
                            $deps[] = [
                                'dep_premium' => $dependant['dep_premium'],
                                'dep_benefits' => $dependant['dep_benefits'],
                                'dep_name' => $dep_names[$i]
                            ];
                            $i++;
                        }
                    }
                    $quote['dependants'] = $deps;
                }

                $this->view->stepFour($quote);
                break;
        }
    }
    
    public function loadNav(){
        $this->set('step', Input::get('step'));
    }
    
    public function setCountries(){
        $countries = $this->model->table('countries')->all();
        if(count($countries)){
            foreach($countries as $country){
                $this->countries[$country->country_name] = $country->country_name;
            }
        }
    }
    
    public function setCities(){
        $cities = array('Baragoi', 'Bungoma','Busia', 'Busia','Butere','Dadaab','Diani Beach','Eldoret','Embu', 'Garissa','Gedi','Hola', 'Homa Bay','Isiolo','Kajiado','Kakamega','Kakuma','Kapenguria','Kericho','Kiambu','Kilifi','Kisii town','Kisumu','Kitale','Lamu', 'Langata','Lodwar','Lokichoggio','Londiani','Loyangalani','Machakos','Malindi','Mandera','Marala','Marsabit','Meru','Mombasa','Moyale','Mumias','Muranga', 'Nairobi','Naivasha','Nakuru','Namanga','Nanyuki','Naro Moru','Narok','Nyahururu','Nyeri','Ruiru','Shimoni','Takaungu','Thika','Vihiga','Voi','Wajir','Watamu','Webuye','Wundanyi');
        foreach ($cities as $city){
            $this->cities[$city] = $city;
        }
    }
    
    public function saveForm($step){
        $this->inputs = Input::post();

        if(!Session::has('customer_id') && $step != 1)
            Redirect::to('/medical/step/1');

        switch($step){
            case 1:
                $this->saveStepOne();
                break;
            
            case 2:
                $this->saveStepTwo();
                break;

            case 'dependants':
                $this->saveDependants();
                break;
            
            case 3:
                $this->saveStepThree();
                break;
        }
    }

    public function saveStepOne(){
        $saved = $this->customer->saveCustomer();
        if($saved){
            // partially save the quote
            $quote_id = $this->quote->saveQuoteRemotely([
                'customer_id' => Session::get('customer_id'),
                'product_id' => $this->product_id
            ],json_encode($this->inputs),[],[],null);

            Session::set('quote_id', $quote_id);
            Redirect::to('/medical/step/2')->withNotice('Saved! Please Proceed to Cover Details');
        }
    }

    public function saveStepTwo(){
        Session::set('no_of_dependants',0);

        // save product info into the customer quotes table
        Session::set('product_info', json_encode($this->inputs));

        if(isset($this->inputs['additional_covers']) && $this->inputs['additional_covers'] > 0) {
            Session::set('no_of_dependants', $this->inputs['additional_covers']);
            Redirect::to('/medical/step/dependants')->withNotice('Saved! Please Add Dependant cover details');
        }

        Redirect::to('/medical/step/3')->withNotice('Saved! Please proceed...');
    }

    public function saveDependants(){
        $dependants_no = Session::get('no_of_dependants');

        $entity_data_ids = [];
        $dependant_data = [];
        if($dependants_no){
            for($dep = 1; $dep <= $dependants_no; $dep++){
                $dependant_data = [
                    'title' => $this->inputs['title_'.$dep],
                    'proposer_surname' => $this->inputs['proposer_surname_'.$dep],
                    'other_names' => $this->inputs['other_names_'.$dep],
                    'date_of_birth' => $this->inputs['dob_'.$dep],
                    'gender' => $this->inputs['gender_'.$dep],
                    'age_range_bracket' => $this->inputs['age_range_bracket_'.$dep],
                    'id/passport' => $this->inputs['id_passport_no_'.$dep],
                    'nhif' => $this->inputs['nhif_'.$dep],
                    'blood_type' => $this->inputs['blood_type_'.$dep],
                    'nationality' => $this->inputs['nationality_'.$dep],
                    'relation_to_spouse' => $this->inputs['relation_to_proposer_'.$dep],
                    'occupation' => $this->inputs['occupation_'.$dep]
                ];

                $this->entity->saveEntityDataRemotely(
                    Session::get('customer_id'),
                    $this->entity_id,
                    json_encode($dependant_data));
            }
        }

        Redirect::to('/medical/step/3')->withNotice('Saved! Please proceed...');
    }

    public function saveStepThree(){
        $ids = [
            'customer_id' => Session::get('customer_id'),
            'product_id' => $this->product_id
        ];

//        dd('saving step 3...');
        $product_info = json_decode(Session::get('product_info'), true);
        $product_info = array_merge($product_info, $this->inputs);

        // get customer info from customer quotes;
        $customer_info = $this->quote->getQuoteById(Session::get('quote_id'))->customer_info;
        $customer_info = json_decode($customer_info,true);

        $amounts = $this->calculateAmounts($customer_info, $product_info);
        $amounts['amounts'];

        // save quote details
        $this->quote->saveQuoteRemotely(
            $ids,
            json_encode($customer_info),
            $product_info,
            $amounts['amounts'],
            $amounts['entity_data_ids'],
            Session::get('quote_id'));

        Redirect::to('/medical/step/4')->withNotice('Saved! Please proceed...');
    }

    public function calculateAmounts($customer_info = array(), $product_info = array()){
        $age_bracket = Medical::determineAgeBracket($customer_info['age_range_bracket'], $customer_info['gender']);
        $selected_plan = Medical::determinePlan($product_info['core_plans']);

        // get the optional benefits
        $core_optional_benefits = Medical::getTheOptionalBenefits($product_info, 'core');

        // get core premium
        $core_premium = $this->model->table('medical_pricing')->find(['agerange_benefits' => $age_bracket])->$selected_plan;

        // get core optional benefits total
        $optional_total = $this->getOptionalBenefitsTotal($selected_plan, $core_optional_benefits);

        $amounts = [
            'insurer_id' => $this->insurer_id,
            'core_premium' => $core_premium,
            'core_optional_benefits' => $optional_total
        ];

        // add other covers amounts
        if($product_info['have_dependants']){
            if ($product_info['additional_covers']){

                $dependants = $this->entity->getEntityDataByCustomerAndEntityId(Session::get('customer_id'), $this->entity_id);
                if(count($dependants)){
                    $i = 1;
                    $dt_total = 0;
                    $entity_data_ids = [];
                    foreach ($dependants as $dependant){
                        $entity_values = json_decode($dependant->entity_values);

                        $age_bracket = Medical::determineAgeBracket($entity_values->age_range_bracket, $entity_values->gender);
                        $dep_premium = $this->model->table('medical_pricing')->find(['agerange_benefits' => $age_bracket])->$selected_plan;

                        $subtotal = $dep_premium + $optional_total;
                        $deps[$i] = [
                            'dep_premium' => $dep_premium,
                            'dep_benefits' => $optional_total
                        ];
                        $dt_total += $subtotal;


                        // get the entity data ids
                        $entity_data_ids[] = $dependant->id;
                        $i++;
                    }
                    $amounts['dependants'] = $deps;
                    $amounts['dt_total'] = $dt_total;
                }
            }
        }

        $premium_rate = $core_premium + $optional_total + $dt_total;
        $amounts['subtotal'] = $premium_rate;
        $amounts['levy'] = $this->determineRate($premium_rate,'Medical Levy','Medical');
        $amounts['phcf'] = $this->determineRate($premium_rate,'P.H.C.F Fund','Medical');
        $amounts['stamp_duty'] = $this->determineRate($premium_rate,'Stamp Duty','Medical');
        $amounts['grand_total'] = $premium_rate + $amounts['levy'] + $amounts['phcf'] + $amounts['stamp_duty'];

        return [
            'amounts' => $amounts,
            'entity_data_ids' => $entity_data_ids
        ];
    }

    public function getOptionalBenefitsTotal($selected_plan, $core_optional_benefits){
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

    public function getOtherCovers($sbid){
        $dependants = $this->model->table('other_covers')->find(['sbid' => $sbid]);
        return json_decode($dependants->details);
    }

    public function determineRate($value, $ratename, $category, $ratetype = ''){
        if($ratetype == ''){
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
        if($rate->rate_type == 'Percentage')
            $computed_value = (($value * $rate->rate_value) / 100);
        else if($rate->rate_type == 'Fixed')
            $computed_value = $rate->rate_value;

        return $computed_value;
    }
}
