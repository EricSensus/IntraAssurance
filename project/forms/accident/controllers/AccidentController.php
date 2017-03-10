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
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Accident\Repositories\AccidentQuotation;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Entities\Controllers\EntitiesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

/**
 * Class AccidentController
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

    public function __construct()
    {
        parent::__construct();
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_entity = Elements::call('Entities/EntitiesController');
        $this->_quotes = Elements::call('Quotes/QuotesController');
    }

    public function index($step = "1")
    {
        if ($step != "1") {
            if (empty(Session::get('customer_id')) || (Session::get('type') != 'accident')) {
                Redirect::to('/accident/step/1');
                exit;
            }
        }
        $this->loadData($step);
        $this->view->wizard($this->data);
    }

    public function save($step)
    {
//        print_r($this->filteredData());
//        exit;
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

    private function savePersonalDetails()
    {
        if ($this->_customer->saveCustomer($this->filteredData())) {
            Session::set('type', 'accident');
            Redirect::to('/accident/step/2');
        } else {
            Redirect::to('/accident/step/1')->withNotice('Could not save your info, try again', 'error');
        }
    }

    private function saveAccidentDetails()
    {
        $entity_id = $this->_entity->getEntityIdByAlias('person')->id;
        $id = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'),
            $entity_id,
            json_encode($this->filteredData()));
        Session::set('main_id', $id);
        Session::set('other_id', serialize([]));
        Redirect::to('/accident/step/3');
    }


    private function saveCoverDetails()
    {
        if (!empty(Input::post('howmany'))) {
            $this->saveExtracovers(Input::post('howmany'));
        }
        $ids['customer_id'] = Session::get('customer_id');
        $ids['product_id'] = 5;
        $customer_info = json_encode(get_object_vars($this->_customer->getCustomerById(Session::get('customer_id'), null)));
        $entities = unserialize(Session::get('other_id'));
        array_push($entities, Session::get('main_id'));
        $id = $this->_quotes->saveQuoteRemotely($ids, $customer_info, $this->filteredData(), null, $entities);
        Session::set('quote_id', $id);
        Redirect::to('/accident/step/4');
    }

    private function saveExtracovers($count)
    {
        $saved = [];
        for ($i = 1; $i <= $count; $i++) {
            $got = $this->__buildStack($i);
            $entity_id = $this->_entity->getEntityIdByAlias('person')->id;
            $saved[] = $this->_entity->saveEntityDataRemotely(Session::get('customer_id'),
                $entity_id,
                json_encode($got));
        }
        Session::set('other_id', serialize($saved));
    }

    private function loadData($step)
    {
        $this->data = new \stdClass();
        $this->data->step = $step;
        $this->data->titles = ['Mr' => 'Mr', 'Mrs' => 'Mrs', 'Ms' => 'Ms', 'Dr' => 'Dr', 'Prof' => 'Prof', 'Eng' => 'Eng'];
        $this->data->age_bracket = $this->__select(['1-18', '19-30', '31-40', '41-50', '51-59', '60-69', '70 or over']);
        $this->data->bands = [
            'band1' => 'Band 1: Covers you upto a limit of 250000 for accidental death',
            'band2' => 'Band 2:	Covers you upto a limit of 500000 for accidental death',
            'band3' => 'Band 3: Covers you upto a limit of 1000000 for accidental death',
            'band4' => 'Band 4: Covers you upto a limit of 2000000 for accidental death',
            'band5' => 'Band 5: Covers you upto a limit of 4000000 for accidental death',
            'band6' => 'Band 6: Covers you upto a limit of 8000000 for accidental death',
            'band7' => 'Band 7: Covers you upto a limit of 1000000 for accidental death',
        ];
        $this->data->towns = $this->__select(['Baragoi',
            'Bungoma', 'Busia', 'Butere', 'Dadaab', 'Diani Beach',
            'Eldoret', 'Embu', 'Garissa', 'Gede', 'Hola', 'Homa Bay',
            'Isiolo', 'Kajiado', 'Kakamega', 'Kakuma', 'Kapenguria', 'Kericho',
            'Kiambu', 'Kilifi', 'Kisii', 'Kisumu', 'Kitale', 'Lamu', 'Langata', 'Lodwar',
            'Lokichoggio', 'Loyangalani', 'Machakos', 'Malindi', 'Mandera', 'Maralal', 'Marsabit',
            'Meru', 'Mombasa', 'Moyale', 'Mumias', 'Muranga', 'Nairobi', 'Naivasha', 'Nakuru', 'Namanga',
            'Nanyuki', 'Naro Moru', 'Narok', 'Nyahururu', 'Nyeri', 'Ruiru', 'Shimoni', 'Takaungu', 'Thika',
            'Vihiga', 'Voi', 'Wajir', 'Watamu', 'Webuye', 'Wundanyi']);

        $this->data->years = $this->__years();
        $this->data->insurer = $this->__select(['AIG Kenya', 'Gateway', 'AAR Insurance Kenya Limited', 'A P A Insurance Limited', 'Africa Merchant Assurance Company Limited', 'Apollo Life Assurance Limited', 'AIG Kenya Insurance Company Limited', 'American Insurance Company (Kenya) Limited', 'Cannon Assurance Limited', 'Capex Life Assurance Company Limited', 'CFC Life Assurance Limited', 'CIC General Insurance Limited', 'CIC Life Assurance Limited', 'Continental Reinsurance Limited', 'Corporate Insurance Company Limited', 'Directline Assurance Company Limited', 'East Africa Reinsurance Company Limited', 'Fidelity Shield Insurance Company Limited', 'First Assurance Company Limited', 'G A Insurance Limited', 'Gateway Insurance Company Limited', 'Geminia Insurance Company Limited', 'ICEA LION General Insurance Company Limited', 'ICEA LION Life Assurance Company Limited', 'Intra Africa Assurance Company Limited', 'Invesco Assurance Company Limited', 'Kenindia Assurance Company Limited', 'Kenya Orient Insurance Limited',
            'Kenya Reinsurance Corporation Limited', 'Madison Insurance Company Kenya Limited', 'Mayfair Insurance Company Limited',
            'Mercantile Insurance Company Limited', 'Metropolitan Life Insurance Kenya Limited', 'Occidental Insurance Company Limited',
            'Old Mutual Life Assurance Company Limited', 'Pacis Insurance Company Limited', 'Pan Africa Life Assurance Limited', 'Phoenix of East Africa Assurance Company Limited', 'Pioneer Assurance Company Limited', 'Real Insurance Company Limited', 'Resolution Insurance Company Limited', 'Shield Assurance Company Limited', 'Takaful Insurance of Africa Limited', 'Tausi Assurance Company Limited', 'The Heritage Insurance Company Limited', 'The Jubilee Insurance Company of Kenya Limited', 'The Monarch Insurance Company Limited', 'Trident Insurance Company Limited', 'UAP Insurance Company Limited', 'UAP Life Assurance Limited', 'Xplico Insurance Company Limited']);

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
            $this->data->quotation = new AccidentQuotation();
        }
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
        $block = ['name_accident_personal_details', 'zebra_honeypot_accident_personal_details',
            'zebra_csrf_token_accident_personal_details', 'btnsubmit', 'zebra_honeypot_accident_other_cover_details',
            'name_accident_other_cover_details', 'zebra_csrf_token_accident_other_cover_details', 'btnSubmitSpecial',
            'name_accident_cover_details', 'zebra_honeypot_accident_cover_details', 'zebra_csrf_token_accident_cover_details'
        ];
        return array_except(Input::post(), $block);
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
}
