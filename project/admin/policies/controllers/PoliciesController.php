<?php
namespace Jenga\MyProject\Policies\Controllers;

use Jenga\App\Html\Excel;
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Helpers\FileUpload;
use Jenga\App\Views\Notifications;
use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Facade\Sanitize;

use Jenga\MyProject\Elements;
use Jenga\MyProject\Services\Charts;

class PoliciesController extends Controller {
    
    public function index(){
        
        if(is_null(Input::get('action')) && is_null(Input::post('action'))){
            
            $action = 'show';
        }
        else{
            
            if(!is_null(Input::get('action')))                
                $action = Input::get('action');
            
            elseif(!is_null(Input::post('action')))                
                $action = Input::post ('action');
        }
        
        $this->$action();
    }
    
    private function _translatePolicies($rawpolicies, $use = 'internal'){
        
        if(is_array($rawpolicies)){
            
            foreach($rawpolicies as $policy){

                $newpolicy = $this->model->createEmpty();

                $insurer = Elements::load('Insurers/InsurersController@getInsurer', ['id'=>$policy->insurers_id])['name'];
                
                if($use == 'internal')
                    $txtinsurer = Sanitize::shorten($insurer, 20);
                else
                    $txtinsurer = $insurer;

                if($use == 'internal')
                    $newpolicy->id = $policy->id;
                
                $newpolicy->created = date('d-M-y',$policy->datetime);
                $newpolicy->policyno = $policy->policy_number;
                $newpolicy->issuedate = ($policy->issue_date == 0 ? 'Not Allocated' : date('d M Y',$policy->issue_date));
                $newpolicy->insurer = $txtinsurer;
                $newpolicy->validity = date('d M Y',$policy->start_date).' - '.date('d M Y',$policy->end_date);
                
                if($use == 'internal')
                    $newpolicy->customers_id = $policy->customers_id;
                
                $newpolicy->customer = Elements::load('Customers/CustomersController@find', ['id'=>$policy->customers_id])->name;            
                $newpolicy->product = Elements::load('Products/ProductsController@getProduct', ['id' => $policy->products_id])['name'];
                $newpolicy->status = ($policy->status=='' ? 'Not Issued' : $policy->status);

                $newpolicy->premium = $policy->currency_code.' '.number_format($policy->amount,2);
                
                if($policy->status == 'issued' && $use == 'internal'){
                    $newpolicy->image = '<img '.Notifications::tooltip('Policy issued').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/done_icon.png" width="20" />';
                }
                elseif($use == 'internal'){
                    $newpolicy->image = '<a href="'.SITE_PATH.'/admin/policies/processissue/'.$policy->id.'" >'
                                            . '<img '.Notifications::tooltip('Click to issue policy').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/issue_policy_icon.png" width="20" />'
                                        . '</a>';
                }
                
                $policies[] = $newpolicy;
            }
        }
        else{
            
            $policy = $rawpolicies;
            
            $newpolicy = $this->model->createEmpty();

            $insurer = Elements::load('Insurers/InsurersController@getInsurer', ['id'=>$policy->insurers_id])['name'];
            $txtinsurer = Sanitize::shorten($insurer, 20);

            $newpolicy->id = $policy->id;
            $newpolicy->policyno = $policy->policy_number;
            $newpolicy->issuedate = ($policy->issue_date == 0 ? 'Not Allocated' : date('d M Y',$policy->issue_date));
            $newpolicy->insurer = $txtinsurer;
            $newpolicy->validity = date('d M Y',$policy->start_date).' - '.date('d M Y',$policy->end_date);
            $newpolicy->customer = Elements::load('Customers/CustomersController@find', ['id'=>$policy->customers_id])->name;            
            $newpolicy->product = Elements::load('Products/ProductsController@getProduct', ['id' => $policy->products_id])['name'];
            $newpolicy->status = ($policy->status=='' ? 'Not Issued' : $policy->status);
            
            if($policy->status == 'issued'){
                $newpolicy->image = '<a href="'.SITE_PATH.'/admin/policies/processissue/'.$policy->id.'" >'
                                        . '<img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/issue_policy_icon.png" width="20" />'
                                    . '</a>';
            }
            else{
                $newpolicy->image = '<a href="'.SITE_PATH.'/admin/policies/processissue/'.$policy->id.'" >'
                                        . '<img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/done_icon.png" width="20" />'
                                    . '</a>';
            }
            
            $policies = $newpolicy;
        }
        return $policies;
    }
    
