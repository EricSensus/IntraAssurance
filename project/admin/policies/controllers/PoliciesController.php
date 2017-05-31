<?php

namespace Jenga\MyProject\Policies\Controllers;

use Carbon\Carbon;
use Jenga\App\Core\App;
use Jenga\App\Helpers\Help;
use Jenga\App\Html\Excel;
use Jenga\App\Project\Security\User;
use Jenga\App\Request\Session;
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Helpers\FileUpload;
use Jenga\App\Views\Notifications;
use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Facade\Sanitize;
use Jenga\MyProject\Agents\Controllers\AgentsController;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Notifications\Controllers\NotificationsController;
use Jenga\MyProject\Policies\Models\PoliciesModel;
use Jenga\MyProject\Policies\Views\PoliciesView;
use Jenga\MyProject\Quotes\Controllers\QuotesController;
use Jenga\MyProject\Services\Charts;
use Jenga\MyProject\Users\Controllers\UsersController;

/**
 * Class PoliciesController
 * @package Jenga\MyProject\Policies\Controllers
 * @property-read  PoliciesView $view
 * @property-read  PoliciesModel $model
 */
class PoliciesController extends Controller
{
    /**
     * @var UsersController
     */
    private $userCtrl;
    /**
     * @var CustomersController
     */
    private $customerCtrl;
    /**
     * @var AgentsController
     */
    private $agentCtrl;
    /**
     * @var NotificationsController
     */
    private $notice;
    protected $own_company;

    public function index()
    {

        if (is_null(Input::get('action')) && is_null(Input::post('action'))) {

            $action = 'show';
        } else {

            if (!is_null(Input::get('action')))
                $action = Input::get('action');

            elseif (!is_null(Input::post('action')))
                $action = Input::post('action');
        }
        //method is referenced using alias
//        if($this->user()->can('edit','policies')){
        $this->$action();
//        }
    }

    public function initData()
    {
        $this->own_company = Elements::call('Companies/CompaniesController')->ownCompany(true);
        $this->notice = App::get('notice');
        $this->customerCtrl = Elements::call('Customers/CustomersController');
        $this->userCtrl = Elements::call('Users/UsersController');
    }

    private function _translatePolicies($rawpolicies, $use = 'internal', $mypolicy = false)
    {
        if (is_array($rawpolicies)) {

            foreach ($rawpolicies as $policy) {

                $newpolicy = $this->model->createEmpty();

                $insurer = Elements::load('Insurers/InsurersController@getInsurer', ['id' => $policy->insurers_id])['name'];

                if ($use == 'internal')
                    $txtinsurer = Sanitize::shorten($insurer, 20);
                else
                    $txtinsurer = $insurer;

                if ($use == 'internal')
                    $newpolicy->id = $policy->id;

                $newpolicy->created = date('d-M-y', $policy->datetime);
                $newpolicy->policyno = $policy->policy_number;
                $newpolicy->issuedate = ($policy->issue_date == 0 ? 'Not Allocated' : date('d M Y', $policy->issue_date));
                $newpolicy->insurer = $txtinsurer;
                $newpolicy->validity = date('d M Y', $policy->start_date) . ' - ' . date('d M Y', $policy->end_date);

                if ($use == 'internal')
                    $newpolicy->customers_id = $policy->customers_id;

                $newpolicy->customer = Elements::load('Customers/CustomersController@find', ['id' => $policy->customers_id])->name;
                $newpolicy->product = Elements::load('Products/ProductsController@getProduct', ['id' => $policy->products_id])['name'];
                $newpolicy->status = ($policy->status == '' ? 'Not Issued' : $policy->status);

                $newpolicy->premium = $policy->currency_code . ' ' . number_format($policy->amount, 2);

                if ($policy->status == 'issued' && $use == 'internal') {
                    $newpolicy->image = '<img ' . Notifications::tooltip('Policy issued') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/done_icon.png" width="20" />';
                } elseif ($use == 'internal') {
                    $newpolicy->image = '<a href="' . SITE_PATH . '/admin/policies/processissue/' . $policy->id . '" >'
                        . '<img ' . Notifications::tooltip('Click to issue policy') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/issue_policy_icon.png" width="20" />'
                        . '</a>';
                }
                $newpolicy->actions = '<i class="moreactions fa fa-bars fa-lg" aria-hidden="true"></i>';

                $policies[] = $newpolicy;
            }
        } else {

            $policy = $rawpolicies;

            $newpolicy = $this->model->createEmpty();

            $insurer = Elements::load('Insurers/InsurersController@getInsurer', ['id' => $policy->insurers_id])['name'];
            $txtinsurer = Sanitize::shorten($insurer, 20);

            $newpolicy->id = $policy->id;
            $newpolicy->policyno = $policy->policy_number;
            $newpolicy->issuedate = ($policy->issue_date == 0 ? 'Not Allocated' : date('d M Y', $policy->issue_date));
            $newpolicy->insurer = $txtinsurer;
            $newpolicy->validity = date('d M Y', $policy->start_date) . ' - ' . date('d M Y', $policy->end_date);
            $newpolicy->customer = Elements::load('Customers/CustomersController@find', ['id' => $policy->customers_id])->name;
            $newpolicy->product = Elements::load('Products/ProductsController@getProduct', ['id' => $policy->products_id])['name'];
            $newpolicy->status = ($policy->status == '' ? 'Not Issued' : $policy->status);

            if ($policy->status == 'issued') {
                $newpolicy->image = '<a href="' . SITE_PATH . '/admin/policies/processissue/' . $policy->id . '" >'
                    . '<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/issue_policy_icon.png" width="20" />'
                    . '</a>';
            } else {
                $newpolicy->image = '<a href="' . SITE_PATH . '/admin/policies/processissue/' . $policy->id . '" >'
                    . '<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/done_icon.png" width="20" />'
                    . '</a>';
            }

            $policies = $newpolicy;
        }
        return $policies;
    }

