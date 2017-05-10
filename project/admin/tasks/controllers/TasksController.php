<?php
namespace Jenga\MyProject\Tasks\Controllers;

use Jenga\App\Helpers\Help;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Request\Session;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Elements;

class TasksController extends Controller {
    
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
    
    public function add($task = null){
        
        //get agents
        $agents = Elements::call('Agents/AgentsController')->retrieveAgents();
        
        foreach($agents as $agent){
            
            if($agent->id == Session::get('agentsid'))
                $agentslist[$agent->id] = '-- Link to me --';
            else
                $agentslist[$agent->id] = $agent->names;
        }
        
        $this->view->addForm($agentslist,$task);
    }
    
    public function edit(){
        
        //get task
        $task = $this->model->getTask(Input::get('id'));
        $this->add($task);
    }

    public function show(){
        
        $dbtasks = $this->model
                            //->where('insurer_agents_id','0')
                            //->orWhere('insurer_agents_id',Session::get('agentsid'))
                            ->where('completed','>',time())
                            ->orWhere('completed','=',0)
                            ->orderBy('dategen','DESC')
                            ->show();
                    
        $customer = Elements::call('Customers/CustomersController');
        $agent = Elements::call('Agents/AgentsController');
        
        foreach($dbtasks as $dbtask){
            
            $dbtask->customer = $customer->getCustomerById($dbtask->customers_id,'raw')->name;
            
            if($dbtask->insurer_agents_id != 0)
                $dbtask->agent = $agent->retrieveAgents($dbtask->insurer_agents_id)->names;
            
            $tasks[] = $dbtask;
        }
        
        $this->view->set('addtaskmodal',['id' => 'addtaskmodal','size'=>'large']);
        $this->view->generateTasks($tasks);
    }
    
    public function getTasksByCustomer($id, $return = true, $params = []){
        
        $dbcusttasks = $this->model
                                ->where('customers_id',$id)
                                ->orderBy('dategen','DESC')
                                ->show();
        
        if($this->model->count() >= 1){
            
            $agent = Elements::call('Agents/AgentsController');

            foreach($dbcusttasks as $dbcusttask){

                if($dbcusttask->insurer_agents_id != 0)
                    $dbcusttask->agent = $agent->retrieveAgents($dbcusttask->insurer_agents_id)->names;

                $tasks[] = $dbcusttask;
            }

            if($return == true){
                $result['html'] = $this->view->generateTasks($tasks, $return, $params);
                $result['count'] = $this->model->count();
                
                return $result;
            }
            else
                echo $this->view->generateTasks($tasks);
        }
        else{
            
            return NULL;
        }
    }
    
    public function getCustomer(){
        
        $this->view->disable();
        $name = Input::request('query');
        
        echo Elements::call('Customers/CustomersController')->getCustomerByName($name);
    }
    
    public function save(){
        
        $this->view->disable();
        
        if(Input::post('id')=='')
            $task = $this->model;
        else
            $task = $this->model->find(Input::post('id'));
        
        $task->customers_id = (Input::post('customerid')=='' ? '0' : Input::post('customerid'));
        $task->dategen = strtotime(Input::post('dategen'));
        $task->tasktype = Input::post('tasktype');
        $task->subject = Input::post('subject');
        $task->description = Input::post('description');
        $task->priority = Input::post('priority');
        $task->remainder = Input::post('remainder') != '' ? strtotime(Input::post('remainder')) : '0';
        $task->insurer_agents_id = Input::post('agent');
        
        $save = $task->save();
        
        if(!array_key_exists('ERROR', $save)){
            
            Redirect::withNotice('The task has been added', 'success')
                    ->to(Input::post('destination'));
        }
        else{
            
            Redirect::withNotice('The task details has not been saved <br/><strong>SQL Error</strong>: '.$save['ERROR'], 'error')
                    ->to(Input::post('destination'));
        }
    }
    
    public function markAsComplete(){
        
        $id = Input::get('id');
        $url = Help::decrypt(Input::get('destination'));
        
        $task = $this->model->find($id);
        $task->completed = time();
        
        if(!array_key_exists('ERROR', $task->save()))
            Redirect::withNotice('The task has been completed', 'success')->to($url);
    }
    
    public function preview(){
        
        $this->view->disable();
        $id = Input::get('taskid');
        
        $task = $this->model->find($id)->data;
        
        //process priority
        $priority = ['0'=>'Low','1'=>'Normal','2'=>'High'];
        $task->priority = $priority[$task->priority];
        
        echo $this->view->taskTable($task);
        unset($task);
    }
    
    public function delete(){
        
        $this->view->disable();
        
        $id = Input::get('id');
        $url = Help::decrypt(Input::get('destination'));
        
        $this->model->where('id',$id)->delete();
        
        Redirect::withNotice('The task has been deleted', 'success')->to($url);
    }
}
