<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Accident\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Core\App;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Accident\Models\AccidentModel;
use Jenga\MyProject\Accident\Repositories\AccidentQuotation;
use Jenga\MyProject\Accident\Views\AccidentView;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Products\Controllers\ProductsController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Tracker\Controllers\TrackerController;

/**
 * Class AccidentController
 * @property-read AccidentModel $model
 * @property-read AccidentView $view
 */
class AccidentController extends Controller
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
     * @var TrackerController
     */
    private $_tracker;

    /**
     * Setup entities. Via Elements call
     */
    private function setEntities()
    {
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entity = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
        $this->_products = Elements::call('Products/ProductsController');
        $this->_tracker = Elements::call('Tracker/TrackerController');
    }

    /**
     * Default entry point for external form. Switch between display forms depending on the step. [1 to 4]
     * @param string $step
     */
    public function index($step = "1")
    {
        if ($step != "1") {
//            if (!Session::has('user_id')) {
//                Redirect::to('/accident/step/1');
//                exit;
//            }
        }
        $this->loadViewData($step);
        $this->view->wizard($this->data);
    }

    /**
     * Save incoming forms posted from the front end
     * @param $step
     */
    public function save($step)
    {
        $this->setEntities();
        $this->loadPartialQuote();
        switch ($step) {
            case "1":
                $this->savePersonalDetails();
                break;
            case "2":
                $this->saveAccidentDetails();
                break;
            case "3":
                $this->saveCoverDetails();
                break;
            default:
                break;

        }
        exit;

    }

    /**
     * Save personal details from step 1. Relies on POST
     */
    private function savePersonalDetails()
    {
        if ($customer = $this->_customer->saveCustomer('accident', $this->getInputForStep(1), false)) {
            Session::set('type', 'accident');
            $ids['customer_id'] = $customer;
            $ids['product_id'] = $this->_products->getProductByAlias('personal_accident')->id;
            $customer_info = json_encode(get_object_vars($this->_customer->getCustomerById($customer, null)));
            $id = $this->_quotes->saveQuoteRemotely($ids, $customer_info);
            Session::set('quote_id', $id);
            $notification = 'Saved Please proceed to step two';
            // get the verification notification if new customer registration
            if (Session::has('sent_confirmation'))
                $notification = Session::get('sent_confirmation');

            $this->_startAccidentTracking();
            Redirect::withNotice($notification, 'success')->to('/accident/step/2');
        } else {
            Redirect::to('/accident/step/1')->withNotice('Could not save your info, try again', 'error');
        }
    }

    /**
     * Start Accident Quote tracking
     */
    private function _startAccidentTracking()
    {

        //create quote tracking
        $this->setEntities();

        $customer_id = Session::get('customer_id');
        $product_id = $this->_products->getProductByAlias('personal_accident')->id;
        $quote = Session::get('quote_id');
        $trackerid = $this->_tracker->start($customer_id, $product_id, 1, $quote);

        if (Session::has('trackerid')) {

            $this->_tracker->close(Session::get('trackerid'));
            Session::delete('trackerid');
        }

        Session::set('trackerid', $trackerid);
    }

    /**
     * Update Tracker according to step
     * @param type $step
     */
    private function _updateAccidentTracker($step)
    {
        if (Session::has('trackerid')) {
            //create quote tracking
            $this->setEntities();
            $this->_tracker->assign(Session::get('trackerid'), $step);
        } else {
            App::warning('Quote tracking has not been initialized for this product: Accident');
        }
    }

    /**
     * Close Accident tracking
     * @param type $id
     */
    private function _closeAccidentTracker($id)
    {

        //create quote tracking
        $this->setEntities();
        if (!empty($id))
            $this->_tracker->close($id);
        Session::delete('trackerid');
    }

    /**
     * Save accident details from step 2
     */
    private function saveAccidentDetails()
    {
        $entity_id = $this->_entity->getEntityIdByAlias('person')->id;
        $product_id = $this->_products->getProductByAlias('personal_accident')->id;
        $id = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'),
            $entity_id, json_encode($this->getInputForStep(2)), $product_id);
        $this->_quotes->updateQuoteData($this->quote->id, ['customer_entity_data_id' => json_encode([$id])]);
        $this->_updateAccidentTracker(2);
        Redirect::to('/accident/step/3');
    }

    /**
     * Save Cover Details
     * Save step 3 of form the redirect
     */
    private function saveCoverDetails()
    {
        if (!empty(Input::post('howmany'))) {
            $this->saveExtraCovers(Input::post('howmany'));
        }
        $this->_quotes->updateQuoteData(Session::get('quote_id'), ['product_info' => json_encode($this->getInputForStep(3))]);
        $this->_updateAccidentTracker(3);
        Redirect::to('/accident/step/4');
    }

    /**
     * Save extra covers
     * Count the number of extra covers and map them to entity data
     * @param $count
     */
    private function saveExtraCovers($count)
    {
        $saved = [];
        $main = array_first(json_decode($this->quote->customer_entity_data_id));
        $product_id = $this->_products->getProductByAlias('personal_accident')->id;
        for ($i = 1; $i <= $count; $i++) {
            $got = $this->_buildStack($i);
            $entity_id = $this->_entity->getEntityIdByAlias('person')->id;
            $saved[] = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'),
                $entity_id, json_encode($got), $product_id);
        }
        array_unshift($saved, $main);
        $this->_quotes->updateQuoteData($this->quote->id, [
            'customer_entity_data_id' => json_encode($saved)
        ]);
    }

    /**
     * Set up partial quote
     */
    private function loadPartialQuote()
    {
        $this->quote = $this->_quotes->getQuoteById(Session::get('quote_id'));
    }

    /**
     * Load View Data
     * Set up some data needed in the View and panels
     * @param $step
     */
    private function loadViewData($step)
    {
        $this->setEntities();
        $this->data = new \stdClass();
        $this->data->step = $step;
        $this->loadPartialQuote();
        $this->data->titles = ['Mr' => 'Mr', 'Mrs' => 'Mrs', 'Ms' => 'Ms', 'Dr' => 'Dr', 'Prof' => 'Prof', 'Eng' => 'Eng'];
        $this->data->age_bracket = $this->_select(['1-18', '19-30', '31-40', '41-50', '51-59', '60-69', '70 or over']);
        $this->data->bands = [
            'band1' => 'Band 1: Covers you upto a limit of 250000 for accidental death',
            'band2' => 'Band 2:	Covers you upto a limit of 500000 for accidental death',
            'band3' => 'Band 3: Covers you upto a limit of 1000000 for accidental death',
            'band4' => 'Band 4: Covers you upto a limit of 2000000 for accidental death',
            'band5' => 'Band 5: Covers you upto a limit of 4000000 for accidental death',
            'band6' => 'Band 6: Covers you upto a limit of 8000000 for accidental death',
            'band7' => 'Band 7: Covers you upto a limit of 10000000 for accidental death',
        ];
        $this->data->towns = $this->_select(['Baragoi',
            'Bungoma', 'Busia', 'Butere', 'Dadaab', 'Diani Beach',
            'Eldoret', 'Embu', 'Garissa', 'Gede', 'Hola', 'Homa Bay',
            'Isiolo', 'Kajiado', 'Kakamega', 'Kakuma', 'Kapenguria', 'Kericho',
            'Kiambu', 'Kilifi', 'Kisii', 'Kisumu', 'Kitale', 'Lamu', 'Langata', 'Lodwar',
            'Lokichoggio', 'Loyangalani', 'Machakos', 'Malindi', 'Mandera', 'Maralal', 'Marsabit',
            'Meru', 'Mombasa', 'Moyale', 'Mumias', 'Muranga', 'Nairobi', 'Naivasha', 'Nakuru', 'Namanga',
            'Nanyuki', 'Naro Moru', 'Narok', 'Nyahururu', 'Nyeri', 'Ruiru', 'Shimoni', 'Takaungu', 'Thika',
            'Vihiga', 'Voi', 'Wajir', 'Watamu', 'Webuye', 'Wundanyi']);

        $this->data->years = $this->_years();
        $this->data->insurer = $this->_select(['AIG Kenya', 'Gateway', 'AAR Insurance Kenya Limited', 'A P A Insurance Limited', 'Africa Merchant Assurance Company Limited', 'Apollo Life Assurance Limited', 'AIG Kenya Insurance Company Limited', 'American Insurance Company (Kenya) Limited', 'Cannon Assurance Limited', 'Capex Life Assurance Company Limited', 'CFC Life Assurance Limited', 'CIC General Insurance Limited', 'CIC Life Assurance Limited', 'Continental Reinsurance Limited', 'Corporate Insurance Company Limited', 'Directline Assurance Company Limited', 'East Africa Reinsurance Company Limited', 'Fidelity Shield Insurance Company Limited', 'First Assurance Company Limited', 'G A Insurance Limited', 'Gateway Insurance Company Limited', 'Geminia Insurance Company Limited', 'ICEA LION General Insurance Company Limited', 'ICEA LION Life Assurance Company Limited', 'Intra Africa Assurance Company Limited', 'Invesco Assurance Company Limited', 'Kenindia Assurance Company Limited', 'Kenya Orient Insurance Limited',
            'Kenya Reinsurance Corporation Limited', 'Madison Insurance Company Kenya Limited', 'Mayfair Insurance Company Limited',
            'Mercantile Insurance Company Limited', 'Metropolitan Life Insurance Kenya Limited', 'Occidental Insurance Company Limited',
            'Old Mutual Life Assurance Company Limited', 'Pacis Insurance Company Limited', 'Pan Africa Life Assurance Limited', 'Phoenix of East Africa Assurance Company Limited', 'Pioneer Assurance Company Limited', 'Real Insurance Company Limited', 'Resolution Insurance Company Limited', 'Shield Assurance Company Limited', 'Takaful Insurance of Africa Limited', 'Tausi Assurance Company Limited', 'The Heritage Insurance Company Limited', 'The Jubilee Insurance Company of Kenya Limited', 'The Monarch Insurance Company Limited', 'Trident Insurance Company Limited', 'UAP Insurance Company Limited', 'UAP Life Assurance Limited', 'Xplico Insurance Company Limited']);

        $this->data->pick_cert = $this->_select([
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
        if ($step == "4") {
            $this->data->payments = $this->_quotes->getQuotations(Session::get('quote_id'));
            $this->_closeAccidentTracker(Session::get('trackerid'));
        }
        $this->data->quote = $this->quote;
    }

    /**
     * Transform an array to its key:value representation
     * @param $data
     * @return array
     */
    private function _select($data)
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
    private function _years($span = 100)
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
     * Attempt to get an array of each person for extra covers
     * @param $index
     * @return array
     */
    private function _buildStack($index)
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
     * Get the form schematic for displaying form
     * @return array
     */
    public function getSchematic()
    {
        $forms = [1, 2, 3];
        $schematic = [];
        $this->setEntities();
        $count = 0;
        foreach ($forms as $form) {
            $this->loadViewData(++$count);
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
        $this->loadViewData($step);
        $schematic = $this->view->getSchematic($this->data, false);
        $my_array = [];
        foreach ($schematic['controls'] as $schema) {
            $my_array[] = $schema[1];
        }
        return array_only(Input::post(), $my_array);
    }
}
