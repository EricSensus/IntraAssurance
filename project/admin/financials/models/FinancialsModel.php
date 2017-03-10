<?php
namespace Jenga\MyProject\Financials\Models;

use Jenga\App\Request\Input;
use Jenga\App\Models\ORM;

class FinancialsModel extends ORM{
    
    public $table = 'payment_confirmation';
    
    public $columns = [
        'esu_customer_quotes.datetime'=>'Date Generated',
        'esu_.name'=>'Payee Name',
        'esu_customer_quotes.refno'=>'Ref No.',
        'esu_payment_confirmation.tracking_id'=>'Tracking ID',
        'esu_payment_confirmation.merchant_reference'=>'Merchant Reference',
        'esu_customer_quotes.amount'=>'Amount'
    ];
    
    public function connectQuotes(){
        
        $this->associate('customer_quotes')
                ->using([
                    'type' => 'one-to-one',
                    'local' => 'customer_quotes_id',
                    'foreign' => 'customer_quotes_id',
                    'on-delete' => 'delete'
                ]);
    }
    
    public function connectUsers(){
        
        $this->associate('')
                ->using([
                    'type' => 'one-to-one',
                    'local' => 'customers_id',
                    'foreign' => 'customers_id'
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
    
    public function getFinancials(){
        
        if(!is_null(Input::post('search'))){
            
            //add the name section
            if(Input::post('name')!=''){
                
                $this->join('', 
                    TABLE_PREFIX.".id = ".TABLE_PREFIX."payment_confirmation._id");
            
                $params = 'Name: '.Input::post('name').', ';
                $this->where(TABLE_PREFIX.'.name','LIKE','%'.Input::post('name').'%');
            }
            
            //add the refno section
            if(Input::post('refno')!=''){
                
                $this->join('customer_quotes', 
                    TABLE_PREFIX."customer_quotes.id = ".TABLE_PREFIX."payment_confirmation.quoteid");
            
                $params .= 'Reference No: '.Input::post('refno').', ';
                $this->where(TABLE_PREFIX.'customer_quotes.refno','LIKE','%'.Input::post('refno').'%');
            }
            
            //add the tracking section
            if(Input::post('trackingid')!=''){
                
                $params .= 'Tracking ID: '.Input::post('trackingid').', ';
                $this->where('tracking_id','LIKE','%'.Input::post('trackingid').'%');
            }
            
            $financials['terms'] = $params;
            
            $finances = $this->show();
        }
        elseif(!is_null(Input::post('export')) || !is_null(Input::post('printer'))){
            
            if(Input::post('pages')!='all_pages'){
                
                $pages = explode(',',Input::post('pages'));
                
                $start = explode('-', $pages[0])[1];
                $length = explode('-', $pages[1])[1];
                //$end = explode('-', $pages[2])[1];
                
                $column = explode('-', $pages[3])[1];
                $order = $pages[4];
                
                $columns = $this->returnColumns('keys');
                $tablecol = $columns[$column];
                
                //join the  table
                $this->join('', 
                    TABLE_PREFIX.".id = ".TABLE_PREFIX."payment_confirmation._id");
                
                //join the customer_quotes table
                $this->join('customer_quotes', 
                    TABLE_PREFIX."customer_quotes.id = ".TABLE_PREFIX."payment_confirmation.quoteid");
                
                $finances = $this->orderBy($tablecol, $order)->show([$start, $length]);
            }
            else{
                
                $finances = $this->show();
            }
        }
        else{
            
            $finances = $this->show();
        }
        
        foreach($finances as $finance){
            
            $data = $this->createEmpty();
            
            if(!is_null(Input::post('export')) || !is_null(Input::post('printer'))){
                
                $qtime = $finance->datetime;
                $userid = $finance->customers_id;
                $username = $finance->name;
                $qrefno = $finance->refno;
                $qamount = $finance->amount;
            }
            else{    
                
                //get user
                //$this->connectUsers();
                //$user = $this->find($id)->()->first();
            
                //get quotes
                $this->connectQuotes();
                $quote = $this->find($finance->customer_quotes_id)->customer_quotes()->first();
                
                $qtime = $quote->datetime;
                $userid = $user->customers_id;
                $username = $user->name;
                $qrefno = $quote->refno;
                $qamount = $quote->amount;
                
                $data->id = $finance->payment_confirmations_id;
                $data->customers_id = $userid;
            }
            
            $data->quotetime = date('d M y', $qtime);
            $data->name = $username;
            $data->trackingid = $finance->tracking_id;
            $data->refno = $qrefno;      
            $data->merchant = $finance->merchant_reference;      
            $data->amount = 'ksh '.number_format((float)$qamount,2);
            
            $financials[] = $data;
        }
        
        return $financials;
    }
}