<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Motor\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Motor\Repositories\MotorQuotation;
use Jenga\MyProject\Motor\Repositories\Payments;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

/**
 * Class MotorController
 */
class MotorController extends Controller
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var CustomersController
     */
    private $_customer;
    /**
     * @var EntitiesController
     */
    private $_entity;
    /**
     * @var QuotesController
     */
    private $_quotes;

    public function __construct()
    {
        parent::__construct();
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entity = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
    }

    /**
     * @param string $step
     */
    public function index($step = "1")
    {
        if ($step != "1") {
            if (empty(Session::get('customer_id')) || (Session::get('type') != 'motor')) {
                Redirect::to('/motor/step/1');
                exit;
            }
        }
        $this->loadData($step);
        $this->view->wizard($this->data);
    }

    /**
     * @param int $step
     */
    public function save($step)
    {
        switch ($step) {
            case 1:
                $this->savePersonalDetails();
                break;
            case 2 :
                $this->saveCarDetails();
                break;
            case 22:
                $this->saveOtherCarDetails();
                break;
            case 3 :
                $this->saveCoverDetails();
                break;
            case 4 :
                //$this->saveStep3();
                break;
        }
        exit;
    }

    /**
     * Load data for the view
     * @param $step
     */
    private function loadData($step)
    {
        $this->data = new \stdClass();
        $this->data->step = $step;
        $this->data->titles = ['Mr' => 'Mr', 'Mrs' => 'Mrs', 'Ms' => 'Ms', 'Dr' => 'Dr', 'Prof' => 'Prof', 'Eng' => 'Eng'];
        $this->data->numbers = $this->__select(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', 'Over 15']);
        $this->data->years = $this->__years();
        $this->data->towns = $this->__select(['Select Town', 'Baragoi',
            'Bungoma', 'Busia', 'Butere', 'Dadaab', 'Diani Beach', 'Eldoret', 'Embu', 'Garissa',
            'Gede', 'Hola', 'Homa Bay', 'Isiolo', 'Kajiado', 'Kakamega', 'Kakuma', 'Kapenguria',
            'Kericho', 'Kiambu', 'Kilifi', 'Kisii', 'Kisumu', 'Kitale', 'Lamu', 'Langata', 'Lodwar',
            'Lokichoggio', 'Loyangalani', 'Machakos', 'Malindi', 'Mandera', 'Maralal', 'Marsabit', 'Meru',
            'Mombasa', 'Moyale', 'Mumias', 'Muranga', 'Nairobi', 'Naivasha', 'Nakuru', 'Namanga', 'Nanyuki',
            'Naro Moru', 'Narok', 'Nyahururu', 'Nyeri', 'Ruiru', 'Shimoni', 'Takaungu', 'Thika', 'Vihiga', 'Voi'
            , 'Wajir', 'Watamu', 'Webuye', 'Wundanyi']);
        $this->data->car_usage = $this->__select([
            'For social, domestic and pleasure purposes',
            'For professional  purposes',
            'By personally in connection with connection or your employer\'s business',
            'By employees or other parties  in connection with connection or your employer\'s business',
            'For the carriage of samples or trade goods farm requisites, produce of live stock'
        ]);
        $this->data->parking_lots = [
            'Car Park' => 'Car Park',
            'Driveway' => 'Driveway',
            'Garaged at home' => 'Garaged at home',
            'Kept on a public road' => 'Kept on a public road'];
        $makes = $this->model->table('make')->all();
        $make = [];
        foreach ($makes as $one) {
            $make[$one->code] = $one->title;
        }
        $this->data->makes = $make;
        $this->data->cover_type = $this->__select([
            'Comprehensive',
            'Third Party Fire and Theft',
            'Third Party Only',
            'Ordinance Liabilities Only'
        ]);
        if ($step == 4) {
            $this->data->payments = new MotorQuotation();
        }
        $this->data->pick_cert = $this->__select([
            'Nairobi, Head Office, Jubilee Insurance House, Wabera Street',
            'Nairobi, Mombasa Road, Tulip House, Ground Floor',
            'Nairobi, Fuji House, Westlands, Wing B, 6th Floor',
            'Mombasa, Jubilee Insurance Building, Moi Avenue ',
            'Kisumu, Jubilee Insurance House, Oginga Odinga Road, 3rd Floor ',
            'Bungoma, Bungoma Business Centre, Moi Avenue, 1st Floor',
            'Eldoret, Imperial Court Eldoret, Nairobi/ Uganda Highway, 1st Floor ',
            'Kisii, New Sansora Building, Town Centre, Hospital Road ',
            'Meru, Alexander House, Meru Town, 2nd Floor',
            'Nakuru, Pollo Centre, Off Kenyatta Avenue',
            'Nyeri, Sohan Plaza, Moi Nyayo Way',
            'Thika, Thika Arcade, Thika Town, 4th Floor',
            'Post to my address in your records (a small extra charge will apply)']);
    }

    private function saveOtherCarDetails()
    {
        $count = Session::get('other_covers');
        $saved = [];
        for ($i = 1; $i <= $count; $i++) {
            $got = $this->__buildStack($i);
            $entity_id = $this->_entity->getEntityIdByAlias('vehicle')->id;
            $saved[] = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'),
                $entity_id,
                json_encode($got));

        }
        Session::set('other_id', serialize($saved));
        Redirect::to('/motor/step/3');
    }

    private function savePersonalDetails()
    {
        if ($this->_customer->saveMyCustomer($this->filteredData())) {
            Session::set('type', 'motor');
            Redirect::to('/motor/step/2');
        } else {
            Redirect::to('/motor/step/1')->withNotice('Could not save your info, try again', 'error');
        }
    }

    private function __buildStack($index)
    {
        $build = [];
        foreach ($this->filteredData() as $key => $value) {
            if (ends_with($key, $index)) {
                $build[rtrim($key, $index)] = $value;
            }
        }
        return $build;
    }

    private function saveCarDetails()
    {
        $entity_id = $this->_entity->getEntityIdByAlias('vehicle')->id;
        $car_details = json_encode($this->filteredData());//some zebra stuff
        $saved = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'), $entity_id, $car_details);
        Session::set('main_id', $saved);
        Session::set('other_id', serialize([]));
        if (!empty(Input::post('othercovers'))) {
            Session::set('other_covers', Input::post('othercovers'));
            Redirect::to('/motor/step/22');
        } else {
            Redirect::to('/motor/step/3');
        }

    }

    private function saveCoverDetails()
    {
        $ids['customer_id'] = Session::get('customer_id');
        $ids['product_id'] = 1;
        $customer_info = json_encode(get_object_vars($this->_customer->getCustomerById(Session::get('customer_id'), null)));
        $entities = unserialize(Session::get('other_id'));
        array_push($entities, Session::get('main_id'));
        $id = $this->_quotes->saveQuoteRemotely($ids, $customer_info, $this->filteredData(), null, $entities);
        Session::set('quote_id', $id);
        Redirect::to('/motor/step/4');
    }


    private function __select($data)
    {
        $_data = [];
        foreach ($data as $v) {
            $_data[$v] = $v;
        }
        return $_data;
    }

    private function __years($span = 100)
    {
        $year = date('Y');
        $lastyear = ($year - $span);
        $yearlist = ['' => 'Select'];
        for ($i = $year; $i >= $lastyear; $i--) {
            $yearlist[$i] = $i;
        }
        return $yearlist;
    }

    private function filteredData()
    {
        $block = [
            'name_motor_personal_details', 'zebra_honeypot_motor_personal_details', 'zebra_csrf_token_motor_personal_details',
            'name_motor_car_details', 'zebra_honeypot_motor_car_details', 'zebra_honeypot_motor_cover_details',
            'zebra_csrf_token_motor_car_details', 'btnSubmitSpecial', 'name_motor_cover_details',
            'zebra_csrf_token_motor_cover_details', 'btnsubmit',
        ];
        return array_except(Input::post(), $block);
    }
}
