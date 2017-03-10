<?php
namespace Jenga\MyProject\Customers\Controllers;

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

use Jenga\MyProject\Elements;
use Jenga\MyProject\Services\Charts;

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

        $this->view->set('addform', $addform);
        $this->view->setViewPanel('customer-add');
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

    public function show()
    {

        if (is_null(Input::get('id'))) {

            $dbcustomers = $this->model->getCustomers();

            foreach ($dbcustomers as $customer) {

                if (is_object($customer)) {

                    $policies = Elements::call('Policies/PoliciesController')->getPoliciesByCustomer($customer->id);
                    $customer->policies = count($policies);

                    //customer quotes section
                    $quotes = Elements::call('Quotes/QuotesController')->getQuotesByCustomer($customer->id);
                    $customer->qcount = count($quotes);
                    $customers[] = $customer;
                }
            }

            $this->view->set('count', count($customers));
            $this->view->set('source', $customers);

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
                    var_dump($dbcol);
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
                    $customers[] = $customer;
                }
            }

            $columns = ['id' => 'ID',
                'name' => 'Full Name',
                'email' => 'Email Address',
                'phone' => 'Phone Number',
                'policies' => 'Policies',
                'qcount' => 'Quotes Generated',
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

    public function delete()
    {

        if (!is_null(Input::post('delete'))) {

            $this->view->disable();
            $ids = Input::post('ids');

            foreach ($ids as $id) {

                //$this->model->connectUsers();
                $this->model->connectQuotes();

                $this->model->where('id', '=', $id)->delete();
            }

            Redirect::withNotice('The customer record(s) have been deleted', 'success')
                ->to(Url::route('/admin/customers/{action}', ['action' => 'show']));
        }
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

    public function signUpsByMonth($settings = array())
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

    public function getCustomerById($id, $format = 'json')
    {

        $customer = $this->model->where('id', $id)->first();

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
     * @param array|null $data
     * @return bool
     */
    public function saveCustomer($data = null)
    {
        if (empty($data))
            $data = Input::post();
        $customer = $this->model->find(['email' => $data['email']]);
        if ($customer->count() > 0) {
            Session::set('customer_id', $customer->id);
            return $customer->id;
        }

        $customer->name = $data['surname'] . ' ' . $data['names'];
        $customer->mobile_no = $data['mobile'];
        $customer->email = $data['email'];
        $customer->enabled = 'yes';
        $customer->postal_address = $data['address'];
        $customer->date_of_birth = strtotime($data['dob']);
        $customer->regdate = time();
        $customer->postal_code = $data['code'];
        $customer->additional_info = json_encode(array_except($data,
            ['surname', 'names', 'mobile', 'address', 'dob', 'code']));


        $customer->save();
        if ($customer->hasNoErrors()) {
            Session::set('customer_id', $customer->last_altered_row);
            return $customer->last_altered_row;
        }
        return false;
    }

    public function attachAgentToCustomer($customer_id, $agent_id){
        $this->model->attachAgentToCustomer($customer_id, $agent_id);
    }
}