<?php

namespace Jenga\MyProject\Customers\Controllers;


use Illuminate\Support\Facades\Request;
use Jenga\App\Core\App;

use Jenga\App\Request\Session;

use Jenga\App\Views\HTML;

use Jenga\App\Html\Excel;

use Jenga\App\Views\View;

use Jenga\App\Request\Url;

use Jenga\App\Request\Input;

use Jenga\App\Html\Generate;

use Jenga\App\Views\Overlays;

use Jenga\App\Views\Redirect;

use Jenga\App\Helpers\Help;

use Jenga\App\Helpers\FileUpload;

use Jenga\App\Views\Notifications;

use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Customers\Models\CustomersModel;
use Jenga\MyProject\Customers\Views\CustomersView;
use Jenga\MyProject\Elements;

use Jenga\MyProject\Services\Charts;

/**
 * Class CustomersController
 *
 * @property-read CustomersView $view
 * @property-read CustomersModel $model
 * @package Jenga\MyProject\Customers\Controllers
 */
class CustomersController extends Controller
{


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
     * @acl\role subscriber
     * @acl\alias add
     */
    public function add()
    {


        //create the return url from the Navigation element

        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'customers']);


        $schematic = [

            'preventjQuery' => TRUE,

            'method' => 'POST',

            'action' => '/admin/customers/save/',

            'controls' => [

                'destination' => ['hidden', 'destination', $url],

                'Full Names' => ['text', 'fullnames', ''],
                'Mobile Number' => ['text', 'mobileno', ''],
                'ID Number' => ['text', 'id_number', ''],


                'Date of Birth' => ['date', 'dob', ''],

                'Email Address' => ['text', 'emailaddress', ''],

                'Postal Address' => ['text', 'postaladdress', ''],

                'Postal Code' => ['text', 'postalcode', '']

            ],

            'validation' => [

                'fullnames' => [

                    'required' => 'Please enter your full names'

                ],

                'mobileno' => [

                    'required' => 'Please enter your mobile number'

                ],

                'dob' => [

                    'required' => 'Please enter your date of birth'

                ],

                'emailaddress' => [

                    'required' => 'Please enter your email address',

                    'email' => 'Please enter valid email address'

                ]

            ]

        ];


        $modal_settings = [

            'formid' => 'addform',

            'role' => 'dialog',

            'title' => 'Customer Personal Details',

            'buttons' => [

                'Cancel' => [

                    'class' => 'btn btn-default',

                    'data-dismiss' => 'modal'

                ],

                'Save Edits' => [

                    'type' => 'submit',

                    'class' => 'btn btn-primary',

                    'id' => 'save_button'

                ]

            ]

        ];


        $form = Generate::Form('addform', $schematic);

        $aform = $form->render('horizontal', TRUE);


        $addform = Overlays::ModalDialog($modal_settings, $aform);


        //if($this->user()->can('add', 'customers')){

        $this->view->set('addform', $addform);
        $this->view->setViewPanel('customer-add');
        //}
    }


    public function search()
    {


        $results = $this->model->getCustomers();


        $params = $results['terms'];

        unset($results['terms']);


        $search = array_values($results);

        $searchcount = count($search);


        //create the return url from the Navigation element

        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'customers']);

        $alerts = Notifications::Alert($searchcount . ' Search Results for ' . $params

            . '<a data-dismiss="alert" class="close" href="' . Url::base() . $url . '">×</a>', 'info', TRUE, TRUE);


        $this->view->set('count', $searchcount);

        $this->view->set('alerts', $alerts);

        $this->view->set('source', $search);


        $this->view->generateTable();

    }


    public function find($id)
    {


        return $this->model->find($id)->data;

    }


    /**
     * @acl\role "subscriber"
     * @acl\action "view"
     */
    public function show()
    {
        if (is_null(Input::get('id'))) {
            $dbcustomers = $this->model->getCustomers();

            if (count($dbcustomers)) {

                foreach ($dbcustomers as $customer) {


                    if (is_object($customer)) {


                        $policies = Elements::call('Policies/PoliciesController')->getPoliciesByCustomer($customer->id);

                        $customer->policies = count($policies);


                        //customer quotes section

                        $quotes = Elements::call('Quotes/QuotesController')->getQuotesByCustomer($customer->id);

                        $customer->qcount = count($quotes);

                        //customers controller
                        $claims = Elements::call('Claims/ClaimsController')->getClaimsByCustomer($customer->id);
                        $customer->claims = count($claims);
                        $customer->actions = '<a class="fa fa-lock" data-toggle="modal" data-target="#credetialsModal" href="' . SITE_PATH . '/ajax/admin/customers/loginmodal/' . $customer->id . '">Send login Credentials</a>';
                        $customers[] = $customer;

                    }

                }

            }

            $this->view->set('count', count($customers));
            $this->view->set('source', $customers);

            if (Session::has('deleted')) {
                $this->view->set('deleted', Session::get('deleted'));
            }

            $this->view->generateTable();

        } else {


            $id = Input::get('id');


            $customer = $this->model->findCustomer($id);

            $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'customers']);


            if (!is_null($customer)) {


                //personal details section

                $this->view->set('customer_name', ucfirst($customer->name));

                $this->view->set('customer', $customer);

                $this->view->set('personalmodal', ['id' => 'editmodal']);


                //linked agent section

                $agent = Elements::call('Agents/AgentsController')->getAgentById($customer->insurer_agents_id);


                $this->view->set('agent', $agent);

                $this->view->set('agentmodal', ['id' => 'agentmodal']);


                //customer polices section

                $policies = Elements::call('Policies/PoliciesController')->getPoliciesByCustomer($id);

                $this->view->set('policycount', count($policies));

                $this->view->set('policies', $policies);

                $this->view->generatePolicies();

                //customer claims
                $claims = Elements::call('Claims/ClaimsController')->getClaimsByCustomer($id);
                $this->view->set('claims_count', count($claims));
                $this->view->set('claims', $claims);
                $this->view->generateClaims();
                //customer quotes section

                $customerquotes = Elements::call('Quotes/QuotesController')->getQuotesByCustomer($id);


                $this->view->set('quote_count', count($customerquotes));

                $this->view->set('quotes', $customerquotes);


                $this->view->generateQuotesTable();


                //customer entities section

                $dbentities = Elements::call('Entities/EntitiesController')->getCustomerEntity($id, 'customers_id');


                if (!is_null($dbentities)) {


                    foreach ($dbentities as $entity) {


                        $entityobj = $this->model->createEmpty();


                        $entityobj->id = $entity['id'];

                        $entityobj->type = $entity['type'];

                        $entityobj->customerid = $id;


                        $entkeys = array_keys($entity['entity']);

                        $entityobj->summary = '<strong>' . str_replace('_', ' ', $entkeys[0]) . ':</strong> ' . $entity['entity'][$entkeys[0]]

                            . ' <strong>' . str_replace('_', ' ', $entkeys[1]) . ':</strong> ' . $entity['entity'][$entkeys[1]];


                        $entitylist[] = $entityobj;

                    }

                }


                //get generic entities list

                $entities = Elements::call('Entities/EntitiesController')->model->show();

                $genericlist = '<ul style=\"margin: 0px; padding: 0px; margin-left: 5px; list-style: none\">';


                foreach ($entities as $entity) {


                    $genericlist .= '<li>'

                        . '<a href=\"' . Url::base() . '/ajax/admin/customers/getfullentity/' . $entity->id . '/' . $id . '\" '

                        . 'data-target=\"#addnewentity\" data-backdrop=\"static\" data-toggle=\"modal\" '

                        . '>'

                        . 'Add New ' . $entity->name

                        . '</a>'

                        . '</li>';

                }


                $genericlist .= '</ul>';


                //add the add and edit modal

                $addnewentity = Overlays::Modal(['id' => 'addnewentity']);

                $this->set('addnewentity', $addnewentity);


                $this->view->set('generic_entity_links', $genericlist);

                $this->view->set('entitycount', count($entitylist));


                $this->view->generateEntitiesTable($entitylist);


                //customer tasks section

                $returnurl = $url . '/show/' . $id . '#tasks';

                $tasks = Elements::call('Tasks/TasksController')->getTasksByCustomer($id, true, ['url' => $returnurl, 'disable_edit' => true]);


                if (is_null($tasks)) {


                    $tasks['count'] = 0;

                    $tasks['html'] = Notifications::Alert('No tasks linked to this customer', 'info', true);

                }


                $this->view->set('taskcount', $tasks['count']);

                $this->view->set('tasks', $tasks['html']);


                //add the delete confirm modal

                $deletemodal = Overlays::confirm();

                $this->set('deletemodal', $deletemodal);


                $this->view->setViewPanel('customer-details');

            } else {


                Redirect::withNotice('Customer has not been found', 'error')
                    ->to($url);

            }

        }

    }

    public function loginmodal()
    {
        $customer = $this->model->where('id', Input::get('id'))->first();
        $this->view->getLoginModal($customer);
    }

    public function makeLoginCredentials()
    {
        $this->view->disable();
        $_ctrl = Elements::call('Users/UsersController');
        $_ctrl->setUserCredentials((object)Input::post());
        Redirect::withNotice('User credentials was updated')->to('/admin/customers');
    }

    /**
     * @acl\role editor
     * @acl\alias edit
     */
    public function edit()
    {

        if (!is_null(Input::get('id'))) {


            $customer = $this->model->findCustomer(Input::get('id'));


            //create the return url from the Navigation element

            $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'customers']);

            $url = $url . '/show/' . Input::get('id');


            $schematic = [

                'preventjQuery' => TRUE,

                'method' => 'POST',

                'action' => '/admin/customers/save/' . Input::get('id'),

                'controls' => [

                    'id' => ['hidden', 'id', $customer->customers_id],

                    'destination' => ['hidden', 'destination', $url],

                    'Full Names' => ['text', 'fullnames', $customer->name],

                    'Mobile Number' => ['text', 'mobileno', $customer->mobile_no],

                    'Date of Birth' => ['date', 'dob', date('d M Y', $customer->date_of_birth), ['format' => 'd M Y']],

                    'Email Address' => ['text', 'emailaddress', $customer->email],

                    'Postal Address' => ['text', 'postaladdress', $customer->postal_address],

                    'Postal Code' => ['text', 'postalcode', $customer->postal_code]

                ],

                'validation' => [

                    'fullnames' => [

                        'required' => 'Please enter your full names'

                    ],

                    'mobileno' => [

                        'required' => 'Please enter your mobile number'

                    ],

                    'emailaddress' => [

                        'required' => 'Please enter your email address',

                        'email' => 'Please enter valid email address'

                    ]

                ]

            ];


            $modal_settings = [

                'id' => 'editmodal',

                'formid' => 'editform',

                'role' => 'dialog',

                'title' => 'Customer Personal Details',

                'buttons' => [

                    'Cancel' => [

                        'class' => 'btn btn-default',

                        'data-dismiss' => 'modal'

                    ],

                    'Save Edits' => [

                        'type' => 'submit',

                        'class' => 'btn btn-primary',

                        'id' => 'save_button'

                    ]

                ]

            ];


            $form = Generate::Form('editform', $schematic);

            $eform = $form->render('horizontal', TRUE);


            $editform = Overlays::ModalDialog($modal_settings, $eform);

        } else {


            $editform = Notifications::Alert('No Customer ID sent', 'error', TRUE, TRUE);

        }


        $this->view->set('editform', $editform);

        $this->view->setViewPanel('customer-edit');

    }

    /**
     * @acl\role editor
     * @acl\alias "Add New Agent"
     */
    public function addAgent()
    {


        $customer = $this->model->find(Input::get('id'));


        //create the return url from the Navigation element

        $url = Elements::call('Navigation/NavigationController')->getUrl('customers');

        $url = $url . '/show/' . Input::get('id');


        //get the Agents element

        $agents = Elements::call('Agents/AgentsController')->retrieveAgents();


        foreach ($agents as $agent) {

            $agentslist[$agent->id] = $agent->names;

        }


        $schematic = [

            'preventjQuery' => TRUE,

            'method' => 'POST',

            'action' => '/admin/customers/agentsave/' . $customer->id,

            'controls' => [

                'id' => ['hidden', 'id', $customer->id],

                'destination' => ['hidden', 'destination', $url],

                'Linked Agent' => ['select', 'agent', $customer->insurer_agents_id, $agentslist]

            ],

            'validation' => [

                'agent' => [

                    'required' => 'Please enter the linked agent'

                ]

            ]

        ];


        $modal_settings = [

            'id' => 'customermodal',

            'formid' => 'customereditform',

            'role' => 'dialog',

            'title' => 'Linked Agent Details',

            'buttons' => [

                'Cancel' => [

                    'class' => 'btn btn-default',

                    'data-dismiss' => 'modal'

                ],

                'Save Edits' => [

                    'type' => 'submit',

                    'class' => 'btn btn-primary',

                    'id' => 'savebutton'

                ]

            ]

        ];


        $form = Generate::Form('customereditform', $schematic);

        $eform = $form->render('horizontal', TRUE);


        $editform = Overlays::ModalDialog($modal_settings, $eform);


        $this->view->set('editform', $editform);

        $this->view->setViewPanel('customer-edit');

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


                    //create the insert row

                    if ($dbcol == 'date_of_birth') {

                        $db->{$dbcol} = strtotime($doc->worksheet->rows[$pickedcol . ',' . $r]);

                    } else {

                        $db->{$dbcol} = $doc->worksheet->rows[$pickedcol . ',' . $r];

                    }

                }


                $db->enabled = 'yes';

                $db->regdate = time();

            }


            $db->save();


            if (!$db->hasNoErrors()) {

                $errorlog[] = $db->errors;

            }

        }


        if (count($errorlog) == 0) {


            Redirect::withNotice('The customer records have been inserted', 'success')
                ->to(Url::route('/admin/customers/{action}'));

        } else {

            var_dump($errorlog);

        }

    }


    public function export()
    {

        $this->view->disable();
        if (!is_null(Input::post('export'))) {

            $dbcustomers = $this->model->getCustomers();


            foreach ($dbcustomers as $customer) {


                if (is_object($customer)) {


                    $policies = Elements::call('Policies/PoliciesController')->getPoliciesByCustomer($customer->id);

                    $customer->policies = count($policies);


                    //customer quotes section

                    $quotes = Elements::call('Quotes/QuotesController')->getQuotesByCustomer($customer->id);

                    $customer->qcount = count($quotes);
                    //customer claims
                    $claims = Elements::call('Claims/ClaimsController')->getClaimsByCustomer($customer->id);
                    $customer->claims = count($claims);
                    $customers[] = $customer;

                }

            }
            $columns = ['id' => 'ID',

                'name' => 'Full Name',

                'email' => 'Email Address',

                'phone' => 'Phone Number',

                'policies' => 'Policies',
                'qcount' => 'Quotes Generated',
                'claims' => 'Filled Claims',
                'regdate' => 'Registration Date'

            ];


            $company = $this->model->table('own_company')->first();


            $doc = new Excel($company->name . ' Customer Listing', Input::post('filename'));

            $doc->generateDoc($columns, $customers, Input::post('format'));

        }

    }


    public function printer()
    {


        if (!is_null(Input::post('printer'))) {


            $this->view->disable();

            $customers = $this->model->getCustomers();


            $this->view->set('count', count($customers));

            $this->view->set('source', $customers);


            HTML::head();

            $this->view->generateTable();


            HTML::printPage();

        }

    }

    /**
     * @acl\role subscriber
     * @acl\action "delete"
     */
    public function delete()
    {

        if (!is_null(Input::post('delete'))) {


            $this->view->disable();

            $ids = Input::post('ids');
            $messages = '<h4>Delete Action:</h4><br/>';
            foreach ($ids as $id) {
                $cust = $this->model->where('id', '=', $id)->first();
                if ($this->canDeleteCustomer($id)) {
                    $this->model->where('id', '=', $id)->delete();
                    $messages .= '<span class="text-success"><i class="fa fa-trash-o"></i> Deleted <strong>' . $cust->name . '</strong></span><br/>';
                } else {
                    $messages .= '<span class="text-danger"><i class="fa fa-ban"></i> Could not delete <strong>' . $cust->name . '</strong>. </span><br/>';
                }
            }
            Session::flash('deleted', $messages);
            Redirect::to(Url::route('/admin/customers/{action}', ['action' => 'show']));

        }

    }

    private function canDeleteCustomer($id)
    {
        $quotes = Elements::call('Quotes/QuotesController')->getQuotesByCustomer($id);
        return empty($quotes);
    }

    /**
     * Delete customer entity
     */

    public function deleteEntity()
    {


        $id = Input::get('id');


        $customerid = array_pop(explode('/', Input::get('url')));

        $customerurl = Elements::call('Navigation/NavigationController')->getUrl('customers');


        $this->model->table('customer_entity_data')->where('id', $id)->delete();


        Redirect::withNotice('The customer entity has been deleted', 'success')
            ->to($customerurl . '/show/' . $customerid);

    }


    /**
     * Delete Customer Quotes
     */

    public function deleteQuotes()
    {


        $ids = Input::post('ids');


        $quoteelm = Elements::call('Quotes/QuotesController');


        foreach ($ids as $id) {


            $quote = $quoteelm->model->find($id);

            $quoteelm->model->where('id', $id)->delete();

        }


        $customerurl = Elements::call('Navigation/NavigationController')->getUrl('customers');

        Redirect::withNotice('The customer quotes have been deleted', 'success')
            ->to($customerurl . '/show/' . $quote->customers_id);

    }


    /**
     * Delete Customer Policies
     */

    public function deletePolicies()
    {


        $ids = Input::post('ids');


        $policyelm = Elements::call('Policies/PoliciesController');


        foreach ($ids as $id) {


            $policy = $policyelm->model->find($id);

            $policyelm->model->where('id', $id)->delete();

        }


        $customerurl = Elements::call('Navigation/NavigationController')->getUrl('customers');

        Redirect::withNotice('The customer policies have been deleted', 'success')
            ->to($customerurl . '/show/' . $policy->customers_id);

    }


    public function save()
    {


        if (Input::post('id') !== NULL) {
            $customer = $this->model->find(Input::post('id'));
        } else {

            $customer = $this->model;

            $customer->regdate = time();

        }


        $customer->name = Input::post('fullnames');
        $customer->id_number = Input::post('id_number');
        $customer->mobile_no = Input::post('mobileno');

        $customer->email = Input::post('emailaddress');

        $customer->postal_address = Input::post('postaladdress');

        $customer->postal_code = Input::post('postalcode');

        $customer->date_of_birth = strtotime(Input::post('dob'));


        $save = $customer->save();

        if (array_key_exists('ERROR', $save) == FALSE) {


            Redirect::withNotice('The customer details have been saved', 'success')
                ->to(Input::post('destination'));

        } else {


            Redirect::withNotice($save['ERROR'], 'error')
                ->to(Input::post('destination'));

        }

    }


    public function agentSave()
    {


        $customer = $this->model->find(Input::post('id'));


        $customer->insurer_agents_id = Input::post('agent');

        $save = $customer->save();


        if (array_key_exists('ERROR', $save) == FALSE) {


            Redirect::withNotice('The linked agent has been saved', 'success')
                ->to(Input::post('destination'));

        } else {


            Redirect::withNotice($save['ERROR'], 'error')
                ->to(Input::post('destination'));

        }

    }


    public function signUpsByMonth($settings = [])
    {


        $subdata = $this->model->getCustomersByMonth();

        $months = array_keys($subdata);


        $subchart = new Charts($settings);


        $subchart->title('Customer Signup by Month');

        $subchart->setup([

            'type' => "'bar'"

        ]);


        $subchart->xAxis([

            'categories' => $months

        ]);


        $subchart->yAxis([

            'min' => 0,

            'title' => [

                'text' => "'Customers'",

                'align' => "'high'"

            ],

            'labels' => [

                'overflow' => "'justify'"

            ]

        ]);


        $subchart->tooltip([

            'valueSuffix' => "' customers'"

        ]);


        $subchart->plotOptions([

            $settings['type'] => [

                'dataLabels' => [

                    'enabled' => 'true'

                ]

            ]

        ]);


        $subchart->legend([

            'layout' => "'horizontal'",

            'align' => "'right'",

            'verticalAlign' => "'bottom'",

            'x' => "0",

            'y' => "100",

            'floating' => 'true',

            'borderWidth' => 0,

            'backgroundColor' => "((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF')",

            'shadow' => 'true'

        ]);


        $seriesmethod = $settings['type'] . 'Series';

        $subchart->$seriesmethod($subdata, ['Confirmed', 'Not Confirmed']);


        $bar = $subchart->build();


        $this->view->set('barchart', $bar);

        $this->view->setViewPanel('customer-signup');

    }


    /**
     * Returns any entities registared under the particular customer
     *
     * @param type $id
     * @return object $retrieved_entities
     */

    public function getEntities($id)
    {


        $entities = $this->model->table('customer_entity_data')->where('customers_id', $id)->show();

        return $entities;

    }


    /**
     * Retrieves the full entity form for the sent entity
     */

    public function getFullEntity()
    {


        $id = Input::get('id');


        $entityele = Elements::call('Entities/EntitiesController');


        $entity = $entityele->getEntityById($id);


        $entityform = $entityele->getFullEntityForm($entity->forms_id);

        $etype = $entityele->getEntityTypes($entity->entity_types_id)[$entity->entity_types_id];


        $customerid = array_pop(explode('/', Input::get('url')));


        $params['entityid'] = $id;

        $params['customerid'] = $customerid;

        $params['formid'] = $entity->forms_id;


        $this->view->makeEntityForm($entityform, $etype, $params);

    }


    public function editFullEntity()
    {


        $id = Input::get('id');


        //get the form id from the entities table which is listed in the customer entities table

        $entity = Elements::call('Entities/EntitiesController');

        $entities = $entity->getCustomerEntity($id);


        //process the form values to make the script

        foreach ($entities[0]['entity'] as $key => $value) {

            $rawscript .= "$('#" . $key . "').val('" . trim($value) . "');\n";

        }


        $script = HTML::script('$(function() {' . $rawscript . '});', 'script', true);


        $entityid = $entity->getEntityDataById($id)->entities_id;

        $eform = $entity->getEntityById($entityid);


        //get the form

        $form = Elements::call('Forms/FormsController')->processFormAttributes($eform->forms_id, $entityid);


        //$customerid = array_pop(explode('/',Input::get('url')));


        $params['entityid'] = $entityid;

        $params['customerid'] = Input::get('customerid');


        $this->view->displayEditEntity($id, $script, $form, $params);

    }

    public function getCustomerByUserId($user_id)
    {
        $user = Elements::call('Users/UsersController')->model->where('id', $user_id)->first();
        if (empty($user->customers_id)) {
            return null;
        }
        return (object)$this->getCustomerDataArray($user->customers_id);
    }

    private function getCustomerDataArray($customer_id = null)
    {
        if (empty($customer_id)) {
            if (empty($customer_id = Input::post('customer'))) {
                return [];
            }
        }
        $_customer = $this->getCustomerById($customer_id, false);
        $info = get_object_vars($_customer);
        $product_datas = json_decode($info['additional_info']);
        unset($info['additional_info']);
        $info['dob'] = date('Y-m-d', $info['date_of_birth']);
        $info['mobile'] = $info['mobile_no'];
        $info['address'] = $info['postal_address'];
        $info['code'] = $info['postal_code'];
        $info['surname'] = substr($info['name'], 0, strpos($info['name'], ' '));
        $info['names'] = substr($info['name'], 1 + strpos($info['name'], ' '));
        $info['id_passport_no'] = $info['id_passport_number'] = $_customer->id_number;
        $the_arrays = $info;
        if (count($product_datas)) {
            foreach ($product_datas as $for_product) {
                $the_arrays = array_merge($the_arrays, get_object_vars($for_product));
            }
        }
        return $the_arrays;
    }

    public function getCustomerById($id, $format = 'json')
    {
        $customer = $this->model->where('id', '=', $id)->first();
        if ($format == 'json') {
            return json_encode([

                'email' => $customer->email,

                'phone' => $customer->mobile_no

            ]);

        } else {

            return $customer;

        }

    }


    public function getCustomerByName($name, $format = 'json')
    {


        $customers = $this->model->where('name', 'LIKE', '%' . $name . '%')->show();


        if ($format == 'json') {

            return $this->returnCustomerViaJson($customers);

        } elseif ($format == 'select') {
            $list = [];
            if (count($customers) >= 1) {
                foreach ($customers as $customer) {
                    $list[] = ['text' => ucfirst($customer->name), 'id' => $customer->id, 'email' => $customer->email, 'phone' => $customer->mobile_no];
                }
            }
            $full_list['query'] = $name;
            $full_list['results'] = $list;
            return json_encode($full_list);
        } else {

            return $customers;

        }

    }


    public function returnCustomerViaJson($customers)
    {


        if (count($customers) >= 1) {


            foreach ($customers as $customer) {

                $list[] = ['value' => ucfirst($customer->name), 'data' => $customer->id];

            }

        } else {


            $list[] = ['value' => 'No Customers Found', 'data' => NULL];

        }


        $full_list['query'] = 'Unit';

        $full_list['suggestions'] = $list;


        return json_encode($full_list);

    }


    public function saveNewEntity()
    {


        $this->view->disable();


        $formid = Input::post('formid');

        $entityid = Input::post('entityid');

        $customerid = Input::post('customerid');


        $eformkeys = json_decode(Input::post('index_' . $formid), true);


        foreach ($eformkeys as $ekey) {

            $einfo[$ekey] = Input::post($ekey);

        }


        $save = Elements::call('Entities/EntitiesController')
            ->saveEntityDataRemotely($customerid, $entityid, json_encode($einfo));


        if (!array_key_exists('ERROR', $save)) {


            Redirect::withNotice('The customer entity has been saved', 'success')
                ->to(Input::post('destination'));

        }

    }


    public function saveEntityEdit()
    {


        $entity = Elements::call('Entities/EntitiesController')
            ->model->customerEntityData(Input::post('customerentityid'));


        $entityid = Input::post('entityid');

        $eformkeys = json_decode(Input::post('index_' . $entityid), true);


        foreach ($eformkeys as $ekey) {

            $einfo[$ekey] = Input::post($ekey);

        }


        $entity->entity_values = json_encode($einfo);


        $save = $entity->save();


        if (!array_key_exists('ERROR', $save)) {

            Redirect::withNotice('The customer entity edits have been saved', 'success')
                ->to(Input::post('destination'));

        } else {

            echo $save['ERROR'];

        }

    }



    //feature

    /**
     * Save customer data from other elements. Also sets customer_id to session
     * @param null $product
     * @param array|null $data
     * @param bool $internal
     * @return bool
     */
    public function saveCustomer($product = null, $data = null, $internal = true)
    {
        $user_ctrl = Elements::call('Users/UsersController');

        $exist = false;

        if (empty($data))

            $data = Input::post();

        $cust_check = $this->model
            ->where('email', $data['email'])
            ->orWhere('id_number', $data['id_passport_no'])->get();

        if (count($cust_check)) {

            $exist = true;
        }


        $customer = $this->model->find(['email' => $data['email']]);

        $customer->name = ucwords($data['FullName']);

        $customer->mobile_no = $data['mobile'];

        $customer->email = $data['email'];

        $customer->enabled = 'yes';

        $customer->postal_address = $data['address'];

        $customer->date_of_birth = strtotime($data['dob']);

        $customer->regdate = time();

        $customer->postal_code = $data['code'];

        $customer->insurer_agents_id = Session::get('agentsid');

        $customer->id_number = $data['id_passport_no'];

        if (!$exist) {

            $customer->additional_info = json_encode(

                [$product => array_except($data, ['FullName', 'mobile', 'address', 'dob', 'code'])]);

        } else {

            $already = get_object_vars(json_decode($customer->additional_info));

            $already[$product] = array_except($data, ['FullName', 'mobile', 'address', 'dob', 'code']);

            $customer->additional_info = json_encode($already);

        }
        $customer->save();

        if ($customer->hasNoErrors()) {

            $customer_id = $customer->last_altered_row;
            Session::set('customer_id', $customer_id);

            if (!$internal) {

                // if user exists check if verified
//                $user_ctrl->logInfo('Check if customer Exists...');
                if ($exist) {
//                    $user_ctrl->logInfo('Customer Exists...');
                    $customer_id = $customer->id;
                    Session::set('customer_id', $customer_id);

                    if ($user_ctrl->isCustomerloggedIn()) {
//                        $user_ctrl->logInfo('Customer is logged in!');

                        return $customer_id;
                    }

                    if ($user_ctrl->checkIfVerified($data['email'])) {
//                        $user_ctrl->logInfo('User is already verified...');

                        Session::set('customer_email', $data['email']);
                        Session::set('verified', true);
                        Session::set('step_feed', 'Your record was found! Please login!');

                        return $customer_id;

                    } else {
//                        $user_ctrl->logInfo('User is not verified...');
                        Session::set('sent_confirmation', 'Verify your Email Address! Check your email for a verification link!');
                        return $customer_id;
                    }


                    return $customer_id;
                }


                // create a login account for the customer send a verification email
//                $user_ctrl->logInfo('Attempting to create a user');
                $user_id = $user_ctrl->createLoginAccountRemotely([

                    'email' => $data['email'],

                    'customer_name' => ucwords($data['surname'] . ' ' . $data['names']),

                    'element' => $product,

                    'exist' => $exist,

                    'customer_id' => $customer_id,

                    'acl' => 'customer'

                ]);

                if ($user_id) {
//                    $user_ctrl->logInfo('User has been created user#: ' . $user_id);
                    return $customer_id;

                }

                return false;

            } else {
                return $customer->last_altered_row;
            }

        }

        return false;

    }


    public function attachAgentToCustomer($customer_id, $agent_id)
    {

        $this->model->attachAgentToCustomer($customer_id, $agent_id);

    }


    /**
     * Loads the login modal for the customer
     * @return mixed
     */

    public function loadLoginContainer()
    {

        return $this->view->customerLoginModal();

    }


    public function customerLoginForm($element)
    {

        return $this->view->customerLoginForm($element);

    }

    public function getCustomerByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

}