    /**
     * @jacl(action="read", alias="Read Policies")
     */
    public function show($search = NULL)
    {

        if (is_null($search)) {
            if($this->user()->is('agent')){
                $rawpolicies = $this->model->select(TABLE_PREFIX . 'policies.*, '. TABLE_PREFIX . 'customers.insurer_agents_id')
                    ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id", 'LEFT')
                    ->where('insurer_agents_id', $this->user()->insurer_agents_id)
                    ->get();
            } else {
                $rawpolicies = $this->model->show();
            }
        } else {
            $results = $this->model->search($search);

            $this->set('condition', $results['condition']);
            $rawpolicies = $results['result'];
        }

        $policies = $this->_translatePolicies($rawpolicies);

        $this->set('count', $this->model->count());
        $this->set('source', $policies);
        $this->set('search', $this->_searchform());

        $this->view->generateTable();
    }

    public function getUnprocessedPolicies()
    {
        if($this->user()->is('agent')){
            $dbpolicies = $this->model->select(TABLE_PREFIX . 'policies.*, '. TABLE_PREFIX . 'customers.insurer_agents_id')
                ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id", 'LEFT')
                ->where('insurer_agents_id', $this->user()->insurer_agents_id)
                ->where('status', '')
                ->get();
        } else {
            $dbpolicies = $this->model->where('status', '')->show();
        }

        $policies = $this->_translatePolicies($dbpolicies);

        $this->set('count', $this->model->count());
        $this->set('source', $policies);
        $this->set('search', $this->_searchform());

        $this->view->generateMiniTable('unprocessed');
    }

    public function getExpiringPolicies()
    {

        $now = time();
        $later = strtotime("+30 days");

        if($this->user()->is('agent')) {
            $dbpolicies = $this->model->select(TABLE_PREFIX . 'policies.*, '. TABLE_PREFIX . 'customers.insurer_agents_id')
                ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "policies.customers_id")
                ->where('insurer_agents_id', $this->user()->insurer_agents_id)
                ->where('end_date', 'BETWEEN', [$now, $later])
                ->get();
        } else {
            $dbpolicies = $this->model->where('end_date', 'BETWEEN', [$now, $later])->show();
        }

        $policies = $this->_translatePolicies($dbpolicies);

        $this->set('count', $this->model->count());
        $this->set('source', $policies);
        $this->set('search', $this->_searchform());

