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
use Jenga\MyProject\Motor\Models\MotorModel;
use Jenga\MyProject\Motor\Repositories\MotorQuotation;
use Jenga\MyProject\Motor\Repositories\Payments;
use Jenga\MyProject\Motor\Views\MotorView;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Quotes\Library\Companies\AIGQuotes;
use Jenga\MyProject\Quotes\Library\Companies\JubileeQuotes;

/**
 * @property-read MotorView $view
 * @property-read MotorModel $model
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

    /**
     * @var ProductsController
     */
    private $_products;

    /**
     * Setup entities. Via Elements call
     */
    private function setEntities()
    {
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entity = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        $this->_products = Elements::call('Products/ProductsController');
    }

    /**
     * Default entry point for external form. Switch between display forms depending on the step. [1 to 4]
     * @param string $step
     */
    public function index($step = "1")
    {
        //SNGUMO - Removed redirection because for new customer the user_id isnt set

//        if ($step != "1") {
//            if (!Session::has('user_id')) {
//                Redirect::to('/motor/step/1');
//                exit;
//            }
//        }
        $this->setEntities();
        $this->loadData($step);
        $this->view->wizard($this->data);
    }

    /**
     * Save incoming forms posted from the front end
     * @param $step
     */
    public function save($step)
    {
        $this->setEntities();
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
     * Load View Data
     * Set up some data needed in the View and panels
     * @param $step
     */
    private function loadData($step)
    {
        $this->setEntities();
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
        $this->data->car_usage = ['For social, domestic and pleasure purposes', 'For professional and business purposes'];
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
        $this->data->cover_type = $this->__select(['Comprehensive', 'Third Party Fire and Theft', 'Third Party Only']);

        if ($step == 4) {
            $this->data->payments = $this->_quotes->getQuotations(Session::get('quote_id'));
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

        $this->data->customer = $this->_customer->getCustomerByUserId($this->user()->user_id);
    }

    /**
     * Save details of other cars
     */
    private function saveOtherCarDetails()
    {
        $count = Session::get('other_covers');
        $saved = [];
        $product_id = $this->_products->getProductByAlias('motor_insurance')->id;
        for ($i = 1; $i <= $count; $i++) {
            $got = $this->__buildStack($i);
            $entity_id = $this->_entity->getEntityIdByAlias('vehicle')->id;
            $saved[] = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'), $entity_id,
                json_encode($got), $product_id);

        }
        Session::set('other_id', serialize($saved));
        Redirect::to('/motor/step/3');
    }

    /**
     * Save personal details from step 1. Relies on POST
     */
    private function savePersonalDetails()
    {
        if ($this->_customer->saveCustomer('motor', $this->getInputForStep(1), false)) {
            Session::set('type', 'motor');

            $notification = 'Saved Please proceed to step two';
            // get the verification notification if new customer registration
            if (Session::has('sent_confirmation'))
                $notification = Session::get('sent_confirmation');

            Redirect::withNotice($notification, 'success')->to('/motor/step/2');
        } else {
            Redirect::to('/motor/step/1')->withNotice('Could not save your info, try again', 'error');
        }
    }

    /**
     * Attempt to get an array of each cars for extra covers
     * @param $index
     * @return array
     */
    private function __buildStack($index)
    {
        $build = [];
        foreach (Input::post() as $key => $value) {
            if (ends_with($key, $index)) {
                $build[rtrim($key, $index)] = $value;
            }
        }
        return $build;
    }


    /**
     * Save car details
     */
    private function saveCarDetails()
    {
        $entity_id = $this->_entity->getEntityIdByAlias('vehicle')->id;
        $car_details = json_encode($this->getInputForStep(2));
        $product_id = $this->_products->getProductByAlias('motor_insurance')->id;
        $saved = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'), $entity_id, $car_details, $product_id);
        Session::set('main_id', $saved);
        Session::set('other_id', serialize([]));
        if (!empty(Input::post('othercovers'))) {
            Session::set('other_covers', Input::post('othercovers'));
            Redirect::to('/motor/step/22');
        } else {
            Redirect::to('/motor/step/3');
        }

    }


    /**
     * Save cover details
     */
    private function saveCoverDetails()
    {
        $ids['customer_id'] = Session::get('customer_id');
        $ids['product_id'] = 1;
        $customer_info = json_encode(get_object_vars($this->_customer->getCustomerById(Session::get('customer_id'), null)));
        $entities = unserialize(Session::get('other_id'));
        array_push($entities, Session::get('main_id'));
        $id = $this->_quotes->saveQuoteRemotely($ids, $customer_info, $this->getInputForStep(3), null, $entities);
        Session::set('quote_id', $id);
        Redirect::to('/motor/step/4');
    }


    /**
     * Transform an array to its key:value representation
     * @param $data
     * @return array
     */
    private function __select($data)
    {
        $_data = [];
        foreach ($data as $v) {
            $_data[$v] = $v;
        }
        return $_data;
    }

    /**
     * Generate some years for the specified span
     * @param int $span
     * @return array
     */
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

    /**
     * Get extra covers schematic
     * @param int $count
     */
    public function getExtraCovers($count = 1)
    {
        $this->setEntities();
        $this->loadData(22);
        echo $this->view->getSchematic($this->data, $count);
    }

    /**
     * Get the form schematic
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
     * Return only specified input for a step
     * @param $step
     * @return array
     */
    public function getInputForStep($step)
    {
        $this->setEntities();
        $this->loadData($step);
        $schematic = $this->view->getSchematic($this->data, false);
        $my_array = [];
        foreach ($schematic['controls'] as $schema) {
            $my_array[] = $schema[1];
        }
        return array_only(Input::post(), $my_array);
    }
}
