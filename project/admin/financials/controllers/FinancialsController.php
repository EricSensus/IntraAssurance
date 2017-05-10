<?php
namespace Jenga\MyProject\Financials\Controllers;

use Jenga\App\Request\Input;
use Jenga\App\Controllers\Controller;
use Jenga\App\Views\Notifications;
use Jenga\App\Html\Excel;
use Jenga\App\Views\HTML;
use Jenga\App\Views\Redirect;

class FinancialsController extends Controller {
    
    public function index(){ 
        
        if(is_null(Input::get('action')) && is_null(Input::post('action'))){
            
            $action = 'getFinancials';
        }
        else{
            
            if(!is_null(Input::get('action')))
                
                $action = Input::get('action');
            
            elseif(!is_null(Input::post('action')))
                
                $action = Input::post ('action');
        }
        
        $this->$action();
    }
    
    public function getFinancials(){
        
        $finances = $this->model->getFinancials();
        
        $this->view->set('count',count($finances));
        $this->view->set('source',$finances);
        
        $this->view->generateTable();
    }
    
    public function search(){
        
        $finances = $this->model->getFinancials();
        
        $params = $finances['terms'];
        unset($finances['terms']);
        
        $search = array_values($finances);        
        $searchcount = count($search);
        
        $alerts = Notifications::Alert($searchcount.' Search Results for '.$params, 'info', TRUE, TRUE);
        
        $this->view->set('count',count($finances));
        $this->view->set('alerts',$alerts);
        $this->view->set('source',$finances);
        
        $this->view->generateTable();
    }
    
    public function export(){
        
        $finances = $this->model->getFinancials();
        
        $columns = $this->model->returnColumns('values');
            
        $doc = new Excel('Esurance Document Creator',  Input::post('filename'));
        $doc->generateDoc($columns, $finances, Input::post('format'));
    }
    
    public function printer(){
        
        $this->view->disable();
        $finances = $this->model->getFinancials();
        
        $this->view->set('count',count($finances));
        $this->view->set('source',$finances);

        $this->view->generateTable();
            
        HTML::printPage();
    }
    
    public function delete(){
        
        $this->view->disable();
        $ids = Input::post('ids');
        
        foreach($ids as $id){
            
            $this->model->connectQuotes();
            $this->model->where('payment_confirmations_id','=',$id)->delete();
        }
        
        Redirect::withStickyNotice('The payment record has been deleted', 'success')
                    ->to(Input::post('destination'));
    }
}
