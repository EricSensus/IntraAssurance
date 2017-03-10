<?php
namespace Jenga\MyProject\Quotes\Models;

use Carbon\Carbon;
use Jenga\App\Helpers\Help;
use Jenga\App\Models\ORM;
use Jenga\App\Request\Input;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;
use Jenga\MyProject\Elements;

class QuotesModel extends ORM {
    
    public $table = 'customer_quotes';
    public $data;
    public $quotetypes = array();
    public $columns = [
        'id' => 'Quote No',
        'datetime' => 'Date Generated', 
        'customers_id' => 'Full Names', 
        'products_id' => 'Product', 
        'customer_entity_data_id' => 'Insured Entity',
        'insurer_agents_id' => 'Linked Agent',
        'status' => 'Status',
        'amount' => 'Quoted Amounts'
    ];
    
    public $statuslist = [
        'new' => 'New',
        'pending' => 'Response Pending',
        'policy_pending' => 'Policy Pending',
        'policy_created' => 'Complete',
        'rejected' => 'Rejected'
    ];

    public function returnColumns($return_values = FALSE){
        
        if($return_values == FALSE){            
            return $this->columns;
        }
        elseif($return_values == 'values') {            
            return array_values($this->columns);
        }
        elseif($return_values == 'keys'){            
            return array_keys($this->columns);
        }
    }
    
    /**
     * Get the quotes from table
     */
    public function getQuotes($showarchived = false){
        if(!is_null(Input::post('search'))){
            
            //add the name section
            if(Input::post('name')!=''){
                
                $this->select(TABLE_PREFIX.'customer_quotes.*, '
                        . TABLE_PREFIX.'customers.insurer_agents_id as customeragents, '
                        . TABLE_PREFIX.'customer_quotes.*');
                
                $this->join('customers', 
                            TABLE_PREFIX."customers.id = ".TABLE_PREFIX."customer_quotes.customers_id");
                
                $params = 'Name: '.Input::post('name').', ';
                $this->where(TABLE_PREFIX.'customers.name','LIKE','%'.Input::post('name').'%');
            }
            
            //add the refno section
            if(Input::post('refno')!=''){
                
                $params .= 'Reference Number: '.Input::post('refno').', ';
                $this->where('refno','LIKE','%'.Input::post('refno').'%');
            }
            
            //add the quote type section
            if(Input::post('qtype')!=''){
                
                $params .= 'Quote Type: '.Input::post('qtype').', ';
                $this->where('quotetype','=',Input::post('qtype'));
            }
            
            //add the quote status section
            if(Input::post('status') != ''){
                
                $params .= 'Quote Status: '.$this->statuslist[Input::post('status')].', ';
                $this->where('status', Input::post('status'));
            }
            
            $data = $this->orderBy(TABLE_PREFIX.'customer_quotes.id','DESC')->show();
            
            //store the search variables for use with the other tools
            $this->store();
            
            $data['terms'] = $params;
        }
        elseif(!is_null(Input::post('export')) || !is_null(Input::post('printer'))){
            
            if(Input::post('pages') == 'all_pages'){
                
                $data = $this->orderBy('id','DESC')->show();
            }
            else{
                
                $pages = explode(',',Input::post('pages'));
                
                $start = explode('-', $pages[0])[1];
                $length = explode('-', $pages[1])[1];
                //$end = explode('-', $pages[2])[1];
                
                $column = explode('-', $pages[3])[1];
                $order = $pages[4];
                
                $columns = $this->returnColumns('keys');
                $tablecol = $columns[$column];
                
                $data = $this->orderBy($tablecol, $order)->show([$start, $length]);
            }
        }
        else{
            
            if($showarchived == FALSE){                
                $this->where('status','!=','policy_created')->where('status', '!=', 'rejected');
            }
            elseif($showarchived == TRUE){                
                $this->where('status','policy_created')->orWhere('status', 'rejected');
            }

            $data = $this->orderBy('id','DESC')->show();
        }
        
        return $data;
    }
    
    public function getArchivedQuotes(){
        
        return $this->getQuotes(TRUE);
    }
    
    public function saveQuoteDocuments($quoteid, $docid){
        
        $doctable = $this->table('quotes_documents','NATIVE');
        $docs = $doctable->where('customer_quotes_id', $quoteid)
                         ->where('documents_id', $docid)
                         ->first();
                 
        if(is_null($docs) || count($docs) == 0){
            
            $doctable->customer_quotes_id = $quoteid;
            $doctable->documents_id = $docid;
            
            $doctable->save();
                    
            if($doctable->hasNoErrors()){
                return TRUE;
            }
        }
        else{
            return TRUE;
        }
    }
    
    /**
     * Get documents linked to quote
     */
    public function getQuoteDocuments($quoteid){
        
        $docs = $this->table('quotes_documents')
                        ->where('customer_quotes_id', $quoteid)
                        ->orderBy('id', 'DESC')
                      ->show();
        return $docs;
    }
    
    public function deleteQuoteDocuments($quoteid, $docid){
        
        $delete = $this->table('quotes_documents')
                          ->where('customer_quotes_id', $quoteid)
                          ->where('documents_id', $docid)
                        ->delete();
        
        return $delete;
    }