    /**
     * @jacl(action="read", alias="Read Policies")
     */
    public function show($search = NULL){
        
        if(is_null($search)){
            $rawpolicies = $this->model->show();
        }
        else{            
            $results = $this->model->search($search);
            
            $this->set('condition', $results['condition']);
            $rawpolicies = $results['result'];
        }
        
        $policies = $this->_translatePolicies($rawpolicies);
        
        $this->set('count', $this->model->count());
        $this->set('source',$policies);
        $this->set('search',$this->_searchform());
        
        $this->view->generateTable();
    }
    
    public function getUnprocessedPolicies(){
        
        $dbpolicies = $this->model->where('status','')->show();
        
        $policies = $this->_translatePolicies($dbpolicies);
        
        $this->set('count', $this->model->count());
        $this->set('source',$policies);
        $this->set('search',$this->_searchform());
        
        $this->view->generateMiniTable('unprocessed');
    }
    
    public function getExpiringPolicies(){
       
        $now = time();
        $later = strtotime("+30 days");
        $dbpolicies = $this->model->where('end_date','BETWEEN',[$now, $later])->show();
        
        $policies = $this->_translatePolicies($dbpolicies);
        
        $this->set('count', $this->model->count());
        $this->set('source',$policies);
        $this->set('search',$this->_searchform());
        
        $this->view->generateMiniTable('expiring');
    }
    
    public function add(){ 
        
        if(!is_null(Input::get('id'))){
            
            $customer = Elements::call('Customers/CustomersController')->getCustomerById(Input::get('id'),'raw');
            $quotes = Elements::call('Quotes/QuotesController')->getQuotesByCustomer($customer->id);
            
            //get product 
            $product = Elements::call('Products/ProductsController');

            //get insurer
            $insurer = Elements::call('Insurers/InsurersController');
            
            //process quotes
            if(!is_null($quotes)){
                
                foreach($quotes as $quote){

                    $amounts = json_decode($quote->amount);

                    foreach($amounts as $insurerid => $price){

                        $list[] = ['id' => $quote->id.':'.$insurerid.':'.$price,
                                   'product' => $product->getProduct($quote->product_id,'array')['name'],
                                   'datetime' => $quote->datetime,
                                   'insurer' => $insurer->getInsurer($insurerid)['name'],
                                   'amount' => $price];
                    }
                }

                $customer->quotes = $list;
            }
            else{
                $customer->quotes = NULL;
            }
        }

        $this->view->addPolicyForm($customer);
    }
    
    public function edit(){
        $policy = $this->model->find(Input::get('id'))->data;
        
        //get the customer details from the Customers element
        $policy->customer = Elements::call('Customers/CustomersController')->find($policy->customers_id);

        $quote = $this->model->getDataFromQuote($policy->customer_quotes_id);
        
        //get Entities element
        $entity = Elements::call('Entities/EntitiesController')->getCustomerEntity($quote->customer_entity_data_id);
        
        $policy->refno = $quote->refno;
        $policy->coverage = $quote->quotes;
        
        $policy->product = Elements::call('Products/ProductsController')->getProduct($policy->products_id);
        $policy->insurer = Elements::call('Insurers/InsurersController')->getInsurer($policy->insurers_id);
        $policy->dategenerated = date('d F Y',$policy->datetime);
        $policy->coverage = ['product'=>$quote->product_info,'entity'=>$entity];
        $policy->premium = $quote->amount;        
        
        $this->view->editPolicy($policy);
    }
    
    public function import(){
        
        $uploadfolder = Input::post('upload_folder');
        $handler = new FileUpload('file_import');
        
        if($handler->handleUpload($uploadfolder)){
            
            $this->view->enable();
            $filename = ABSOLUTE_PATH .DS. 'tmp' .DS. $_FILES['file_import']['name'];
            
            $excel = new Excel();
            $doc = $excel->importDoc($filename);
            
            $doc->worksheet->name = $_FILES['file_import']['name'];
            $doc->worksheet->filename = $filename;
            
            $this->view->matchImportColumns($doc);
        }
    }
    
