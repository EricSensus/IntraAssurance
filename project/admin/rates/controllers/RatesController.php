<?php

namespace Jenga\MyProject\Rates\Controllers;

use function DI\object;
use Jenga\App\Controllers\Controller;
use Jenga\App\Helpers\Help;
use Jenga\App\Html\Excel;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\HTML;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Rates\Models\RatesModel;
use Jenga\MyProject\Rates\Views\RatesView;

/**
 * Class RatesController
 * @property-read RatesView $view
 * @property-read RatesModel $model
 * @package Jenga\MyProject\Rates\Controllers
 */
class RatesController extends Controller
{
    /**
     * @var
     */
    private $rate_cats, $rate_types, $rates, $insurer;

    /**
     *
     */
    private function _init()
    {

        parent::__construct();
        $company = Input::get('__company');
        if (!empty($company)) {
            $this->model->where('insurer_id', Help::decrypt($company));
        }
        $this->rates = $this->model->show();
        $rate_types = $this->model->select('distinct(rate_type)')->show();
        $rate_cats = $this->model->select('distinct(rate_category)')->show();

        $this->rate_types = $this->getRateTypes($rate_types);
        $this->rate_cats = $this->getRateCategories($rate_cats);
    }

    /**
     *
     */
    private function setInsurer()
    {
        $this->insurer = Elements::call('Insurers/InsurersController')->getInsurerByFinder([
            'email_address' => 'info@jubilee.co.ke'
        ]);
        $this->companies = Elements::call('Insurers/InsurersController')->model->show();
        $haystack = [];
        foreach ($this->companies as $item) {
            $haystack[$item->id] = $item->name;
        }
        $this->companies = $haystack;
    }

    /**
     *
     */
    public function index()
    {

        $action = 'show';

        if (!empty(Input::get('action')) && !is_null(Input::get('action'))) {
            $action = Input::get('action');
        }

        $this->$action();
    }

    /**
     *
     */
    public function show()
    {
        $this->_init();
        $this->setViewData();
        $this->view->set('source', $this->_transformTable($this->rates));
        $this->view->generateTable();
    }

    /**
     *
     */
    public function showSetup()
    {
        $this->_init();
        $this->setViewData();
        $this->view->set('source', $this->_transformTable($this->rates));
        $this->view->generateSetupTable(true);
    }

    public function _transformTable($source)
    {
        $haystack = [];
        $instance = Elements::call('Insurers/InsurersController');
        foreach ($source as $item) {
            $insurers = (object)$instance->getInsurer($item->insurer_id);
            $item->company = $insurers->name;
            $haystack[] = $item;
        }
        return $haystack;
    }

    public function search()
    {
        $this->rates = $this->getParamValues(Input::post());
        $this->setViewData();

        $this->view->generateTable();
    }

    public function setViewData()
    {
        $this->view->set('source', $this->rates);
        $this->view->set('count', count($this->rates));
        $this->view->set('rate_types', $this->rate_types);
        $this->view->set('rate_cats', $this->rate_cats);
    }

    public function add()
    {
        $this->_init();
        $this->setInsurer();
        $this->view->showAddRateForm($this->rate_types, $this->rate_cats, $this->insurer, $this->companies);
    }

    public function getCompanies()
    {
        $dbinsurers = $this->model->table('insurers')->show();

        foreach ($dbinsurers as $insurer) {
            $insurer->official_name = ($insurer->official_name == '' ? 'Not Specified' : $insurer->official_name);
            $insurer->email_address = ($insurer->email_address == '' ? 'Not Specified' : $insurer->email_address);
            $insurer->rates = $this->countRates($insurer->id);
            $insurer->link = Help::encrypt($insurer->id);
            $insurers[] = $insurer;
        }
        $this->view->insurersTable($insurers);
    }

    /**
     * Get the number of quotes for a company
     * @param null $company
     * @return int
     */
    private function countRates($company = null)
    {
        if (!empty($company)) {
            $this->model->where('insurer_id', $company);
        }
        $models = $this->model->show();
        return count($models);
    }

    /**
     * Save a Rate
     */
    public function store()
    {
        $this->setInsurer();
        $state = 'error';
        $finder = ['rate_name' => Input::post('rate_name'), 'insurer_id' => Input::post('insurer_id')];
        if (!$this->model->rateExists($finder)) {
            if ($this->model->saveRate()) {
                $notice = 'Success! The Rate has been saved.';
                $state = 'success';
            } else {
                $notice = 'Failed! The Rate was not saved';
            }
        } else {
            $notice = 'Failed! The Rate Name already exists';
            $state = 'warning';
        }

        Session::flash('status', $state);
        Session::flash('message', $notice);
        Redirect::to(Input::post('destination'))->withNotice($notice);
    }

    public function import()
    {
        $uploadfolder = Input::post('upload_folder');
        $handler = new FileUpload('file_import');

        if ($handler->handleUpload($uploadfolder)) {

            $this->view->enable();
            $filename = ABSOLUTE_PATH . DS . 'tmp' . DS . $_FILES['file_import']['name'];

            $excel = new Excel();
            $doc = $excel->importDoc($filename);

            $doc->worksheet->name = $_FILES['file_import']['name'];
            $doc->worksheet->filename = $filename;

            $this->view->matchImportColumns($doc);
        }
    }

