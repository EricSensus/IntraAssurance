<?php

namespace Jenga\MyProject\Claims\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Core\App;
use Jenga\App\Helpers\FileUpload;
use Jenga\App\Html\Excel;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Request\Url;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Claims\Models\ClaimsModel;
use Jenga\MyProject\Claims\Views\ClaimsView;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Documents\Controllers\DocumentsController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Users\Controllers\UsersController;
use Svg\Document;

/**
 * Class ClaimsController
 * @property-read ClaimsView $view
 * @property-read ClaimsModel $model
 * @package Jenga\MyProject\Claims\Controllers
 */
class ClaimsController extends Controller
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
     * @var DocumentsController
     */
    private $docCtrl;
    /**
     * @var string
     */
    protected $upload_dir = PROJECT_PATH . DS . 'uploads/';

    /**
     * Entry route
     */
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
        $this->$action();
    }

    /**
     * Get detailed information
     * @param int $customer_id
     * @return object
     */
    private function getCustomerDataArray($customer_id)
    {
        $_customer = Elements::call('Customers/CustomersController')->getCustomerById($customer_id, false);
        $info = get_object_vars($_customer);
        $product_datas = json_decode($info['additional_info']);
        unset($info['additional_info']);
        $info['dob'] = date('Y-m-d', $info['date_of_birth']);
        $info['mobile'] = $info['mobile_no'];
        $info['address'] = $info['postal_address'];
        $info['code'] = $info['postal_code'];
        $info['surname'] = substr($info['name'], 0, strpos($info['name'], ' '));
        $info['names'] = substr($info['name'], 1 + strpos($info['name'], ' '));
        $the_arrays = $info;
        if (count($product_datas)) {
            foreach ($product_datas as $for_product) {
                $the_arrays = array_merge($the_arrays, get_object_vars($for_product));
            }
        }
        return (object)$the_arrays;
    }

    /**
     * Get policy data
     * @param $policy_id
     * @return \stdClass
     */
    public function getPolicyDetails($policy_id)
    {
        $data = new \stdClass();
        $data->policy = $policy = $this->model->findFromTable($policy_id, 'policies');
        $quote_id = $policy->customer_quotes_id;
        $data->quote = $quote = $this->model->findFromTable($quote_id, 'customer_quotes');
        $data->customer = $this->getCustomerDataArray($policy->customers_id);
        $data->product = (object)Elements::load('Products/ProductsController@getProduct', ['id' => $quote->products_id]);
        $quote_amounts = $this->getQuoteAmount($quote);
        $insurer_id = $quote_amounts->insurer_id;
        $data->agent = Elements::call('Users/UsersController')->getAgentUserInfo($data->customer->insurer_agents_id);
        $data->insurer = (object)Elements::call('Insurers/InsurersController')->getInsurer($insurer_id);
        $data->entities = Elements::call('Entities/EntitiesController')->getCustomerEntity($quote->customer_entity_data_id);
        return $data;
    }

    /**
     * @param $id
     * @return object
     */
    public function getClaimData($id)
    {
        $claim = $this->model->where('id', $id)->first();
        $claim->policy = Elements::call('Policies/PoliciesController')
            ->getPolicyData($claim->policy_id);
        $claim->quote = $claim->policy->quote;
        $claim->customer = $claim->policy->customer_info;
        unset($claim->description, $claim->policy->quote, $claim->policy->customer_info);
        return $claim;
    }

    /**
     * Get the array amounts
     * @param $quote
     * @return int|null|object
     */
    private function getQuoteAmount($quote)
    {
        $amounts = json_decode($quote->amount);
        $use_amount = null;
        if (empty($amounts)) {
            return 0;
        }
        try {
            foreach ($amounts as $struct) {
                if ($struct->chosen) {
                    $use_amount = $struct;
                    break;
                }
            }
        } catch (\Exception $e) {
            $use_amount = (object)[];
        }
        return $use_amount;
    }

    /**
     * Get individual process details
     * @param $processes
     * @return array
     */
    private function getProcessDetails($processes)
    {
//        dump($processes);exit;
        if (empty($processes))
            return [];
        $list = [];
        foreach ($processes as $key => $process) {
            if (!empty($process->documents_id)) {
                $document = Elements::call('Documents/DocumentsController')->model->find($process->documents_id)->data;
                $document->time = date('d F, Y H:i', $document->datetime);
                $document->link = Url::link('/' . $document->filepath);
                $process->file = $document;

            }
            $process->user = Elements::call('Users/UsersController')->model->where('id', $process->user_id)->first();
            $list[] = $process;
        }
        return $list;
    }

    /**
     * File a claim
     */
    public function fileclaim()
    {
        $claim_process = $this->model->getProcess(Input::get('id'));
        $this->view->set('id', Input::get('id'));
        $this->view->set('process_count', $claim_process['count']);
        $this->view->set('timeline', $this->getProcessDetails($claim_process['process']));
        $this->view->uploadClaimAdditionalDocs();
    }

    /**
     * Notification handler for accepted}quote
     * @param $claim
     * @return mixed
     */
    private function notificationForNewClaim($claim)
    {
        $own = Elements::call('Companies/CompaniesController')->ownCompany(true);
        $data = $this->getPolicyDetails($claim->policy_id);

        // get agent name
        $agent = Elements::Call('Agents/AgentsController')->getAgentById($data->agent->id);

        $content = '<p>Hello, ' . $agent->names . '</p>';
        $content .= '<p>Your client ' . $data->customer->name . ' has filed a new claim.</p>
                <p>Please log into the portal to view all the details. Click the link below.</p>
                 <p><a href="' . SITE_PATH . '/login" target="_blank">' . SITE_PATH . '/login</a></p>
                 <p>Thanks</p>
                 <p><strong>' . $data->insurer->name . ' Insurance Portal</strong></p>';

        $subject = 'You started a new claim for ' . $data->product->name . '. Policy #' . $data->policy->policy_number;
        $message = 'Your client "' . $data->customer->name . '" has filled a new claim for ' . $data->product->name . '. Policy #' . $data->policy->policy_number;
        $_cust = Elements::call('Users/UsersController')->getCustomerUserInfo($data->customer->id);

        $notice = App::get('notice');
        $notice->add($message, 'agent', $data->agent->id, 'claims');
//        $notice->add($subject, 'customer', $_cust->id, 'claims');
        $notice->sendAsEmail([$data->agent->username => $data->agent->username], $content, 'New claim for your client ' . $data->customer->name, [$own->email_address => $own->name]);
    }

    /**
     * Save claim from post data
     */
    public function saveclaim()
    {
        $this->docCtrl = Elements::call('Documents/DocumentsController');

        $policy_id = Input::post('policy_id');
        $policy = $this->getPolicyDetails($policy_id);
        $this->view->disable();
        $claim = $this->model;
        $claim->subject = Input::post('subject');
        $claim->description = Input::post('description');
        $claim->policy_id = $policy_id;
        $claim->agent_id = $policy->customer->insurer_agents_id;
        $claim->customer_id = $policy->customer->id;
//        $claim->created_at = time();
        $claim->status = 'New';
        $claim->save();
        $this->notificationForNewClaim($claim);
        $this->updateTimeline($claim->last_altered_row, "Started filing a claim : Status -> Processing");

        if ($claim->hasNoErrors()) {

//             loop and upload the attached files
            $this->docCtrl->uploadMultipleFiles('attached_files', $claim->last_altered_row);

            Redirect::withNotice('The claim has been saved')
                ->to(Url::base() . '/admin/claims/edit/' . $claim->last_altered_row);
        }
    }

    /**
     * @param $claim
     * @param $desc
     */
    public function notificationsForClaim($claim, $desc)
    {
        $own = Elements::call('Companies/CompaniesController')->ownCompany(true);
        $data = $this->getPolicyDetails($claim->policy_id);
        $content = '<p>Hello, ' . $data->customer->name . '</p>';
        $content .= '<p>Your claim for ' . $data->product->name . ' product was updated. Current Status is ' . Input::post('action') . '</p>
       <p>Please log into the portal to view all the details. Click the link below.</p>
                   <p><a href="' . SITE_PATH . '/login" target="_blank">' . SITE_PATH . '/login</a></p>
                   <p>Thanks</p>
                   <p><strong>' . $data->insurer->name . ' Insurance Portal</strong></p>';

        $subject = 'You have updated claim #' . $claim->id . '  Statement:' . $desc;
        $message = 'Your claim #' . $claim->id . ' for product "' . $data->product->name . ' was updated . ' . $desc;
        $_cust = Elements::call('Users/UsersController')->getCustomerUserInfo($data->customer->id);

        $notice = App::get('notice');
        $notice->add($subject, 'agent', $this->user()->id, 'claims');
        $notice->add($message, 'customer', $_cust->id, 'my-claims');
        $notice->sendAsEmail([$data->customer->email => $data->customer->name], $content, $subject, [$own->email_address => $own->name]);

    }

    /**
     * Get a claim preview
     */
    public function previewclaim()
    {
//        $customer_id = Input::post('customer_id');
        $policy_id = Input::post('policy_id');
        $data = $this->getPolicyDetails($policy_id);
        // $claim = $this->model->createEmpty();
        $this->view->addClaim($data);
    }

    /**
     * Get the customer policies
     */
    public function getPolicies()
    {

        $this->view->disable();

        //get the customers policies
        $id = Input::request('id');
        $policies = $this->model->table('policies')
            ->where('customers_id', '=', $id)
            ->where('status', 'issued')
            ->show();

        $insurer = Elements::call('Insurers/InsurersController');

        if (count($policies) > 0) {
            foreach ($policies as $policy) {
                $list[] = [
                    'id' => $policy->id,
                    'policy' => strtoupper($policy->policy_number),
                    'datetime' => $policy->datetime,
                    'amount' => $policy->amount,
                    'insurer' => $insurer->getInsurer($policy->insurers_id)['name']
                ];
//                }
            }
            $select_claim_list = $this->view->getPolicyList($list);
            $return = [
                'status' => true,
                'content' => $select_claim_list
            ];
        } else {
            $return = [
                'status' => false,
            ];
        }
        echo json_encode($return);
    }

    /**
     * Create a new quote
     */
    public function add()
    {
        $customer = null;
        if ($this->user()->is('customer')) {
            $id = $this->user()->customer_id;
        }
        if (!empty($id)) {
            $customer = Elements::call('Customers/CustomersController')->getCustomerById($id, 'raw');
            $policies = Elements::call('Policies/PoliciesController')->getPoliciesByCustomer($customer->id);
            //get product
            //print_r($policies);
            $product = Elements::call('Products/ProductsController');
            //get insurer
            $insurer = Elements::call('Insurers/InsurersController');
            //process policies
            if (!is_null($policies)) {
                $list = [];
                foreach ($policies as $quote) {
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
        $this->view->addClaimForm($customer);
    }

    /**
     * Get policy preview
     */
    public function getPolicyPreview()
    {
        $meta = $this->getPolicyDetails(Input::get('id'));
        $this->view->previewPolicy($meta);
    }

    /**
     * Upload a document form
     * @param $id
     */
    public function upload($id)
    {
        $settings = [
            'title' => 'Upload Document',
            'id' => $id,
        ];
        $this->view->uploadForm($settings);
    }

    /**
     * Uptate the timeline
     * @param $claim
     * @param $description
     * @param null $file
     */
    public function updateTimeline($claim, $description, $file = null)
    {
        $process = $this->model->getProcessModel();
        $process->claim_id = $claim;
        $process->user_id = $this->user()->id;
        $process->description = $description;
        if (!empty($file)) {
            $process->documents_id = $file;
        }
        return $process->save();
    }

    /**
     *
     */
    public function delete()
    {
//            dump(Input::get('id'));exit;
        if(Input::has('id')) {
            $this->view->disable();
            $claim_id = Input::get('id');
            $this->model->where('id', $claim_id)->delete();

            $url = Url::route('/admin/claims/');
            Redirect::withNotice('The claim has been deleted', 'success')
                ->to($url);
        }

        if(Input::has('ids')){
            $ids = Input::post('ids');

            foreach ($ids as $id) {
//                // get the policy data
//                $policy = $this->model->getPolicy($id);
//
//                // send delete notification
//                $this->deleteNotification($policy);

                // delete from db
                $this->model->where('id', '=', $id)->delete();
            }

            $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'claims']);
            Redirect::withNotice('The claim(s) have been deleted ', 'success')
                ->to($url);
        }
    }

    /**
     * Edit a claim
     */
    public function edit()
    {
        $claim = $this->model->find(Input::get('id'));
        $process = $this->model->getProcess($claim->id);
        $meta = $this->getPolicyDetails($claim->policy_id);
        $this->view->set('meta', $meta);
        $process_details = $this->getProcessDetails($process['process']);
        $this->view->set('timeline', $process_details);
        $this->view->editClaim($claim, $meta);
    }

    /**
     * Update a claim
     */
    public function updateclaim()
    {
        $this->view->disable();
        $claim = $this->model->find(Input::post('claim'));
        $claim->status = Input::post('status');
        $claim->save();
        $desc = "Claim status: " . Input::post('status') . "<br/>";
        $desc .= "Message: " . Input::post('message');
        if (Input::post('sendemail') == 'yes') {
            $desc .= "<br/>Sent email to customer";
        }
        $this->updateTimeline($claim->id, $desc);
        $this->notificationsForClaim($claim, $desc);
        if ($claim->hasNoErrors()) {
            Redirect::withNotice('The claim has been saved')
                ->to(Url::base() . '/admin/claims');
        }
    }

    /**
     * Process upload
     */

    public function processUpload()
    {
        //get folder information
        $element = 'claims';
        $action = 'uploaddoc';
        $details = Input::post('details');
        $id = Input::post('id');
        $handler = new FileUpload('file_import');
        $handler->setFilePrefix();
        $_document = Elements::call('Documents/DocumentsController');
        if ($handler->handleUpload($this->upload_dir)) {

            $filename = $handler->getFileName();
            $docs = $_document->model->find(['filename' => $filename]);

            $docs->filename = $filename;
            $docs->filepath = 'project' . DS . 'uploads' . DS . $filename;
            $docs->description = $element . ' document';
            $docs->doctype = $element;
            $docs->datetime = time();
            $docs->save();
            $desc = empty($details) ? "Uploaded a document -> " . $filename : $details;
            $process = $this->updateTimeline($id, $desc, $docs->last_altered_row);
            if ($process) {
                Redirect::withNotice('The document has been saved', 'success')
                    ->to(Url::base() . '/admin/claims/edit/' . $id);
            }
        }
    }

    /**
     * Get all claims form a particular customer
     * @param $id
     * @return array|null
     */
    public function getClaimsByCustomer($id)
    {
        $claims_db = $this->model->where('customer_id', '=', $id)->show();

        if (count($claims_db) >= 1) {
            return $this->_mapClaimsFields($claims_db);
        } else {
            return NULL;
        }
    }

    /**
     * Close a policy
     * @param $id
     */
    public function closepolicy($id)
    {
        $claim = $this->model->find($id);
        $policy = $this->getPolicyDetails($claim->policy_id);
        $this->view->closeClaim($claim, $policy);
    }

    /**
     * @param null $search
     */
    public function show($search = NULL){
        
        if (is_null($search)) {
            if ($this->user()->is('agent')) {
                $rawClaims = $this->model->select(TABLE_PREFIX . 'claims.*')
                    ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "claims.customer_id", 'LEFT')
                    ->where('insurer_agents_id', $this->user()->insurer_agents_id)
                    ->get();
            } else {
                $rawClaims = $this->model->all();
            }

        } else {
            $results = $this->model->search($search);
            $this->set('condition', $results['condition']);
            $rawClaims = $results['result'];
        }
        $claims = $this->_mapClaimsFields($rawClaims);


        $this->set('count', $this->model->count());
        $this->set('source', $claims);
        $this->set('search', $this->_seekForm());
        $this->view->generateTable();
    }

    /**
     * Get a representational state of claim fields
     * @param $claims
     * @return array
     */
    private function _mapClaimsFields($claims)
    {
        $__stash = [];
        foreach ($claims as $claim) {
            $new_claim = $this->model->createEmpty();
            $meta = $this->getPolicyDetails($claim->policy_id);
            $new_claim->created = date('d-M-y', strtotime($claim->created_at));
            $new_claim->policyno = $claim->policy_id;
            $new_claim->status = $claim->status;
            $new_claim->claim = $claim->id;
            $new_claim->id = $claim->id;
            $new_claim->insurer = $meta->insurer->name;
            $new_claim->policy = $meta->policy->policy_number;
            $new_claim->customer = $meta->customer->name;
            $new_claim->product = $meta->product->name;
            $new_claim->customer_id = $meta->customer->id;
            $new_claim->actions = '<i class="moreactions fa fa-bars fa-lg" aria-hidden="true"></i>';

            // more fields
            $new_claim->subject = $claim->subject;
            $new_claim->description = $claim->description;
            $new_claim->closed = $claim->closed;
            $new_claim->agent_name = Elements::call('Agents/AgentsController')
                ->getAgentById($claim->agent_id)->names;
            $__stash[] = $new_claim;
        }
        return $__stash;
    }

    /**
     * Search for a claim
     * @return array
     */
    private function _seekForm()
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
            'title' => 'Claim Filter Form',
            'form' => [
                'preventjQuery' => TRUE,
                'method' => 'post',
                'action' => '/admin/claims/search',
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

    /**
     * Get my claims
     * @param bool $dash
     */
    public function myClaims($dash = false)
    {
        $this->setData();

        // get customer id from username
        $user = $this->userCtrl->model->find(Session::get('user_id'));
        $customer = $this->customerCtrl->getCustomerByEmail($user->username);

        $rawClaims = $this->model->where('customer_id', $customer->id)->get();
        $claims = $this->_mapClaimsFields($rawClaims);

        $this->set('count', $this->model->count());
        $this->set('source', $claims);
        $this->set('search', $this->_seekForm());
        $this->view->generateTable($dash);
    }

    /**
     * Set the elements in place
     */
    public function setData()
    {
        $this->userCtrl = Elements::call('Users/UsersController');
        $this->customerCtrl = Elements::call('Customers/CustomersController');
    }

    /**
     * Get my claims on the dashboard
     */
    public function myDashClaims()
    {
        $this->myClaims(true);
    }

    public function export()
    {
        $dbclaims = $this->model->all();
        $claims = $this->_mapClaimsFields($dbclaims);
//        dump($claims);exit
        $columns = [
            'customer' => 'Customer',
            'policy' => 'Policy No',
            'created' => 'Date Created',
            'status' => 'Status',
            'subject' => 'Subject',
            'description' => 'Description',
            'closed' => 'Closed',
            'agent_name' => 'Agent'
        ];

        $company = $this->model->table('own_company')->first();
        $doc = new Excel($company->name . ' Claims Listing', Input::post('filename'));

        $doc->generateDoc($columns, $claims, Input::post('format'));
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
//        dump($doc->worksheet->rows);exit;
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

                        case 'customer':
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
                            $db->customer_id = $customer_record_id;
                            break;

                        case 'policy':

                            $policy_number = $doc->worksheet->rows[$pickedcol . ',' . $r];

                            //get the products_id
                            $policy_elm = Elements::call('Policies/PoliciesController');
                            $policies = $policy_elm->model->where('policy_number', $policy_number)->show();

                            if (count($policies) >= 1) {
                                $db->policy_id = $policies[0]->id;
                            } else {
                                $errorlog['policy'][$pickedcol . ',' . $r] = 'Skipped! Policy number (' . $policy_number . ') does not exists!';
                            }
                            break;

                        case 'agent_name':
                            $agent_name = $doc->worksheet->rows[$pickedcol . ',' . $r];

                            // find the agent by name
                            $agent_ctrl = Elements::call('Agents/AgentsController');
                            $agent = $agent_ctrl->model->where('names', $agent_name)->show();

                            if (count($agent) == 0) {
                                $errorlog['agent_name'][$pickedcol . ',' . $r] = 'Skipped! Agent ( ' . $agent_name . ' ) does not exist!';
                            } else {
                                $agent_id = $agent[0]->id;
                            }

                            $db->agent_id = $agent_id;
                            break;

                        default:
                            //create the insert row
                            $db->{$dbcol} = $doc->worksheet->rows[$pickedcol . ',' . $r];
                            break;
                    }
                }

                $db->created_at = date('Y-m-d H:i:s');
            }

            $save = $db->save();

