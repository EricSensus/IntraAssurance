<?php
namespace Jenga\MyProject\Insurers\Controllers;

use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Elements;

class InsurersController extends Controller {
    
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
    
    public function getInsurer($id = NULL){
        
        if(!is_null($id))
            return $this->model->getInsurer($id);
        else
            return $this->model->show();
    }
    
    public function getCommissions(){
        
        $products = Elements::call('Products/ProductsController')->getProduct();
        $insurers = $this->getInsurer();
        
        foreach($insurers as $insurer){
            
            foreach($products as $product){
                
                $comms = $this->model->table('commissions')
                        ->where('products_id',$product->id)
                        ->where('insurers_id', $insurer->id)
                        ->first();
                
                $prodholder[$insurer->id][$product->id] = [$comms->id,$comms->percentage];
                $productslist[$product->id] = $product->name;
                $collections[$insurer->id] = $comms->collection_means;
            }
            
            $commholder[$insurer->name] = $prodholder;
            
            $insurerslist[$insurer->id] = $insurer->name;
            unset($prodholder);
        }
        
        $this->set('collections', $collections);
        $this->set('productslist', $productslist);
        $this->set('insurerslist', $insurerslist);
        
        $this->view->commissionsList($commholder);     
    }
    
    public function saveCommissions(){
        
        $this->view->disable();
        
        $count = 0;
        
        $id = Input::post('id');
        $collections = Input::post('collection');
        $commissions = Input::post('commission');
        
        foreach($commissions as $insurerid => $products){            
            foreach($products as $productid => $percentage){
                
                if($id[$count]!='')
                    $comm = $this->model->table('commissions')->find($id[$count]);
                else
                    $comm = $this->model->table('commissions');
                
                $comm->insurers_id = $insurerid;
                $comm->products_id = $productid;
                $comm->percentage = $percentage == '' ? 0 : $percentage;
                $comm->collection_means = $collections[$insurerid];
                
                $comm->save();
                
                unset($percentage);                
                $count++;
            }
        }
        
        Redirect::withNotice('The commissions have been saved','success')
                ->to(Input::post('destination'));
    }

    public function getInsurerByFinder($finder){
        return $this->model->find($finder);
    }
}