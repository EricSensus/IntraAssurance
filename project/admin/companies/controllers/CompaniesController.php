<?php

namespace Jenga\MyProject\Companies\Controllers;

use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Companies\Models\CompaniesModel;
use Jenga\MyProject\Companies\Views\CompaniesView;
use Jenga\MyProject\Elements;

/**
 * Class CompaniesController
 * @property-read CompaniesView $view
 * @property-read CompaniesModel $model
 * @package Jenga\MyProject\Companies\Controllers
 */
class CompaniesController extends Controller
{

    public function index()
    {

        if (is_null(Input::get('action')) && is_null(Input::post('action'))) {

            $action = 'getCompanies';
        } else {

            if (!is_null(Input::get('action')))
                $action = Input::get('action');

            elseif (!is_null(Input::post('action')))
                $action = Input::post('action');
        }

        $this->$action();
    }

    public function getCompanies()
    {

        $companies = $this->model->getCompanies();

        $this->view->set('count', count($companies));
        $this->view->set('source', $companies);

        $this->view->generateMainTable();
    }

    public function getInsurers()
    {

        $dbinsurers = $this->model->table('insurers')->show();

        foreach ($dbinsurers as $insurer) {

            $insurer->official_name = ($insurer->official_name == '' ? 'Not Specified' : $insurer->official_name);
            $insurer->email_address = ($insurer->email_address == '' ? 'Not Specified' : $insurer->email_address);

            $insurers[] = $insurer;
        }

        $this->view->insurersTable($insurers);
    }

    public function getInsurer()
    {

        if (!is_null(Input::get('id'))) {
            $id = Input::get('id');
            $insurer = $this->model->table('insurers')->find($id)->data;
        }

        $this->view->insurerForm($insurer);
    }

    public function saveInsurer()
    {

        if (Input::post('id') == '') {
            $insurer = $this->model->table('insurers');
        } else {
            $insurer = $this->model->table('insurers')->find(Input::post('id'));
        }

        $insurer->name = Input::post('name');
        $insurer->official_name = Input::post('official_name');
        $insurer->email_address = Input::post('email_address');

        $save = $insurer->save();

        if (!array_key_exists('ERROR', $save)) {
            Redirect::withNotice('The Insurer details have been saved', 'success')
                ->to(Input::post('destination'));
        }
    }

    public function deleteInsurer()
    {

        if (!is_null(Input::get('id'))) {
            $this->model->table('insurers')->where('id', '=', Input::get('id'))->delete();
        } elseif (!is_null(Input::post('ids'))) {

            foreach (Input::post('ids') as $id) {
                $this->model->table('insurers')->where('id', '=', $id)->delete();
            }
        }

        $destination = Elements::call('Navigation/NavigationController')->getUrl('setup');
        Redirect::withNotice('The Insurer details have been deleted', 'success')
            ->to($destination);
    }

    public function ownCompany($return = FALSE)
    {

        $company = $this->model->table('own_company')->first();

        if ($return == FALSE) {
            $this->view->companyForm($company);
        } else {
            return $company;
        }
    }

    public function saveOwnDetails()
    {

        $this->view->disable();

        if (Input::post('id') != '') {
            $company = $this->model->table('own_company')->find(Input::post('id'));
        } else {
            $company = $this->model->table('own_company');
        }

        $company->name = Input::post('name');
        $company->email_address = Input::post('email_address');
        $company->telephone = Input::post('telephone');
        $company->postal_address = Input::post('postal_address');

        $physical = [
            'location' => Input::post('location'),
            'zipcode' => Input::post('zipcode'),
            'citycounty' => Input::post('citycounty'),
            'country' => Input::post('country')
        ];

        $company->physical_details = json_encode($physical);

        $save = $company->save();

        if (!array_key_exists('ERROR', $save)) {
            Redirect::withNotice('The company details have been saved', 'success')
                ->to(Input::post('destination'));
        }
    }
}