    /**
     * Joins the payment_confirmations table to the main quotes table
     */
    public function connectPayments(){
        
        $this->associate('payment_confirmation')
                ->using([
                    'type' => 'one-to-many',
                    'local' => 'id',
                    'foreign' => 'customer_quotes_id',
                    'on_update' => 'NO',
                    'on_delete' => 'delete'
                ]);
    }
    
    /**
     * Joins the customers table to the main quotes table
     */
    private function _connectCustomerProfiles(){
        
        $this->associate('customers')
                ->using([
                    'type' => 'one-to-one',
                    'local' => 'customers_id',
                    'foreign' => 'id'
                ]);
    }
    
    /**
     * Return product percentages
     * 
     * @return type
     */
    public function getProductAnalysis(){
        
        $data = $this->getQuotes();
        
        foreach($data as $quote){     
            
            $name = Elements::load('Products/ProductsController@getProduct', ['id'=>$quote->products_id])['name'];
            
            if(!is_null($name))
                $this->quotetypes[] = $name;
        }
        
        $product_count = count($this->quotetypes);
        $product_types = array_count_values($this->quotetypes);
        
        foreach($product_types as $type => $number){            
            $figure = ($number / $product_count)*100;
            $percentage[$type] = number_format($figure, 1, '.', '');
        }
        
        return $percentage;
    }
    
    /**
     * Returns quote numbers by month
     */
    public function getQuotesByMonth(){
        
        $monthdata = array();
        
        for($month = 1; $month <= (date('m')+12); $month++) {
            
            $start = mktime(0, 0, 0, $month, 1, date("Y",strtotime('-1 year')));
            $end = mktime(23, 59, 0, $month, date('t', $start), date("Y",strtotime('-1 year')));
            
            $this->select('id')
                ->where('datetime', '>=', $start)
                ->where('datetime', '<=', $end)
                ->show();
            
            $quotecount = $this->count();
            
            if($month >= 13){                
                $newmonth = $month-12;
                $dateObj   = \DateTime::createFromFormat('!m', $newmonth);
            }
            else{
                $dateObj   = \DateTime::createFromFormat('!m', $month);
            }
            
            $monthName = $dateObj->format('M'); // March            
            $monthdata[$monthName] = $quotecount;
        }
        
        return $monthdata;
    }
    
    public function getUnpaid(){
        
        $this->getQuotes();
        $this->connectPayments();
        
        $unpaid = array();        
        foreach($this->data as $quote){
            
            //search and see if there is a corresponding ID in the payment_confirmations table
            $this->find($quote->id)->payment_confirmation()->show();
            
            if($this->count() == '0'){
                
                //create an empty object to hold the data
                $data = $this->createEmpty();
                
                //connect to the  table
                $this->_connectCustomerProfiles();                
                $user = $this->find($quote->customers_id)->first(); 
                
                //assign the data from the different queries
                $data->fullname = is_null($user->name)||$user->name == '' ? 'Not Specified' : $user->name;
                $data->refno = $quote->refno;
                $data->quotetype = $quote->quotetype;
                $data->datetime = date('d M y',$quote->datetime);
                $data->customers_id = $user->customers_id;
                        
                $unpaid[] = $data;
            }
        }
        
        return $unpaid;
    }
    
    public function getIncomplete(){
        
        //set the table to customer_data        
        $results = $this->table('customer_data')
                        ->where('step2data', '=', '')
                        ->orWhere('step3data', '=', '')
                        ->show();
        
        //$incomplete['count'] = $this->count();
        $incomplete['count'] = count($results);
                
        foreach($results as $result){
            
            $data = $this->createEmpty();
            
            //connect to User Profiles
            $user = $this->_getUser($result->customers_id);
            
            //process last step
            $step = explode('_', $result->status);
            
            $data->fullname = is_null($user->name)||$user->name == '' ? 'Not Specified' : $user->name;
            $data->laststep = ucfirst($step[1]);
            $data->datetime = date('d M y',$result->datetime);
            $data->customers_id = $user->customers_id;
            
            $incomplete['data'][] = $data;
        }
        
        return $incomplete;
    }
    
    private function _getUser($customers_id){
        
        //connect to User Profiles
        $this->_connectCustomerProfiles();
        $user = $this->find($customers_id)->customers()->first();
        
        return $user;
    }
    
    public function getPolicies(){
        
        //get quotes
        $this->getQuotes();
        
        if(isset($this->data['terms'])){
            
            $policies['terms'] = $this->data['terms'];
            unset($this->data['terms']);
        }
        
        foreach($this->data as $quote){
            
            $data = $this->createEmpty();
            
            $user = $this->_getUser($quote->customers_id);
            
            $data->id = $quote->customer_quotes_id;
            $data->date = date('d M Y H:i', $quote->datetime);
            $data->refno = $quote->refno;
            $data->names = is_null($user->name)||$user->name == '' ? 'Not Specified' : $user->name;
            $data->customers_id = $quote->customers_id;
            $data->quotetype = ucfirst($quote->quotetype);
            $data->amount = 'ksh '.number_format((float)$quote->amount, 2);
            
            $policies[] = $data;
        }
        
        return $policies;
    }

    public function changeQuoteStatus($quote_no){
        $this->find($quote_no);
        $this->status = 'agent_attached';
        $this->save();

        if ($this->hasNoErrors())
            return true;
        return false;
    }
}