    public function integrateImport(){
        
        $this->view->disable();
        
        $columns = Input::post('columns');
        $db = $this->model;
        
        //reimport document
        $excel = new Excel();
        $doc = $excel->importDoc(Input::post('filepath'));
        
        //starts at 2 to jump column titles row
        $errorlog = [];
        for($r=2; $r<=$doc->worksheet->rowcount; $r++){
            
            for($c=1; $c<=count($columns); $c++){

                $colcount = ($c-1);
                $coldata = explode(',', $columns[$colcount]);
                $colid = $coldata[0];
                $dbcol = $coldata[1];

                //get columns that havent been skipped
                if(!is_null(Input::post('importselect_'.$colid)) && Input::post('importselect_'.$colid) != ''){
                    
                    $pickedcol = Input::post('importselect_'.$colid);
                    
                    switch ($dbcol) {
                        
                        case 'customer_name':
                            //process customer name
                            $customername = $doc->worksheet->rows[$pickedcol.','.$r];
                            
                            $customerelm = Elements::call('Customers/CustomersController');
                            $customer = $customerelm->getCustomerByName($customername,'raw');
                            
                            if(count($customer) == 0){
                                
                                //if the customer doesnt exist create new record
                                $customermdl = $customer->model;
                                
                                $customermdl->name = $customername;
                                $customermdl->regdate = time();
                                $customermdl->insurer_agents_id = 0;
                                
                                //save the customer
                                $save = $customermdl->save();
                                
                                $customer_record_id = $save['last_altered_row'];
                            }
                            else{
                                
                                //get the customer record
                                $customer_record_id = $customer[0]->id;
                            }
                            
                            $db->customers_id = $customer_record_id;
                            break;
                            
                        case 'product_name':
                            
                            $productname = $doc->worksheet->rows[$pickedcol.','.$r];
                            
                            //get the products_id
                            $productelm = Elements::call('Products/ProductsController');
                            $products = $productelm->model->where('name','LIKE','%'.$productname.'%')->show();
                            
                            if(count($products) >= 1){
                                
                                $db->products_id = $products[0]->id;
                            }
                            else{
                                $errorlog['products'][$pickedcol.','.$r] = 'No products named - '.$productname.' in the listed products"';
                            }
                            break;
                        
                        case 'insurer_name':
                            
                            $insurer = $doc->worksheet->rows[$pickedcol.','.$r];
                            
                            //get the insurer
                            $insurerelm = Elements::call('Insurers/InsurersController');
                            $insurers = $insurerelm->model->where('name','LIKE',$insurer)->show();
                            
                            if(count($insurers) >= 1){
                                
                                $db->insurers_id = $insurers[0]->id;
                            }
                            else{
                                $errorlog['insurers'][$pickedcol.','.$r] = 'No insurer named - '.$productname.' in the listed insurance companies"';
                            }
                            break;

                        case 'issue_date':
                        case 'start_date':
                        case 'end_date':
                            
                            $date = $doc->worksheet->rows[$pickedcol.','.$r];
                            $db->{$dbcol} = strtotime($date);
                            break;
                        
                        default:
                            //create the insert row
                            $db->{$dbcol} = $doc->worksheet->rows[$pickedcol.','.$r];
                            break;
                    }
                }
                
                $db->regdate = time();
            }
            
            $save = $db->save();
                
            if(array_key_exists('ERROR', $save)){
                $errorlog['database'][$pickedcol.','.$r] = $save['ERROR'];
            }
        }
        
        if(count($errorlog) == 0){
            
            Redirect::withNotice('The customer records have been inserted','success')
                    ->to(Url::route('/admin/customers/{action}'));
        }
        else{
            
            $this->view->showImportErrors($errorlog);
        }
    }
    
    public function export(){
        
        $this->view->disable();
        $dbpolicies = $this->model->getPolicies();
        
        $policies = $this->_translatePolicies($dbpolicies,'export');
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
        $doc = new Excel($company->name.' Policies Listing',  Input::post('filename'));
        
        $doc->generateDoc($columns, $policies, Input::post('format'));
    }
    
    public function printer(){
        
        $this->view->disable();
        
        $dbpolicies = $this->model->getPolicies();
        $policies = $this->_translatePolicies($dbpolicies,'printer');
        
        $this->view->set('count',count($policies));
        $this->view->set('source',$policies);

        HTML::head();
        $this->view->generateTable();

        HTML::printPage();
    }

    public function savePolicyEdit(){
        
        $policy = $this->model->find(Input::post('id'));
        
        $policy->policy_number = Input::post('policynumber');
        $policy->issue_date = strtotime(Input::post('issuedate'));
        $policy->start_date = strtotime(Input::post('startdate'));
        $policy->end_date = strtotime(Input::post('enddate'));
        
        $save = $policy->save();
        
        if(!array_key_exists('ERROR', $save)){
            
            $policyurl = Elements::call('Navigation/NavigationController')->getUrl('policies');
            
            Redirect::withNotice('The policy changes have been added', 'success')
                    ->to($policyurl);
        }
        else{
            
            echo $this->model->getLastQuery();
        }
    }
    
