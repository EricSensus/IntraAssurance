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
use Jenga\MyProject\Domestic\Repositories\DomesticQuotation;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

/**
 * Class DomesticController
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

    public function __construct()
    {
        parent::__construct();
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entity = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
    }

    /**
     * @var CustomersController
     */
    private $customer;

    public function index($step = "1")
    {

        if ($step != "1") {
            if (empty(Session::get('customer_id')) || (Session::get('type') != 'domestic')) {
                Redirect::to('/domestic/step/1');
                exit;
            }
        }
        $this->loadData($step);
        $this->view->wizard($this->data);
    }

    /**
     * Data to be passed to views
     * @param $step
     */
    private function loadData($step)
    {
        $this->data = new \stdClass();
        $this->data->step = $step;
        $this->data->titles = ['Mr' => 'Mr', 'Mrs' => 'Mrs', 'Ms' => 'Ms', 'Dr' => 'Dr', 'Prof' => 'Prof', 'Eng' => 'Eng'];
        $this->data->towns = $this->__select(['Baragoi',
            'Bungoma', 'Busia', 'Butere', 'Dadaab', 'Diani Beach',
            'Eldoret', 'Embu', 'Garissa', 'Gede', 'Hola', 'Homa Bay',
            'Isiolo', 'Kajiado', 'Kakamega', 'Kakuma', 'Kapenguria', 'Kericho',
            'Kiambu', 'Kilifi', 'Kisii', 'Kisumu', 'Kitale', 'Lamu', 'Langata', 'Lodwar',
            'Lokichoggio', 'Loyangalani', 'Machakos', 'Malindi', 'Mandera', 'Maralal', 'Marsabit',
            'Meru', 'Mombasa', 'Moyale', 'Mumias', 'Muranga', 'Nairobi', 'Naivasha', 'Nakuru', 'Namanga',
            'Nanyuki', 'Naro Moru', 'Narok', 'Nyahururu', 'Nyeri', 'Ruiru', 'Shimoni', 'Takaungu', 'Thika',
            'Vihiga', 'Voi', 'Wajir', 'Watamu', 'Webuye', 'Wundanyi']);
        $this->data->walls = $this->__select(['Brick Walls', 'Concrete Walls', 'Stone Walls']);
        $this->data->roofs = $this->__select(['Tiled Roof', 'Asphalt Roof', 'Concrete Roof', 'Slate', 'Timber', 'Corrugated']);
        $this->data->dwelling = $this->__select(["Bungalow", "Maisonette", "Town house", "Apartment"]);
        $this->data->security = $this->__select(["Buglary Proof Doors / Windows", "Siren / Alarm", "Security guard", "Panic button", "Perimeter wall", "Dogs", "Electric fence", 'Other']);
        $this->data->years = $this->__years();
        $this->data->insurer = $this->__select(['AIG Kenya', 'Gateway', 'AAR Insurance Kenya Limited', 'A P A Insurance Limited', 'Africa Merchant Assurance Company Limited', 'Apollo Life Assurance Limited', 'AIG Kenya Insurance Company Limited', 'American Insurance Company (Kenya) Limited', 'Cannon Assurance Limited', 'Capex Life Assurance Company Limited', 'CFC Life Assurance Limited', 'CIC General Insurance Limited', 'CIC Life Assurance Limited', 'Continental Reinsurance Limited', 'Corporate Insurance Company Limited', 'Directline Assurance Company Limited', 'East Africa Reinsurance Company Limited', 'Fidelity Shield Insurance Company Limited', 'First Assurance Company Limited', 'G A Insurance Limited', 'Gateway Insurance Company Limited', 'Geminia Insurance Company Limited', 'ICEA LION General Insurance Company Limited', 'ICEA LION Life Assurance Company Limited', 'Intra Africa Assurance Company Limited', 'Invesco Assurance Company Limited', 'Kenindia Assurance Company Limited', 'Kenya Orient Insurance Limited',
            'Kenya Reinsurance Corporation Limited', 'Madison Insurance Company Kenya Limited', 'Mayfair Insurance Company Limited',
            'Mercantile Insurance Company Limited', 'Metropolitan Life Insurance Kenya Limited', 'Occidental Insurance Company Limited',
            'Old Mutual Life Assurance Company Limited', 'Pacis Insurance Company Limited', 'Pan Africa Life Assurance Limited', 'Phoenix of East Africa Assurance Company Limited', 'Pioneer Assurance Company Limited', 'Real Insurance Company Limited', 'Resolution Insurance Company Limited', 'Shield Assurance Company Limited', 'Takaful Insurance of Africa Limited', 'Tausi Assurance Company Limited', 'The Heritage Insurance Company Limited', 'The Jubilee Insurance Company of Kenya Limited', 'The Monarch Insurance Company Limited', 'Trident Insurance Company Limited', 'UAP Insurance Company Limited', 'UAP Life Assurance Limited', 'Xplico Insurance Company Limited']);
        $this->data->addons = $this->__select([
            "Domestic servants if so specify job description and numbers",
            "Your liability as an owner (limit of lability is Kshs 1000,000.00",
            "Your liability as a renter or occupier (limit of liability is Kshs 1000,000.00)"]);
        $this->data->liabilities = $this->__select(['1 million', '2 million', '3 million', '4 million', '5 million', '6 million']);
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
        if ($step == "4") {
            $this->data->quotation = new DomesticQuotation();
        }
    }

    public function save($step)
    {
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

    private function saveCoverDetails()
    {
        $ids['customer_id'] = Session::get('customer_id');
        $ids['product_id'] = 8;
        $customer_info = json_encode(get_object_vars($this->_customer->getCustomerById(Session::get('customer_id'), null)));
        $entities = unserialize(Session::get('other_id'));
        array_push($entities, Session::get('main_id'));
        $id = $this->_quotes->saveQuoteRemotely($ids, $customer_info, $this->filteredData(), null, $entities);
        Session::set('quote_id', $id);
        Redirect::to('/domestic/step/4');
    }

    private function savePropertyDetails()
    {
        $entity_id = $this->_entity->getEntityIdByAlias('private_property')->id;
        $saved = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'),
            $entity_id,
            json_encode($this->filteredData()));
        Session::set('main_id', $saved);
        Session::set('other_id', serialize([]));
        Redirect::to('/domestic/step/3');
    }

    private function savePersonalDetails()
    {
        if ($this->_customer->saveCustomer($this->filteredData())) {
            Session::set('type', 'domestic');
            Redirect::to('/domestic/step/2');
        } else {
            Redirect::to('/domestic/step/1')->withNotice('Could not save your info, try again', 'error');
        }
    }

    private function filteredData()
    {
        $block = [
            'name_domestic_personal_details', 'zebra_honeypot_domestic_personal_details',
            'zebra_csrf_token_domestic_personal_details', 'btnsubmit',
            'name_domestic_property_details', 'zebra_honeypot_domestic_property_details',
            'zebra_csrf_token_domestic_property_details', 'btnSubmitSpecial',
            'name_domestic_cover_details', 'zebra_honeypot_domestic_cover_details',
            'zebra_csrf_token_domestic_cover_details'];
        return array_except(Input::post(), $block);
    }
}
