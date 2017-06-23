<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Domestic\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Core\App;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Domestic\Models\DomesticModel;
use Jenga\MyProject\Domestic\Repositories\DomesticQuotation;
use Jenga\MyProject\Domestic\Views\DomesticView;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Products\Controllers\ProductsController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Tracker\Controllers\TrackerController;

/**
 * Class DomesticController
 * @property-read DomesticModel $model
 * @property-read DomesticView $view
 */
class DomesticController extends Controller
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
     * @var object
     */
    private $quote;

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

//        if ($step != "1") {
//            if (!Session::has('user_id')) {
//                Redirect::to('/domestic/step/1');
//                exit;
//            }
//        }
        $this->loadData($step);
        $this->view->wizard($this->data);
    }

    /**
     * Data to be passed to views
     * @param $step
     */
    private function loadData($step)
    {
        $this->setEntities();
        $this->data = new \stdClass();
        $this->data->step = $step;
        $this->loadPartialQuote();
        $this->data->titles = ['Mr' => 'Mr', 'Mrs' => 'Mrs', 'Ms' => 'Ms', 'Dr' => 'Dr', 'Prof' => 'Prof', 'Eng' => 'Eng'];
        $this->data->towns = $this->_select(['Baragoi',
            'Bungoma', 'Busia', 'Butere', 'Dadaab', 'Diani Beach',
            'Eldoret', 'Embu', 'Garissa', 'Gede', 'Hola', 'Homa Bay',
            'Isiolo', 'Kajiado', 'Kakamega', 'Kakuma', 'Kapenguria', 'Kericho',
            'Kiambu', 'Kilifi', 'Kisii', 'Kisumu', 'Kitale', 'Lamu', 'Langata', 'Lodwar',
            'Lokichoggio', 'Loyangalani', 'Machakos', 'Malindi', 'Mandera', 'Maralal', 'Marsabit',
            'Meru', 'Mombasa', 'Moyale', 'Mumias', 'Muranga', 'Nairobi', 'Naivasha', 'Nakuru', 'Namanga',
            'Nanyuki', 'Naro Moru', 'Narok', 'Nyahururu', 'Nyeri', 'Ruiru', 'Shimoni', 'Takaungu', 'Thika',
            'Vihiga', 'Voi', 'Wajir', 'Watamu', 'Webuye', 'Wundanyi']);
        $this->data->walls = $this->_select(['Brick Walls', 'Concrete Walls', 'Stone Walls']);
        $this->data->roofs = $this->_select(['Tiled Roof', 'Asphalt Roof', 'Concrete Roof', 'Slate', 'Timber', 'Corrugated']);
        $this->data->dwelling = $this->_select(["Bungalow", "Maisonette", "Town house", "Apartment"]);
        $this->data->security = $this->_select(["Buglary Proof Doors / Windows", "Siren / Alarm", "Security guard", "Panic button", "Perimeter wall", "Dogs", "Electric fence", 'Other']);
        $this->data->years = $this->_years();
        $this->data->insurer = $this->_select(['AIG Kenya', 'Gateway', 'AAR Insurance Kenya Limited', 'A P A Insurance Limited', 'Africa Merchant Assurance Company Limited', 'Apollo Life Assurance Limited', 'AIG Kenya Insurance Company Limited', 'American Insurance Company (Kenya) Limited', 'Cannon Assurance Limited', 'Capex Life Assurance Company Limited', 'CFC Life Assurance Limited', 'CIC General Insurance Limited', 'CIC Life Assurance Limited', 'Continental Reinsurance Limited', 'Corporate Insurance Company Limited', 'Directline Assurance Company Limited', 'East Africa Reinsurance Company Limited', 'Fidelity Shield Insurance Company Limited', 'First Assurance Company Limited', 'G A Insurance Limited', 'Gateway Insurance Company Limited', 'Geminia Insurance Company Limited', 'ICEA LION General Insurance Company Limited', 'ICEA LION Life Assurance Company Limited', 'Intra Africa Assurance Company Limited', 'Invesco Assurance Company Limited', 'Kenindia Assurance Company Limited', 'Kenya Orient Insurance Limited',
            'Kenya Reinsurance Corporation Limited', 'Madison Insurance Company Kenya Limited', 'Mayfair Insurance Company Limited',
            'Mercantile Insurance Company Limited', 'Metropolitan Life Insurance Kenya Limited', 'Occidental Insurance Company Limited',
            'Old Mutual Life Assurance Company Limited', 'Pacis Insurance Company Limited', 'Pan Africa Life Assurance Limited', 'Phoenix of East Africa Assurance Company Limited', 'Pioneer Assurance Company Limited', 'Real Insurance Company Limited', 'Resolution Insurance Company Limited', 'Shield Assurance Company Limited', 'Takaful Insurance of Africa Limited', 'Tausi Assurance Company Limited', 'The Heritage Insurance Company Limited', 'The Jubilee Insurance Company of Kenya Limited', 'The Monarch Insurance Company Limited', 'Trident Insurance Company Limited', 'UAP Insurance Company Limited', 'UAP Life Assurance Limited', 'Xplico Insurance Company Limited']);
        $this->data->addons = $this->_select([
            'Domestic servants if so specify job description and numbers',
            'Your liability as an owner (limit of lability is Kshs 1000,000.00',
            'Your liability as a renter or occupier (limit of liability is Kshs 1000,000.00)']);
        $this->data->liabilities = $this->_select(['1 million', '2 million', '3 million', '4 million', '5 million', '6 million']);

        if ($step == "4") {
            $this->data->payments = $this->_quotes->getQuotations(Session::get('quote_id'));
            $this->_closeDomesticTracker(Session::get('trackerid'));
        }
        $this->data->quote = $this->quote;
    }

    /**
     * Set up partial quote
     */
    private function loadPartialQuote()
    {
        $this->quote = $this->_quotes->getQuoteById(Session::get('quote_id'));
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
                $this->savePropertyDetails();

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
     * Save Cover Details
     * Save step 3 of form the redirect
     */
    private function saveCoverDetails()
    {
        $this->_quotes->updateQuoteData($this->quote->id, ['product_info' => json_encode($this->getInputForStep(3))]);
        $this->_updateDomesticTracker(3);
        Redirect::to('/domestic/step/4');
    }

    /**
     * Save property details
     */
    private function savePropertyDetails()
    {
        $entity_id = $this->_entity->getEntityIdByAlias('private_property')->id;
        $product_id = $this->_products->getProductByAlias('domestic_package')->id;
        $saved = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'),
            $entity_id, json_encode($this->getInputForStep(2)), $product_id);
        $this->_quotes->updateQuoteData($this->quote->id, ['customer_entity_data_id' => json_encode([$saved])]);
        $this->_updateDomesticTracker(2);
        Redirect::to('/domestic/step/3');
    }

    /**
     * Save personal details
     */
    private function savePersonalDetails()
    {
        if ($customer = $this->_customer->saveCustomer('domestic', $this->getInputForStep(1), false)) {
            Session::set('type', 'domestic');
            $ids['customer_id'] = $customer;
            $ids['product_id'] = $this->_products->getProductByAlias('domestic_package')->id;
            $customer_info = json_encode(get_object_vars($this->_customer->getCustomerById($customer, null)));
            $id = $this->_quotes->saveQuoteRemotely($ids, $customer_info);
            Session::set('quote_id', $id);
            $notification = 'Saved Please proceed to step two';
            // get the verification notification if new customer registration
            if (Session::has('sent_confirmation'))
                $notification = Session::get('sent_confirmation');

            //start tracking
            $this->_startDomesticTracking();

            Redirect::withNotice($notification, 'success')->to('/domestic/step/2');
        } else {
            Redirect::to('/domestic/step/1')->withNotice('Could not save your info, try again', 'error');
        }
    }

    /**
     * Start Domestic Quote tracking
     */
    private function _startDomesticTracking()
    {

        //create quote tracking
        $this->setEntities();

        $customer_id = Session::get('customer_id');
        $product_id = $this->_products->getProductByAlias('domestic_package')->id;
        $step = 1;
        $quote = Session::get('quote_id');
        $trackerid = $this->_tracker->start($customer_id, $product_id, $step, $quote);

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
    private function _updateDomesticTracker($step)
    {

        if (Session::has('trackerid')) {

            //create quote tracking
            $this->setEntities();

            $this->_tracker->assign(Session::get('trackerid'), $step);
        } else {
            App::warning('Quote tracking has not been initialized for this product: Domestic');
        }
    }

    /**
     * Close Domestic tracking
     * @param type $id
     */
    private function _closeDomesticTracker($id)
    {
        $this->setEntities();
        if (!empty($id))
            $this->_tracker->close($id);
        Session::delete('trackerid');
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