    public function search() {
        
        $this->show(Input::post());
    }
    
    public function getCustomer(){
        
        $this->view->disable();
        $name = Input::request('query');
        
        $customers = $this->model->table('customers')->where('name','LIKE','%'.$name.'%')->show();
        
        if(count($customers)>=1){
            
            foreach($customers as $customer){

                $list[] = ['value' => ucfirst($customer->name),'data' => $customer->id];
            }
        }
        else{
            
            $list[] = ['value' => 'No Customers Found', 'data'=> NULL];
        }
        
        $full_list['query'] = 'Unit';
        $full_list['suggestions'] = $list;
        
        echo json_encode($full_list);
    }
    
    public function getQuotes(){
        
        $this->view->disable();
        
        //get the customers quotes
        $id = Input::request('id');
        $quotes = $this->model->table('customer_quotes')
                                ->where('customers_id','=',$id)
                                ->where('status','IN',['new','pending'])
                            ->show();
                    
        //get product 
        $product = Elements::call('Products/ProductsController');
        
        //get insurer
        $insurer = Elements::call('Insurers/InsurersController');
        
        if(count($quotes)>=1){
            foreach($quotes as $quote){
                
                $amounts = json_decode($quote->amount);
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
                        'amount' => $amounts->total_net_premiums,
                        'insurer' => $insurer->getInsurer($amounts->insurer_id)['name']
                    ];
//                }
            }
            
            $selectquotelist = $this->view->getQuoteList($list);
            $return = [
                'status' => true,
                'content' => $selectquotelist
            ];
        }
        else{
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
        echo json_encode($return);
    }
    
    public function createPolicy($offer = null){
        
        if(!is_null(Input::get('id'))){
            $quoteid = Input::get('id');


        }
        elseif(!is_null($offer)){
            
            $quoteinfo = explode(':',$offer);
            $quoteid = $quoteinfo[0];
            
            $insurerid = $quoteinfo[1];
            $price = $quoteinfo[2];
        }
        else{
            
            $quoteinfo = explode(':',Input::post('quotes'));
            $offer = Input::post('quotes');
            
            $quoteid = $quoteinfo[0];
            $insurerid = $quoteinfo[1];
            $price = $quoteinfo[2];
        }
        
        $data = $this->model->getDataFromQuote($quoteid);        
        $policy = $this->model->createEmpty();
        
        if(!is_null($offer)){
            $policy->offer = $offer;
        }

        $quote_amounts = json_decode($data->amount);
        if(!isset($price))
            $price = $quote_amounts->total_net_premiums;

        if(!isset($insurerid))
            $insurerid = $quote_amounts->insurer_id;
        
        //get the customer details from the Customers element
        $policy->customer = Elements::load('Customers/CustomersController@find', ['id'=>$data->customers_id]);
        
        //get product details from Product element
        $policy->product = Elements::load('Products/ProductsController@getProduct', ['id'=>$data->products_id]);
        
        //get insurer details from Insurer element
        $policy->insurer = Elements::call('Insurers/InsurersController')->getInsurer($insurerid);
        
        //get Entities element
        $entity = Elements::call('Entities/EntitiesController')->getCustomerEntity($data->customer_entity_data_id);
        
        //get the customer quote id
        $policy->quoteid = $quoteid;
        
        $policy->startdate = (!is_null($data->start_date) ? date('d F Y',$data->start_date) : '');
        $policy->enddate = (!is_null($data->end_date) ? date('d F Y',$data->end_date) : '');
        $policy->dategenerated = date('d F Y',(!is_null($data->datetime) ? $data->datetime : time()));
        
        $policy->no = '--not issued--';
        
        //get quote status list from Quote element
        $statuslist = Elements::call('Quotes/QuotesController')->statuslist;
        $policy->quotestatus = $statuslist[$data->status];
        
        $policy->coverage = ['product'=>$data->product_info,'entity'=>$entity];
        
        $policy->currency_code = 'ksh';
        $policy->amount = $price;
        $policy->premium = 'ksh '.number_format($price,2);
        $this->view->addPolicy($policy);
    }
    
    public function savePolicy(){
        
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
        
        $policy->status = Input::post('status');
        $policy->currency_code = Input::post('code');
        $policy->amount = Input::post('amount');
        
        //update quote status
        Elements::call('Quotes/QuotesController')->saveStatus($policy->customer_quotes_id, Input::post('offer'), 'policy_created');
        
        $policy->save();
        
        if($policy->hasNoErrors()){
            Redirect::withNotice('The policy has been saved')
                    ->to(Url::base().'/admin/policies/uploaddocs/'.$policy->last_altered_row);
//                    ->to(Url::base().'/admin/policies/issuepolicy/'.$policy->last_altered_row);
        }
    }