    public function export()
    {
        $this->view->disable();

        if (!is_null(Input::post('export'))) {

            $columns = ['id' => 'ID',
                'rate_name' => 'Rate Name',
                'rate_value' => 'Rate Value',
                'rate_type' => 'Rate Type',
                'rate_category' => 'Rate Category'
            ];

            $company = Elements::call('Insurers/InsurersController')->getInsurerByFinder([
                'email_address' => 'info@jubilee.co.ke'
            ]);

            $doc = new Excel($company->name . ' Rates Listing', Input::post('filename'));
            $doc->generateDoc($columns, $this->rates, Input::post('format'));
        }
    }

    public function printer()
    {

        if (!is_null(Input::post('printer'))) {

            $this->view->disable();

            $this->view->set('count', count($this->rates));
            $this->view->set('source', $this->rates);

            HTML::head();
            $this->view->generateTable();

            HTML::printPage();
        }
    }

    public function editModal($id)
    {
        $this->_init();
        $rate = $this->model->getRateData($id);
        $insurer = (object)Elements::call('Insurers/InsurersController')->getInsurer($rate->insurer_id);
        $this->view->showEditRateForm($rate, $this->rate_types, $this->rate_cats, $insurer);
    }

    public function update()
    {
        if ($this->model->updateRate()) {
            Session::flash('status', 'success');
            Session::flash('message', 'Success! The Rate has been updated');

            Redirect::withNotice('Success! The Rate has been updated', 'success')->to(Input::post('destination'));
        } else {
            Session::flash('status', 'error');
            Session::flash('message', 'Encountered an error');

            Redirect::withNotice('Failed! Encountered an error', 'error')->to(Input::post('destination'));
        }
    }

    /**
     * Delete Rates
     */
    public function delete()
    {

        if (!is_null(Input::post('delete'))) {

            $this->view->disable();
            $ids = Input::post('ids');

            foreach ($ids as $id) {
                $this->model->where('id', '=', $id)->delete();
            }

            $notice = 'The rate record(s) have been deleted';
            Session::set('status', 'success');
            Session::set('message', $notice);

            Redirect::withNotice($notice, 'success')
                ->to('/admin/rates/show');
        }
    }

    /**
     * Gets non empty filters
     * @param $params
     * @return array
     */
    public function getParamValues($params)
    {
        $rates = $this->model;

        $needed_fields = [
            'rate_name',
            'rate_value',
            'rate_type',
            'rate_category'
        ];

        if (count($params)) {
            foreach ($params as $field_name => $param) {
                if (!empty($param) && in_array($field_name, $needed_fields)) {
                    if ($field_name == 'rate_name')
                        $rates = $rates->where($field_name, 'LIKE', "%$param%");
                    else
                        $rates = $rates->where($field_name, $param);
                }
            }
        }

        return $rates->show();
    }

    public function getRateTypes($rate_types)
    {
        foreach ($rate_types as $type) {
            $r_types[$type->rate_type] = $type->rate_type;
        }

        return $r_types;
    }

    public function getRateCategories($rate_cats)
    {
        foreach ($rate_cats as $cat) {
            $r_cats[$cat->rate_category] = $cat->rate_category;
        }

        return $r_cats;
    }

    /**
     * Load the rates profile for the company
     * @param $company
     * @return object
     */
    public function getAllRatesFor($company)
    {
        return $this->model->where('insurer_id', $company)->show();
    }

    /**
     * Get the rates
     * @param $tsi
     * @param $rate_name
     * @param $category
     * @param null $rate_type
     * @return float|int
     */
    public function getRates($tsi, $rate_name, $category, $rate_type = null)
    {
        if (!($this->rates instanceof \stdClass)) {
            $this->rates = new \stdClass();
        }
        if (empty($rate_type)) {
            $rate = $this->model->where('rate_category', $category)->where('rate_name', $rate_name)->first();
        } else {
            $rate = $this->model->where('rate_category', $category)->where('rate_name', $rate_name)->where('rate_type', $rate_type)->first();
        }

        if ($rate->rate_type == 'Percentage') {
            $computed_value = (($tsi * $rate->rate_value) / 100);
        } elseif ($rate->rate_type == 'Fixed') {
            $computed_value = $rate->rate_value;
        }
        $this->rates->{$this->strClean($category . " " . $rate_name)} = ['rate' => $rate->rate_value, 'type' => $rate->rate_type];
        return $computed_value;
    }

    /**
     * Get return rate
     * @param $rate_name
     * @param $category
     * @return string
     */
    public function getReturnRate($rate_name, $category)
    {
        $rate_model = $this->model->where('rate_category', $category)->where('rate_name', $rate_name)->first();
        return ($rate_model->rate_type == 'Percentage' ? $rate_model->rate_value . '%' : $rate_model->rate_value);
    }


    private function strClean($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        return (empty($text)) ? 'n-a' : $text;
    }
}