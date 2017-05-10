<?php
namespace Jenga\MyProject\Policies\Models;

use Jenga\App\Request\Input;
use Jenga\App\Models\ORM;

use Jenga\MyProject\Elements;

class PoliciesModel extends ORM {
    
    public $table = 'policies';
    public $columns = [
        'policy_number' => 'Policy No',
        'issue_date' => 'Issue Date',
        'insurers_id' => 'Insurer',
        'start_date' => 'Validity',        
        'customers_id' => 'Customer',
        'products_id' => 'Product',
        'status' => 'Status'
    ];

    public function getDataFromQuote($id){
        $data = $this->table('customer_quotes')->where('id', $id)->first();
        return $data;
    }
    
    public function search($search){
        
        $data = $this;
           
        //search for the customer name
        if($search['name']!=''){
            
            $condition = 'Name: '.$search['name'].', ';
            $data->join('customers', TABLE_PREFIX.'policies.customers_id = '.TABLE_PREFIX.'customers.id')
                    ->where(TABLE_PREFIX.'customers.name', 'LIKE', '%'.$search['name'].'%');
        }
        
        //search for the product
        if($search['product']!=''){
            
            //get the product name
            $product = Elements::call('Products/ProductsController')->getProduct($search['product'],'array');
            
            $condition .= 'Product: '.$product['name'].', ';
            $data->where('products_id','=',$search['product']);
        }
        
        //search for the insurer
        if($search['insurer']!=''){
            
            //get the insurer name
            $insurer = Elements::call('Insurers/InsurersController')->getInsurer($search['insurer']);
            
            $condition .= 'Insurer: '.$insurer['name'].', ';
            $data->where('insurers_id','=',$search['insurer']);
        }
        
        $searchresults['condition'] = $condition;
        $searchresults['result'] = $data->show();
        
        //store the search variables for use with the other tools
        $this->store();
        
        return $searchresults;
    }
    
    public function getPolicies(){
        
        if(Input::post('pages') == 'all_pages'){
                
            $policies = $this->show();
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
             
            $policies = $this->orderBy($tablecol, $order)
                            ->show([$start, $length]);
        }
        
        return $policies;
    }
    
    public function getPolicyAnalysis(){
        
        for($month = 1; $month <= (date('m')+12); $month++) {
            
            $start = mktime(0, 0, 0, $month, 1, date("Y",strtotime('-1 year')));
            $end = mktime(23, 59, 0, $month, date('t', $start), date("Y",strtotime('-1 year')));
            
            $reports = $this->where('datetime', '>=', $start)
                        ->where('datetime', '<=', $end)
                        ->show();
        
            $issuecount = 0; $notissuecount = 0;
            foreach($reports as $report){
                
                if($report->issue_date == 0)
                    $notissuecount++;
                else
                    $issuecount++;
            }
            
            if($month >= 13){                
                $newmonth = $month-12;
                $dateObj   = \DateTime::createFromFormat('!m', $newmonth);
            }
            else{
                $dateObj   = \DateTime::createFromFormat('!m', $month);
            }          
            
            $monthName = $dateObj->format('M'); // March   
            
            $notissue[$monthName] = $notissuecount;
            $issue[$monthName] = $issuecount;

            $months[] = $monthName;
        }
        
        $count = 0;
        foreach($months as $month){
            
            $monthdata['issued'][] = $issue[$month];
            $monthdata['notissued'][] = $notissue[$month];
            
            if($count == 11)
                break;
            
            $count++;
        }
        
        $monthdata['months'] = $months;
        
        return $monthdata;
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

    public function getPolicyDocs($policy_id){
        $policy_docs = $this->table('policies_documents')->where('policies_id', $policy_id)->show();
        $count = count($policy_docs);

        return [
            'count' => $count,
            'docs' => $policy_docs
        ];
    }

    public function getPolicy($policy_id){
        $policy = $this->where('id', $policy_id)->first();
        return $policy;
    }
}