//            dump($this->model->errors());exit;
            if (array_key_exists('ERROR', $save)) {
                $errorlog['database'][$pickedcol . ',' . $r] = $save['ERROR'];
            }
        }

        if (count($errorlog) == 0) {

            Redirect::withNotice('The claims records have been imported.', 'success')
                ->to(Url::route('/admin/claims'));
        } else {

            $this->view->showImportErrors($errorlog);
        }
    }

    /**
     * /**
     * Prepare the quotes to be exported
     */
    public function getProcessedClaims()
    {
        $agents = Elements::call('Agents/AgentsController')->getAllAgents();
        $quote_data = [];


        // format the quotes
        if (count($agents)) {
            foreach ($agents as $agent) {
                // get quotes by agent
                $agent_claims = $this->model->getClaimsByAgent($agent->id)->get();

                // get pending quotes
                $open_claims = $this->model->getClaimsByAgent($agent->id, ['status' => 'Open'])->get();

                // get rejected quotes
                $closed_claims = $this->model->getClaimsByAgent($agent->id, ['status' => 'Closed'])->get();

                // get accepted quotes
                $new_claims = $this->model->getClaimsByAgent($agent->id, ['status' => 'New'])->get();
                $processing = $this->model->getClaimsByAgent($agent->id, ['status' => 'Processing'])->get();

                $quote_data[] = (object)[
                    'agent_name' => $agent->names,
                    'total_quotes' => count($agent_claims),
                    'open_claims' => count($open_claims),
                    'closed_claims' => count($closed_claims),
                    'new_claims' => count($new_claims),
                    'processing' => count($processing)
                ];
            }
        }

        return $quote_data;
    }

    public function getActiveClaims(){
        if ($this->user()->is('agent')) {
            $rawClaims = $this->model->select(TABLE_PREFIX . 'claims.*')
                ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "claims.customer_id", 'LEFT')
                ->where('insurer_agents_id', $this->user()->insurer_agents_id)
                ->where('status', 'New')
                ->get();
        } else {
            $rawClaims = $this->model->where('status', 'New')->get();
        }
        $claims = $this->_mapClaimsFields($rawClaims);

        $this->set('count',count($rawClaims));
        $this->set('source', $claims);
        $this->set('search', $this->_seekForm());
        $this->view->generateTable();
    }
}
