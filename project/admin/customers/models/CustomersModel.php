<?php
namespace Jenga\MyProject\Customers\Models;

use Jenga\App\Models\ORM;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class CustomersModel extends ORM {
    
    public $table = 'customers';    
    public $columns = [
        'id'=>'ID', 
        'name'=>'Full Name', 
        'email'=>'Email Address', 
        'mobileno'=>'Phone Number', 
        '[count].policies'=>'Policies', 
        '[count].customer_quotes'=>'Quotes Generated', 
        'regdate'=>'Registration Date'
    ];
    
    /**
     * Connect customers to the quotes
     */
    public function connectQuotes(){
        
        $this->associate('customer_quotes')
                ->using([
                    'type' => 'one-to-many',
                    'local' => 'id',
                    'foreign' => 'customers_id',
                    'on_delete' => 'delete'
                ]);
    }
    
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
     * Find customers by id
     *  
     * @param type $id
     * @return type
     */
    public function findCustomer($id){
        
        $customer = $this->find($id)->data;
        
        if(!is_null($customer)){
            
            $properties = get_object_vars($customer);
            $data = $this->createEmpty();

            foreach($properties as $property => $value){

                if($property == 'regdate' || $property == 'dateofbirth'){

                    $data->$property = date('d-m-Y', $value);
                }
                else{

                    $data->$property = $value;
                }
            }
        }
        else{
            
            $data = NULL;
        }
        
        return $data;
    }
    
    public function getCustomersByMonth(){
        
        for($month = 1; $month <= (date('m')+12); $month++) {
            
            $start = mktime(0, 0, 0, $month, 1, date("Y",strtotime('-1 year')));
            $end = mktime(23, 59, 0, $month, date('t', $start), date("Y",strtotime('-1 year')));
            
            $this->select(TABLE_PREFIX.'customers.regdate, '.TABLE_PREFIX.'users.email_confirmed')
                ->join('users', 
                        TABLE_PREFIX."customers.id = ".TABLE_PREFIX."users.customers_id")
                ->where(TABLE_PREFIX.'customers.regdate', '>=', $start)
                ->where(TABLE_PREFIX.'customers.regdate', '<=', $end)
                ->where(TABLE_PREFIX.'users.email_confirmed', '=', 'no')
                ->show();
        
            $nocount = $this->count();
            
            $this->select(TABLE_PREFIX.'customers.regdate, '.TABLE_PREFIX.'users.email_confirmed')
                ->join('users', 
                        TABLE_PREFIX."customers.id = ".TABLE_PREFIX."users.customers_id")
                ->where(TABLE_PREFIX.'customers.regdate', '>=', $start)
                ->where(TABLE_PREFIX.'customers.regdate', '<=', $end)
                ->where(TABLE_PREFIX.'users.email_confirmed', '=', 'yes')
                ->show();
            
            $yescount = $this->count();
            
            if($month >= 13){                
                $newmonth = $month-12;
                $dateObj   = \DateTime::createFromFormat('!m', $newmonth);
            }
            else{
                $dateObj   = \DateTime::createFromFormat('!m', $month);
            }
            
            $monthName = $dateObj->format('M'); // March
            
            $monthdata[$monthName] = $nocount.','.$yescount;
        }
        
        return $monthdata;
    }
    
    public function getCustomers(){
        
        if(Session::has('agentsid')){
            $this->where('insurer_agents_id', Session::get('agentsid'));
        }
        
        if(!is_null(Input::post('search'))){  

            //add the name section
            if(Input::post('name')!=''){
                
                $params = 'Name: '.Input::post('name').', ';
                $this->where('name','LIKE','%'.Input::post('name').'%');
            }
            
            //add the email section
            if(Input::post('email')!=''){
                
                $params .= 'Email: '.Input::post('email');
                $this->where('email','LIKE','%'.Input::post('email').'%');
            }

            $users = $this->show();
            
            //store the search variables for use with the other tools
            $this->store();
            
            $customers['terms'] = $params;
        } 
        else if (!is_null(Input::post('export')) || !is_null(Input::post('printer'))){
            
            if(Input::post('pages') == 'all_pages'){
                $users = $this->show();
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
                
                $users = $this->orderBy($tablecol, $order);

                $this->show([$start, $length]);
            }
        }
        else{
            $users = $this->show();
        }

        foreach($users as $user){
            
            $data = $this->createEmpty();
            
            if($this->count() != '0'){
                
                $this->connectQuotes();      
                
                $quotes = $this->find($user->id)->customer_quotes()->show();
                $qcount = $this->count();
                
                $data->id = $user->id;
                $data->name = is_null($user->name) || $user->name == '' ? 'Not Specified' : $user->name;
                $data->email = $user->email;
                $data->phone = $user->mobile_no;
                $data->qcount = $qcount;
                //$data->quotes = $quotes;
                $data->regdate = date('d M y',$user->regdate);
            }
            else{
                
                $data->id = $user->id;
                $data->name = is_null($user->name) || $user->name == '' ? 'Not Specified' : $user->name;
                $data->email = $user->email;
                $data->phone = $user->mobile_no;
                $data->qcount = 0;
                $data->regdate = date('d M y',$user->regdate);
            }
            
            $customers[] = $data;
        }
        
        return $customers;
    }
    
    public function getQuotesByCustomer($id){
        
        $this->connectQuotes();
        $quotes = $this->find($id)->customer_quotes()->show();
        
        if($this->count() == 0){
            
            return Notifications::Alert('No quotes found', 'info', TRUE, TRUE);
        }
        else{
            
            foreach($quotes as $quote){
                
                $data = $this->createEmpty();
                
                $data->id = $quote->customer_quotes_id;
                $data->date = date('d-m-Y',$quote->datetime);
                $data->refno = $quote->refno;
                $data->quotetype = ucfirst($quote->quotetype);
                $data->amount = 'ksh '.number_format($quote->amount, 2);
                
                $squote[] = $data;
            }
            
            return $squote;
        }
    }
    
    /**
    public function getQuoteFinancials($id){
        
        //initialize payment_confirmations table
        $this->table('payment_confirmation');
        
        $finances = $this->where('customers_id', '=', $id)->show();
        
        if($this->count() == 0){
            
            return Notifications::Alert('No payment records found', 'info', TRUE, TRUE);
        }
        else{
            
            foreach($finances as $record){
                
                $data = $this->createEmpty();
                
                //connect to quotes
                $this->connectQuotes();
                $quotes = $this->find($id)->customer_quotes()->show();
                
                $data->date = date('d-m-Y',$record->datetime);
                $data->tracking_id = $record->tracking_id;
                $data->refno = $quotes->refno;
                $data->amount = 'ksh '.number_format($quotes->amount, 2);
                
                $payments[] = $data;
            }
            
            return $payments;
        }
    }
     * 
     */

    /**
     * @param $customer_id
     * @param $agent_id
     * @return bool
     */
    public function attachAgentToCustomer($customer_id, $agent_id){
        $this->find($customer_id);
        $this->insurer_agents_id = $agent_id;
        $this->save();

        if($this->hasNoErrors())
            return true;
        return false;
    }

    public function getCustomerById($customer_id){
        return $this->where('id', $customer_id)->first();
    }
}