        $this->view->generateMiniTable('expiring');
    }

    public function add()
    {
        if (!is_null(Input::get('id'))) {

            $customer = Elements::call('Customers/CustomersController')->getCustomerById(Input::get('id'), 'raw');
            $quotes = Elements::call('Quotes/QuotesController')->getQuotesByCustomer($customer->id);

            //get product
            $product = Elements::call('Products/ProductsController');

            //get insurer
            $insurer = Elements::call('Insurers/InsurersController');

            //process quotes
            if (!is_null($quotes)) {

                foreach ($quotes as $quote) {

                    $amounts = json_decode($quote->amount);

                    foreach ($amounts as $insurerid => $price) {

                        $list[] = ['id' => $quote->id . ':' . $insurerid . ':' . $price,
                            'product' => $product->getProduct($quote->product_id, 'array')['name'],
                            'datetime' => $quote->datetime,
                            'insurer' => $insurer->getInsurer($insurerid)['name'],
                            'amount' => $price];
                    }
                }

                $customer->quotes = $list;
            } else {
                $customer->quotes = NULL;
            }
        }

        $this->view->addPolicyForm($customer);
    }

    public function getOldCustomer()
    {
        $this->view->disable();
        $name = Input::request('query');
        $customers = $this->model->table('customers')->where('name', 'LIKE', '%' . $name . '%')->show();
        if (count($customers) >= 1) {
            foreach ($customers as $customer) {
                $list[] = ['value' => ucfirst($customer->name), 'data' => $customer->id];
            }
        } else {
            $list[] = ['value' => 'No Customers Found', 'data' => NULL];
        }
        $full_list['query'] = 'Unit';
        $full_list['suggestions'] = $list;
        echo json_encode($full_list);
    }

    public function edit()
    {
        $policy = $this->model->find(Input::get('id'))->data;

        //get the customer details from the Customers element
        $policy->customer = Elements::call('Customers/CustomersController')->find($policy->customers_id);

        $quote = $this->model->getDataFromQuote($policy->customer_quotes_id);

        $entity_data_ids = $quote->customer_entity_data_id;
        $this->view->set('other_covers_no', count($entity_data_ids));

        //get Entities data
        $entity = Elements::call('Entities/EntitiesController')->getCustomerEntity($entity_data_ids, 'id');

        $policy->refno = $quote->refno;
        $policy->coverage = [
            'product' => $quote->product_info,
            'entity' => $entity,
            'amounts' => $quote->amount
        ];

        $policy->product = Elements::call('Products/ProductsController')->getProduct($policy->products_id);
        $policy->insurer = Elements::call('Insurers/InsurersController')->getInsurer($policy->insurers_id);
        $policy->dategenerated = date('d F Y', $policy->datetime);
        $policy->premium = $quote->amount;

        $this->view->set('id', Input::get('id'));
        $policy_docs = $this->model->getPolicyDocs(Input::get('id'));
        $this->view->set('docs_count', $policy_docs['count']);

        if ($policy_docs['count']) {
            foreach ($policy_docs['docs'] as $key => $doc) {
                $document = Elements::call('Documents/DocumentsController')->model->find($doc->documents_id)->data;

                $document->time = date('d F, Y H:i', $document->datetime);
                $document->policy_id = Input::get('id');

                if (count($document) > 0) {
                    $doclist[] = $document;
                }
            }
        }

        $this->view->set('source', $doclist);
        $this->view->set('product_alias', $policy->product['product_alias']);
        $this->view->editPolicy($policy);
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

    public function integrateImport()
    {

        $this->view->disable();

        $columns = Input::post('columns');
        $db = $this->model;

        //reimport document
        $excel = new Excel();
        $doc = $excel->importDoc(Input::post('filepath'));

        //starts at 2 to jump column titles row
        $errorlog = [];
        for ($r = 2; $r <= $doc->worksheet->rowcount; $r++) {

            for ($c = 1; $c <= count($columns); $c++) {

                $colcount = ($c - 1);
                $coldata = explode(',', $columns[$colcount]);
                $colid = $coldata[0];
                $dbcol = $coldata[1];

                //get columns that havent been skipped
                if (!is_null(Input::post('importselect_' . $colid)) && Input::post('importselect_' . $colid) != '') {

                    $pickedcol = Input::post('importselect_' . $colid);

                    switch ($dbcol) {

                        case 'customer_name':
                            //process customer name
                            $customername = $doc->worksheet->rows[$pickedcol . ',' . $r];

                            $customerelm = Elements::call('Customers/CustomersController');
                            $customer = $customerelm->getCustomerByName($customername, 'raw');

                            if (count($customer) == 0) {

                                //if the customer doesn't exist create new record
                                $customermdl = $customer->model;

                                $customermdl->name = $customername;
                                $customermdl->regdate = time();
                                $customermdl->insurer_agents_id = 0;

                                //save the customer
                                $save = $customermdl->save();

                                $customer_record_id = $save['last_altered_row'];
                            } else {

                                //get the customer record
                                $customer_record_id = $customer[0]->id;
                            }

                            $db->customers_id = $customer_record_id;
                            break;

                        case 'product_name':

                            $productname = $doc->worksheet->rows[$pickedcol . ',' . $r];

                            //get the products_id
                            $productelm = Elements::call('Products/ProductsController');
                            $products = $productelm->model->where('name', 'LIKE', '%' . $productname . '%')->show();

                            if (count($products) >= 1) {

                                $db->products_id = $products[0]->id;
                            } else {
                                $errorlog['products'][$pickedcol . ',' . $r] = 'No products named - ' . $productname . ' in the listed products"';
                            }
                            break;

                        case 'insurer_name':

                            $insurer = $doc->worksheet->rows[$pickedcol . ',' . $r];

                            //get the insurer
                            $insurerelm = Elements::call('Insurers/InsurersController');
                            $insurers = $insurerelm->model->where('name', 'LIKE', $insurer)->show();

                            if (count($insurers) >= 1) {

                                $db->insurers_id = $insurers[0]->id;
                            } else {
                                $productname = null;
                                $errorlog['insurers'][$pickedcol . ',' . $r] = 'No insurer named - ' . $productname . ' in the listed insurance companies"';
                            }
                            break;

                        case 'issue_date':
                        case 'start_date':
                        case 'end_date':

                            $date = $doc->worksheet->rows[$pickedcol . ',' . $r];
                            $db->{$dbcol} = strtotime($date);
                            break;

                        default:
                            //create the insert row
                            $db->{$dbcol} = $doc->worksheet->rows[$pickedcol . ',' . $r];
                            break;
                    }
                }

                $db->regdate = time();
            }

            $save = $db->save();

            if (array_key_exists('ERROR', $save)) {
                $errorlog['database'][$pickedcol . ',' . $r] = $save['ERROR'];
            }
        }

        if (count($errorlog) == 0) {

            Redirect::withNotice('The customer records have been inserted', 'success')
                ->to(Url::route('/admin/customers/{action}'));
        } else {

            $this->view->showImportErrors($errorlog);
        }
    }

    public function export()
    {

        $this->view->disable();
        $dbpolicies = $this->model->getPolicies();

        $policies = $this->_translatePolicies($dbpolicies, 'export');
        $columns = [
            'policyno' => 'Policy Number',
            'issuedate' => 'Issue Date',
            'insurer' => 'Insurer',
            'validity' => 'Validity',
            'customer' => 'Customer',
            'product' => 'Product',
            'status' => 'Status'
        ];

        $company = $this->model->table('own_company')->first();
        $doc = new Excel($company->name . ' Policies Listing', Input::post('filename'));

        $doc->generateDoc($columns, $policies, Input::post('format'));
    }

    public function printer()
    {

        $this->view->disable();

        $dbpolicies = $this->model->getPolicies();
        $policies = $this->_translatePolicies($dbpolicies, 'printer');

        $this->view->set('count', count($policies));
        $this->view->set('source', $policies);

        HTML::head();
        $this->view->generateTable();

        HTML::printPage();
    }

    public function savePolicyEdit()
    {

        $policy = $this->model->find(Input::post('id'));

        $policy->policy_number = Input::post('policynumber');
        $policy->issue_date = strtotime(Input::post('issuedate'));
        $policy->start_date = strtotime(Input::post('startdate'));
        $policy->end_date = strtotime(Input::post('enddate'));

        $save = $policy->save();

        if (!array_key_exists('ERROR', $save)) {

            $policyurl = Elements::call('Navigation/NavigationController')->getUrl('policies');

            Redirect::withNotice('The policy changes have been added', 'success')
                ->to($policyurl);
        } else {

            echo $this->model->getLastQuery();
        }
    }

    public function search()
    {

        $this->show(Input::post());
    }

    public function getCustomer()
    {

        $this->view->disable();
        $name = Input::request('query');

        $customers = $this->model->table('customers')->where('name', 'LIKE', '%' . $name . '%')->show();
        $list = [];
        if (count($customers) >= 1) {

            foreach ($customers as $customer) {

                $list[] = ['text' => ucfirst($customer->name), 'id' => $customer->id];
            }
        }
        $full_list['query'] = $name;
        $full_list['results'] = $list;
        echo json_encode($full_list);
    }

    public function getQuotes()
    {

        $this->view->disable();

        //get the customers quotes
        $id = Input::request('id');
        $quotes = $this->model->table('customer_quotes')
            ->where('customers_id', '=', $id)
            ->where('status', 'IN', ['new', 'pending', 'Accepted'])
            ->show();

        //get product
        $product = Elements::call('Products/ProductsController');

        //get insurer
        $insurer = Elements::call('Insurers/InsurersController');

        if (count($quotes) >= 1) {
            foreach ($quotes as $quote) {

                $amounts = json_decode($quote->amount);
                $amounts = json_decode($quote->amount);
                $use_amount = null;
                foreach ($amounts as $struct) {
                    if ($struct->chosen) {
                        $use_amount = $struct;
                        break;
                    }
                }
//
//                foreach($amounts as $insurerid => $price){
//
////                    $list[] = ['id' => $quote->id.':'.$insurerid.':'.$price,
////                               'product' => $product->getProduct($quote->products_id,'array')['name'],
////                               'datetime' => $quote->datetime,
////                               'insurer' => $insurer->getInsurer($insurerid)['name'],
////                               'amount' => $price];
                $list[] = [
                    'id' => $quote->id,
                    'datetime' => $quote->datetime,
                    'amount' => $use_amount->total,
                    'insurer' => $insurer->getInsurer($use_amount->insurer_id)['name']
                ];
//                }
            }

            $selectquotelist = $this->view->getQuoteList($list);
            $return = [
                'status' => true,
                'content' => $selectquotelist
            ];
        } else {
            //get agents
            $agents = Elements::call('Agents/AgentsController')->retrieveAgents();
            foreach ($agents as $agent) {
                $agentlist[$agent->id] = $agent->names;
            }

            //get products
            $products = Elements::call('Products/ProductsController')->getProduct();
            foreach ($products as $product) {
                $productlist[$product->id] = $product->name;
            }

            //get insurers
            $insurers = Elements::call('Insurers/InsurersController')->getInsurer();
            foreach ($insurers as $insurer) {
                $insurerlist[$insurer->id] = $insurer->name;
            }

            $return = [
                'status' => false,
                'content' => Elements::call('Quotes/QuotesController')->view->addQuoteForPolicy($agentlist, $productlist, $insurerlist, $id)
            ];
        }

        $return['customer_id'] = $id;
        echo json_encode($return);
    }

    /**
     * @acl\role admin
     * @param null $offer
     * @param null $id
     */
    public function createPolicy($offer = null, $id = null)
    {
        if (!is_null(Input::get('id'))) {
            $quoteid = Input::get('id');
        } elseif (!is_null($offer)) {

            $quoteinfo = explode(':', $offer);
            $quoteid = $quoteinfo[0];

            $insurerid = $quoteinfo[1];
            $price = $quoteinfo[2];
        } else {
            $quoteid = (empty($id)) ? Input::post('quotes') : $id;
        }
        $data = $this->model->getDataFromQuote($quoteid);
        $policy = $this->model->createEmpty();

        if (!is_null($offer)) {
            $policy->offer = $offer;
        }
        $amounts = json_decode($data->amount);
        $use_amount = null;
        foreach ($amounts as $struct) {
            if ($struct->chosen) {
                $use_amount = $struct;
                break;
            }
        }
        if (!isset($price))
            $price = $use_amount->total;

        if (!isset($insurerid))
            $insurerid = $use_amount->insurer_id;

        //get the customer details from the Customers element
        $policy->customer = Elements::load('Customers/CustomersController@find', ['id' => $data->customers_id]);

        //get product details from Product element
        $policy->product = Elements::load('Products/ProductsController@getProduct', ['id' => $data->products_id]);

        //get insurer details from Insurer element
        $policy->insurer = Elements::call('Insurers/InsurersController')->getInsurer($insurerid);

        //get Entities element
        $entity = Elements::call('Entities/EntitiesController')->getCustomerEntity($data->customer_entity_data_id, 'id');

        //get the customer quote id
        $policy->quoteid = $quoteid;

        // get product info
        $product_info = json_decode($data->product_info);
        $policy->startdate = (!is_null($product_info->coverstart) ? date('d F Y', strtotime($product_info->coverstart)) : '');
        $policy->enddate = (!is_null($product_info->coverend) ? date('d F Y', strtotime($data->coverend)) : '');
        $policy->dategenerated = date('d F Y', (!is_null($data->datetime) ? $data->datetime : time()));

        $policy->no = '--not issued--';

        //get quote status list from Quote element
        $statuslist = Elements::call('Quotes/QuotesController')->statuslist;
        $policy->quotestatus = $statuslist[$data->status];

        $policy->coverage = [
            'product' => $data->product_info,
            'entity' => $entity,
            'amounts' => $use_amount
        ];
        $policy->currency_code = 'ksh';
        $policy->amount = $price;
        $policy->premium = 'ksh ' . number_format($price, 2);
        $this->view->set('product_alias', $policy->product['product_alias']);
        $this->view->addPolicy($policy);
    }

    /**
     * Save a policy from quote
     * @param \stdClass $quote
     * @return bool
     */
    public function saveAcceptedQuotePolicy($quote)
    {

        $this->view->disable();
        $policy = $this->model;
        $policy->policy_number = '--not issued--';
        $policy->customers_id = $quote->customers_id;
        $policy->issue_date = null;
        $policy->start_date = time();
        $policy->end_date = strtotime('+1 year', $policy->start_date);
        $policy->datetime = time();
        $amounts = json_decode($quote->amount);
        $use_amount = null;
        foreach ($amounts as $struct) {
            if ($struct->chosen) {
                $use_amount = $struct;
                break;
            }
        }
        $policy->insurers_id = $use_amount->insurer_id;
        $policy->products_id = $quote->products_id;
        $policy->customer_quotes_id = $quote->id;

        $statuslist = Elements::call('Quotes/QuotesController')->statuslist;

        $policy->status = $statuslist['policy_unprocessed'];
        $policy->currency_code = 'Ksh';
        $policy->amount = $use_amount->total;
        //update quote status
      //  Elements::call('Quotes/QuotesController')->saveStatus($policy->customer_quotes_id, null, 'policy_unprocessed');
        $policy->save();
//        exit;
        if ($policy->hasNoErrors()) {
            return true;
        }
        return false;
    }

    public function savePolicy()
    {
        $this->view->disable();
        $policy = $this->model;

        $policy->policy_number = Input::post('policyno');
        $policy->customers_id = Input::post('customers_id');

        $policy->issue_date = 0;
        $policy->start_date = strtotime(Input::post('startdate'));
        $policy->end_date = strtotime(Input::post('enddate'));
        $policy->datetime = time();

        $policy->insurers_id = Input::post('insurers_id');
        $policy->products_id = Input::post('products_id');
        $policy->customer_quotes_id = Input::post('customer_quotes_id');

        $statuslist = Elements::call('Quotes/QuotesController')->statuslist;

        $policy->status = $statuslist['policy_pending'];
        $policy->currency_code = Input::post('code');
        $policy->amount = Input::post('amount');

        //update quote status
        Elements::call('Quotes/QuotesController')->saveStatus($policy->customer_quotes_id, Input::post('offer'), 'policy_created');

        $policy->save();

//        dump($this->model->errors());exit;
        if ($policy->hasNoErrors()) {
            Redirect::withNotice('The policy has been saved')
                ->to(Url::base() . '/admin/policies/uploaddocs/' . $policy->last_altered_row);
//                    ->to(Url::base().'/admin/policies/issuepolicy/'.$policy->last_altered_row);
        }
    }

    /**
     * Start the policy creation for a recently accepted quote
     */

    public function startPolicyForAcceptedQuote()
    {
        $this->view->disable();
        $policy = $this->model;

        $policy->policy_number = '--not-is';
        $policy->customers_id = Input::post('customers_id');

        $policy->issue_date = 0;
        $policy->start_date = strtotime(Input::post('startdate'));
        $policy->end_date = strtotime(Input::post('enddate'));
        $policy->datetime = time();

        $policy->insurers_id = Input::post('insurers_id');
        $policy->products_id = Input::post('products_id');
        $policy->customer_quotes_id = Input::post('customer_quotes_id');

        $statuslist = Elements::call('Quotes/QuotesController')->statuslist;

        $policy->status = $statuslist['policy_pending'];
        $policy->currency_code = Input::post('code');
        $policy->amount = Input::post('amount');

        //update quote status
        Elements::call('Quotes/QuotesController')->saveStatus($policy->customer_quotes_id, Input::post('offer'), 'policy_created');

        $policy->save();

//        dump($this->model->errors());exit;
        if ($policy->hasNoErrors()) {
            Redirect::withNotice('The policy has been saved')
                ->to(Url::base() . '/admin/policies/uploaddocs/' . $policy->last_altered_row);
//                    ->to(Url::base().'/admin/policies/issuepolicy/'.$policy->last_altered_row);
        }
    }

    /**
     * Upload related policy documents
     */
    public function uploadDocs()
    {
        $policy_docs = $this->model->getPolicyDocs(Input::get('id'));

        $this->view->set('id', Input::get('id'));
        $this->view->set('docs_count', $policy_docs['count']);

        if ($policy_docs['count']) {
            foreach ($policy_docs['docs'] as $key => $doc) {
                $document = Elements::call('Documents/DocumentsController')->model->find($doc->documents_id)->data;

                $document->time = date('d F, Y H:i', $document->datetime);
                $document->policy_id = Input::get('id');

                if (count($document) > 0) {
                    $doclist[] = $document;
                }
            }
        }

        $this->view->set('source', $doclist);
        $this->view->uploadAdditionalDocs();
    }

    public function deletedoc()
    {
        $policy_id = Input::get('policy_id');
        $document_id = Input::get('id');

        //delete document from Documents element
        $doc = Elements::call('Documents/DocumentsController')->deleteDoc($document_id);

        if ($doc !== FALSE) {
            Redirect::withNotice('The linked policy document has been deleted')
                ->to('/admin/policies/uploaddocs/' . $policy_id);
        }
    }

    public function issuePolicy()
    {

        $policy = $this->model->find(Input::get('id'))->data;

        $this->view->issuePolicy($policy);
    }

    public function processIssue()
    {
        $policy = $this->model->find(Input::get('id'))->data;
        $this->view->issuePolicy($policy, 'process-issue');
    }

    public function saveIssue()
    {
        $this->view->disable();
        $this->initData();

        $policy = $this->model->find(Input::post('policyid'));
        if (array_key_exists('btnsubmit', Input::post())) {
            if (Input::post('sendemail') == 'yes')
                $this->sendMailNotification(Input::post('policyid'));

            $policy->policy_number = Input::post('policynumber');
            $policy->issue_date = strtotime(Input::post('issuedate'));
            $policy->status = 'issued';
            $policy->save();

            if ($policy->hasNoErrors()) {
                // get the customer attached
                $customer = $this->customerCtrl->getCustomerById($policy->customers_id, 'null');

                // get the user attached
                $user = $this->userCtrl->getUserByCustomerId($customer->id);

                // message
                $content = '<p>Dear <b>' . $customer->name . '</b></p>';
                $content .= '<p>The policy with Policy<b># ' . $policy->policy_number . '</b> has been issued.</p>';

                // subject
                $subject = 'Policy No: ' . $policy->policy_number . ' has been issued';

                // for every issued policy, inform the respective customer
                $this->notice->sendAsEmail([$customer->email_address => $customer->name], $content, $subject, [
                    $this->own_company->email_address => $this->own_company->name
                ]);

                // add notification
                $message = 'A policy with policy# ' . $policy->policy_number . ' has been issued.';
                $this->notice->add($message, 'customer', $user->id, 'policies');
                $this->notice->add($message, 'admin', $user->id, 'policies');

                if (Input::post('ajax') != 'yes') {
                    Redirect::withNotice('The policy details have been saved', 'success')
                        ->to('/admin/policies');
                } else {

                    $msg = new Notifications();
                    $msg->setMessage('success', 'The policy details have been saved');

                    Notifications::Alert('The policy details have been saved', 'success');
                }
            }
        } else {

            Redirect::withNotice('The policy to be processed later', 'notice')
                ->to('/admin/policies');
        }
    }

    public function issueBatch()
    {
        $ids = Input::post('ids');

        foreach ($ids as $id) {

            $policies[] = $this->model->find($id)->data;
        }

        $this->view->batchPolicy($policies);
    }

    /**
     * Deletes one or more selected policies(Batch deletion)
     */
    public function delete()
    {
        $ids = Input::post('ids');

        foreach ($ids as $id) {
            // get the policy data
            $policy = $this->model->getPolicy($id);

            // send delete notification
            $this->deleteNotification($policy);

            // delete from db
            $this->model->where('id', '=', $id)->delete();
        }

        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'policies']);
        Redirect::withNotice('The policies have been deleted ', 'success')
            ->to($url);
    }

    /**
     * Delete a single policy
     * @return void
     */
    public function deleteSingle(){
        // set the necessary data to be used
        $this->initData();
        $policy_id = Input::post('id');

        // send delete notification
        $this->deleteNotification($this->model->find($policy_id));

        $this->model->where('id', $policy_id)->delete();
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'policies']);
        Redirect::withNotice('The policy has been deleted ', 'success')
            ->to($url);
    }

    public function deleteNotification($policy){
        // get the customer attached
        $customer = $this->customerCtrl->getCustomerById($policy->customers_id, 'null');

        // get the user attached
        $user = $this->userCtrl->getUserByCustomerId($customer->id);

        // message
        $content = '<p>Dear <b>' . $customer->name . '</b></p>';
        $content .= '<p>The policy with Policy<b># ' . $policy->policy_number . '</b> has been deleted.</p>';

        // subject
        $subject = 'Policy No: ' . $policy->policy_number . ' has been deleted';

        // for every deleted policy, inform the respective customer (email)
        $this->notice->sendAsEmail([$customer->email_address => $customer->name], $content, $subject, [
            $this->own_company->email_address => $this->own_company->name
        ]);

        // add notification
        $message = 'A policy with policy# ' . $policy->policy_number . ' has been deleted.';
        $this->notice->add($message, 'admin|agent', $user->id, 'policies');
    }

    private function _searchform()
    {

        //generate products list
        $products = Elements::load('Products/ProductsController@getProduct');

        foreach ($products as $product) {
            $productlist[$product->id] = $product->name;
        }

        //generate insurer list
        $insurers = Elements::load('Insurers/InsurersController@getInsurer');

        foreach ($insurers as $insurer) {
            $insurerlist[$insurer->id] = $insurer->name;
        }

        $searchform = [
            'title' => 'Policy Filter Form',
            'form' => [
                'preventjQuery' => TRUE,
                'method' => 'post',
                'action' => '/admin/policies/search',
                'controls' => [
                    'Customer Name' => ['text', 'name', ''],
                    'Products' => ['select', 'product', '', $productlist],
                    'Insurers' => ['select', 'insurer', '', $insurerlist]
                ],
                'map' => [2, 1]
            ]
        ];

        return $searchform;
    }

    public function getPoliciesByCustomer($id)
    {

        $rawpolicies = $this->model->where('customers_id', '=', $id)->show();
        return $this->_translatePolicies($rawpolicies);
    }

    public function getPolicyByQuote($id)
    {

        $dbpolicy = $this->model->where('customer_quotes_id', '=', $id)->first();

        if (count($dbpolicy) >= 1) {
            return $this->_translatePolicies($dbpolicy);
        } else {
            return NULL;
        }
    }

    public function analysePoliciesByMonth($settings = [])
    {

        $policies = $this->model->getPolicyAnalysis();
        $months = $policies['months'];
        unset($policies['months']);

        $pchart = new Charts($settings);

        if ($settings['type'] == 'stackedcolumn')
            $type = 'column';
        else
            $type = $settings['type'];

        $pchart->setup(['type' => $type]);
        $pchart->title('Generated Policies By Month');

        $pchart->xAxis([
            'categories' => $months
        ]);

        $pchart->yAxis([
            'allowDecimals' => 'false',
            'min' => 0,
            'title' => [
                'text' => "''"
            ],
            'plotLines' => [
                'value' => '0',
                'width' => '1',
                'color' => "'#808080'"
            ]
        ]);

        $pchart->tooltip([
            'valueSuffix' => "' Quote(s)'"
        ]);

        $pchart->legend([
            'layout' => "'vertical'",
            'align' => "'right'",
            'verticalAlign' => "'middle'",
            'floating' => 'true',
            'borderWidth' => '0'
        ]);

        $seriesmethod = $settings['type'] . 'Series';
        $pchart->$seriesmethod($policies, ['Not Issued', 'Issued']);

        $quoteschart = $pchart->build();

        $this->view->set('monthlypolicies', $quoteschart);
        $this->view->setViewPanel('monthly-policies');
    }

    public function proceedToPolicy()
    {
        Redirect::to('/admin/policies/add');
    }

    public function previewPolicy()
    {
        $policy_id = Input::get('id');

        $policy = $this->model->getPolicy($policy_id);

        $quote = Elements::call('Quotes/QuotesController')
            ->getQuoteById($policy->customer_quotes_id);
        $product = Elements::call('Products/ProductsController')->getProduct($quote->products_id);

        $policy->quote = $quote;

        $policy->customer_info = json_decode($quote->customer_info);
        $policy->product_info = json_decode($quote->product_info);
        $amounts = json_decode($quote->amount);
        $policy->amounts = $amounts;
        $policy->dategenerated = date('d F Y', (!is_null($policy->datetime) ? $policy->datetime : time()));

        $insurer = Elements::call('Insurers/InsurersController')
            ->getInsurer($amounts->insurer_id);
        $policy->insurer = $insurer;

        $product = Elements::call('Products/ProductsController')
            ->getProduct($policy->products_id);
        $policy->product = $product['name'];

        $policy->quote_status = $quote->status;

        $this->view->set('class', '');
        if (!is_null(Input::get('pdf')))
            $this->view->set('class', 'hide');

//        print_r($product);exit;
        $this->view->set('product_alias', $product['product_alias']);
        $this->view->generatePolicyPreview($policy);
    }

    public function showRelatedDocs()
    {
        $policy_docs = $this->model->getPolicyDocs(Input::get('id'));
        $this->view->set('docs_count', $policy_docs['count']);

        if ($policy_docs['count']) {
            foreach ($policy_docs['docs'] as $key => $doc) {
                $document = Elements::call('Documents/DocumentsController')->model->find($doc->documents_id)->data;
                $document->time = date('d F, Y H:i', $document->datetime);
                $document->policy_id = Input::get('id');

                if (count($document) > 0) {
                    $doclist[] = $document;
                }
            }
        }

        $this->view->set('source', $doclist);
        $this->view->loadDownloadDocsModal();
    }

    public function renewBatch()
    {
        // set the necessary data to be used
        $this->initData();

        $ids = Input::post('ids');
        $return = [];

        if (count($ids)) {
            foreach ($ids as $id) {
                $policy = $this->model->getPolicy($id);

                $this->sendRenewalNotice($policy);

                $return[] = [
                    'policy_id' => $policy->id,
                    'policy_number' => $policy->policy_number,
                    'end_date' => date('d F Y', $policy->end_date)
                ];
            }
        }

        $policies = $return;
        $this->view->renewPolicies($policies);
    }

    public function renewPolicies()
    {
        $post = Input::post();

        for ($i = 1; $i <= $post['field_count']; $i++) {
            $end_date = strtotime($post['end_date' . $i]);
            $period = $post['period' . $i];
            $policy_id = $post['policy_id' . $i];

            $policy = $this->model->find($policy_id);
            $policy->start_date = $end_date;
            $policy->end_date = strtotime(date("Y-m-d", $end_date) . " +$period");
            $policy->save();
        }

        Redirect::withNotice('Renewal was successful', 'success')
            ->to('/admin/policies/');
    }

    public function pdfpolicy()
    {
        $this->view->disable();

        $this->generatePDFPolicy(Input::get('id'), true, false, true);
    }

    public function generatePDFPolicy($id, $savedoc = true, $return = false, $exit = false)
    {
        //get policy
        $policy = $this->model->find($id);
        $this->view->disable();
        //get own company
        $owncompany = Elements::call('Companies/CompaniesController')->ownCompany(TRUE);
        $url = Url::base() . '/ajax/policies/previewpolicy/' . $id . '/show';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

//        dd($result);

        // output the HTML content
        $pdf = new \mPDF();

        $pdf->useSubstitutions = false; // optional - just as an example
        $pdf->SetHeader("\n\n" . 'Page {PAGENO}');  // optional - just as an example
        $pdf->CSSselectMedia = 'mpdf'; // assuming you used this in the document header
        $pdf->setBasePath($url);
        $pdf->writeHTML($result);

        //creat and save document record
        if (!$return) {
            //Close and output PDF document
            $filename = 'policies' . '_' . rand(0, 100) . '_' . date('d-m-Y', $policy->datetime) . '.pdf';
            $qname = PROJECT_PATH . DS . 'uploads' . DS . $filename;

            $pdf->Output($qname, 'F');
            $pdf->Output($qname, 'I');
        } else {

            //Close and output PDF document
            $filename = 'policy' . '_' . $policy->id . '_' . date('d-m-Y', $policy->datetime) . '.pdf';
            $qname = PROJECT_PATH . DS . 'uploads' . DS . $filename;

            $pdf->Output($qname, 'F');
        }

        $filepath = 'project' . DS . 'uploads' . DS . $filename;
        $description = 'PDF document for Policy No.' . $policy->policy_number;
        $doctype = 'policy_pdf';

        if ($savedoc == true) {
            $newid = Elements::call('Documents/DocumentsController')->saveDoc($filename, $filepath, $doctype, $description);
            $this->model->saveQuoteDocuments($policy->id, $newid);
        }

        if(!$exit)
            return $qname;
        else
            echo $qname;exit;
    }

    public function emailPolicy()
    {
        $id = Input::get('id');

        $policy = $this->model->where('id', $id)->first();

        //get quote
        $quote = Elements::call('Quotes/QuotesController')->getQuoteById($policy->customer_quotes_id);

        //get products
        $products = Elements::call('Products/ProductsController')->getProduct($quote->products_id);

        //get customer
        $customer = Elements::call('Customers/CustomersController')->model->find($quote->customers_id);

        //get agent
        $agent = Elements::call('Agents/AgentsController')->retrieveAgents($customer->insurer_agents_id);

        $this->view->mailPolicy($policy, $customer, $products, $agent);
    }

    public function sendEmail()
    {
        $send_mode = Input::post('sendmode');

        $mail = new \PHPMailer();

        //Set who the message is to be sent from
        $mail->setFrom(Input::post('ownemail'), Input::post('replyname') . ' - ' . Input::post('owncompany'));

        //Set an alternative reply-to address
        $mail->addReplyTo(Input::post('replyto'), Input::post('replyname'));

        //Set who the message is to be sent to
        $mail->addAddress(Input::post('email'), Input::post('customer_name'));

        //Set the subject line
        $mail->Subject = Input::post('subject');

        $mail->msgHTML(Input::post('content'));

        $path = Input::post('email_attach_filepath');
        if (!empty($path)) {
            $mail->addAttachment($path);
        }

        $mail->send();

        Redirect::withNotice('The Policy has been sent!')
            ->to('/admin/policies');
    }

    public function createPDFAttachment()
    {
        $this->view->disable();
        $quotefile = $this->generatePDFPolicy(Input::post('id'), TRUE, TRUE);

        $this->view->createEmailAttachment($quotefile);
    }

    /**
     * Send Mail Notification for a new policy issue
     * @param $policy_id
     */
    public function sendMailNotification($policy_id)
    {
//        dd('Sending Mail Notification for Policy#: '.$policy_id);
        $path = $this->generatePDFPolicy($policy_id, TRUE, TRUE);

        // get the policy
        $policy = $this->model->where('id', $policy_id)->first();

        // get the customer data from the policy id
        $customer = $this->getCustomerInfoByPolicyId($policy->customers_id);
        $customer_name = $customer->name;

        // get the agent attached to customer
        $agent_id = $customer->insurer_agents_id;
        $agent = Elements::call('Agents/AgentsController')
            ->getAgentById($agent_id);

        // get insurer details
        $insurer = Elements::call('Insurers/InsurersController')
            ->model
            ->where('id', $policy->insurers_id)
            ->first();

        $content = '<p>Dear ' . $customer_name . '</p>';
        $content .= '<p>Hope this finds you well. We have prepared your policy. Click the link below to review your policy:</p>';
        $content .= Url::link('/policies/previewpolicy/' . $policy_id);
        $content .= '<p>You can also download the linked document from the preview by clicking the "Download Documents" button.</p>';
        $content .= '<p>If you have any questions regarding the offer, please don\'t hesitate to contact me.</p>';
        $content .= '<p>Best regards,<br/>';
        $content .= $agent->names;
        $content .= '<br/>Email: ' . $agent->email_address . '<br/>Telephone: ' . $agent->telephone_number;

        $mail = new \PHPMailer();

        //Set who the message is to be sent from
        $mail->setFrom($insurer->email_address, $agent->names . ' - ' . $insurer->official_name);

        //Set an alternative reply-to address
        $mail->addReplyTo($agent->email_address, $agent->names);

        //Set who the message is to be sent to
        $mail->addAddress($customer->email, $customer_name);

        //Set the subject line
        $mail->Subject = 'Please review your policy';

        $mail->msgHTML($content);

        if (!empty($path)) {
            $mail->addAttachment($path);
        }

        $mail->send();
    }

    public function getCustomerInfoByPolicyId($customer_id)
    {
        return Elements::call('Customers/CustomersController')
            ->getCustomerById($customer_id, 'array');
    }

    /**
     * Get the logged in customer's policies
     * @param bool $dash
     */
    public function myPolicies($dash = false)
    {
        $this->setData();

        // get customer id from username
        $user = $this->userCtrl->model->find(Session::get('user_id'));
        $customer = $this->customerCtrl->getCustomerByEmail($user->username);

        $rawpolicies = $this->model->where('customers_id', $customer->id)->get();

        $policies = $this->_translatePolicies($rawpolicies, 'internal', true);

        $this->set('count', $this->model->count());
        $this->set('source', $policies);
        $this->set('search', $this->_searchform());

        $this->view->generateTable(true, $dash);
    }

    public function setData()
    {
        $this->userCtrl = Elements::call('Users/UsersController');
        $this->customerCtrl = Elements::call('Customers/CustomersController');
        $this->agentCtrl = Elements::call('Agents/AgentsController');
    }

    public function myDashPolicies()
    {
        $this->myPolicies(true);
    }

    /**
     * run by a cronjob
     * checks for policies which are within a month of expiry
     */
    public function checkForExpiredPolicies()
    {
        $this->setData();
        $current_date = Carbon::parse(date('Y-m-d'));

        // get all available policies
        $policies = $this->model->where('end_date', '>=', strtotime($current_date))->get();

        if (count($policies)) {
            foreach ($policies as $policy) {
                // get the start date
                $start_date = Carbon::parse(date('Y-m-d', $policy->start_date));

                // get the end date
                $end = Carbon::parse(date('Y-m-d', $policy->end_date));

                // get the difference in days
                $diff_in_days = $end->diffInDays($current_date);

                // start notice
                $notice = App::get('notice');

                $subject = 'Policy Expiry (Policy#' . $policy->policy_number . ')';

                // get the insurer details
                $own = Elements::call('Companies/CompaniesController')->ownCompany(true);

                $message = 'The policy with policy#: <b>' . $policy->policy_number . ' </b> is almost expired';
                if ($diff_in_days <= 31) {
                    // get attached customer
                    $customer = $this->customerCtrl->getCustomerById($policy->customers_id, 'null');

                    if (!empty($customer->insurer_agents_id)) {
                        // get agent details
                        $agent = $this->agentCtrl->retrieveAgents($customer->insurer_agents_id);

                        $content = '<p>Dear <b>' . $agent->names . '</b>,';
                        $content .= '<p>The policy with policy#: <b>' . $policy->policy_number . '</b> is almost expired.</p>';

                        // notify agent of the expiry
                        $notice->sendAsEmail([$agent->email_address => $agent->names], $content, $subject, [
                            $own->email_address => $own->name
                        ]);

                        // get agent user id
                        $user = $this->userCtrl->model->where('insurer_agents_id', $customer->insurer_agents_id)
                            ->first();
                        $user_id = (!empty($user->id)) ? $user->id : 0;
                        $notice->add($message, 'admin|agent', $user_id, 'policies');
                    } else {
                        $content = '<p>Dear <b>' . $agent->names . '</b>,';
                        $content .= '<p>The policy with policy#: <b>' . $policy->policy_number . '</b> is almost expired.</p>';

                        // notify own
                        $notice->sendAsEmail([$own->email_address => $own->name], $content, $subject, [
                            $own->email_address => $own->name
                        ]);
                        $notice->add($message, 'admin|agent', 0, 'policies');
                    }
                }
            }
        }
    }

    /**
     * Gets the selected policy details and loads a modal
     * @param $id
     */
    public function showRenewPolicy($id)
    {
        $policy = $this->model->where('id', $id)->first();

        $policy->end_date = date('Y-m-d', $policy->end_date);

        $this->view->showRenewalModal($policy);
    }

    /**
     * Renew a single policy
     */
    public function renewPolicy()
    {
        $end_date = Input::post('end_date');
        $period = Input::post('period');

        $new_end_date = strtotime($end_date . " +$period");

        $policy = $this->model->find(Input::post('id'));
        $policy->start_date = strtotime($end_date);
        $policy->end_date = $new_end_date;
        $policy->save();

        if ($policy->hasNoErrors()) {
            $this->initData();
            $this->sendRenewalNotice($policy);

            Redirect::withNotice('The policy has been renewed', 'success')
                ->to(Url::route('/admin/policies'));
        }
    }

    /**
     * Renewal Notice
     */
    public function sendRenewalNotice($policy)
    {
        // get the customer attached
        $customer = $this->customerCtrl->getCustomerById($policy->customers_id, 'null');

        // get the user attached
        $user = $this->userCtrl->getUserByCustomerId($customer->id);

        // message
        $content = '<p>Dear <b>' . $customer->name . '</b></p>';
        $content .= '<p>The policy with Policy<b># ' . $policy->policy_number . '</b> has been renewed.</p>';

        // subject
        $subject = 'Policy No: ' . $policy->policy_number . ' has been renewed';

        // for every deleted policy, inform the respective customer
        $this->notice->sendAsEmail([$customer->email_address => $customer->name], $content, $subject, [
            $this->own_company->email_address => $this->own_company->name
        ]);

        // add notification
        $message = 'A policy with policy# ' . $policy->policy_number . ' has been renewed.';
        $this->notice->add($message, 'admin|agent', $user->id, 'policies');
    }
}