    public function uploadDocs(){
        $policy_docs = $this->model->getPolicyDocs(Input::get('id'));

        $this->view->set('id', Input::get('id'));
        $this->view->set('docs_count', $policy_docs['count']);

        if ($policy_docs['count']){
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
    public function deletedoc(){
        $policy_id = Input::get('policy_id');
        $document_id = Input::get('id');

        //delete document from Documents element
        $doc = Elements::call('Documents/DocumentsController')->deleteDoc($document_id);

        if ($doc !== FALSE) {
            Redirect::withNotice('The linked policy document has been deleted')
                ->to('/admin/policies/uploaddocs/'.$policy_id);
        }
    }
    
    public function issuePolicy(){
        
        $policy = $this->model->find(Input::get('id'))->data;
        
        $this->view->issuePolicy($policy);
    }
    
    public function processIssue(){
        
        $policy = $this->model->find(Input::get('id'))->data;
        $this->view->issuePolicy($policy,'process-issue');
    }
    
    public function saveIssue(){
        
        $this->view->disable();
        
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'policies']);
        
        $policy = $this->model->find(Input::post('policyid'));
        
        if(array_key_exists('btnsubmit', Input::post())){
            
            $policy->policy_number = Input::post('policynumber');
            $policy->issue_date = strtotime(Input::post('issuedate'));
            $policy->status = 'issued';

            $policy->save();

            if(Input::post('ajax')!='yes'){
                Redirect::withNotice('The policy details have been saved', 'success')
                        ->to($url);
            }
            else{

                $msg = new Notifications();
                $msg->setMessage('success','The policy details have been saved');

                Notifications::Alert('The policy details have been saved', 'success');
            }
        }
        else{
            
            Redirect::withNotice('The policy to be processed later', 'notice')
                        ->to($url);
        }
         
    }
    
    public function issueBatch(){
       
       $ids = Input::post('ids');
       
       foreach($ids as $id){
           
           $policies[] = $this->model->find($id)->data;
       }
       
       $this->view->batchPolicy($policies);
    }


    public function delete(){
        
        $ids = Input::post('ids');
        
        foreach($ids as $id){
            
            $this->model->where('id','=',$id)->delete();
        }
        
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'policies']);
        Redirect::withNotice('The policies have been deleted ', 'success')
                ->to($url);
    }
    
    private function _searchform(){
        
        //generate products list
        $products = Elements::load('Products/ProductsController@getProduct');
        
        foreach($products as $product){            
            $productlist[$product->id] = $product->name;
        }
        
        //generate insurer list
        $insurers = Elements::load('Insurers/InsurersController@getInsurer');
        
        foreach($insurers as $insurer){
            $insurerlist[$insurer->id] = $insurer->name;
        }
        
        $searchform = [
            'title' => 'Policy Filter Form',
            'form' => [
                'preventjQuery' => TRUE,
                'method' => 'post',
                'action' => '/admin/policies/search',
                'controls' => [
                    'Customer Name' => ['text','name',''],
                    'Products' => ['select','product','',$productlist],
                    'Insurers' => ['select','insurer','',$insurerlist]                    
                ],
                'map' => [2,1]
            ]
        ];
        
        return $searchform;
    }
    
    public function getPoliciesByCustomer($id){
        
        $rawpolicies = $this->model->where('customers_id','=',$id)->show();        
        return $this->_translatePolicies($rawpolicies);
    }
    
    public function getPolicyByQuote($id){
        
        $dbpolicy = $this->model->where('customer_quotes_id','=',$id)->first();
        
        if(count($dbpolicy)>=1){
            return $this->_translatePolicies($dbpolicy);
        }
        else{
            return NULL;
        }
    }
    
    public function analysePoliciesByMonth($settings = array()){
        
        $policies = $this->model->getPolicyAnalysis();
        $months = $policies['months'];
        unset($policies['months']);
       
        $pchart = new Charts($settings);
        
        if($settings['type'] == 'stackedcolumn')
            $type = 'column';
        else
            $type = $settings['type'];
        
        $pchart->setup(['type'=>$type]);
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
        
        $seriesmethod = $settings['type'].'Series';
        $pchart->$seriesmethod($policies,['Not Issued','Issued']);  
        
        $quoteschart = $pchart->build();
        
        $this->view->set('monthlypolicies', $quoteschart);
        $this->view->setViewPanel('monthly-policies');
    }
}