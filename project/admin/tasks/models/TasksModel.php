<?php
namespace Jenga\MyProject\Tasks\Models;

use Jenga\App\Models\ORM;
use Jenga\MyProject\Elements;

class TasksModel extends ORM {
    
    public $table = 'tasks';
    
    public function getTask($id){
        
        $task = $this->find($id)->data;
        
        //get the customer name
        if($task->customers_id != '0'){
            $customer = Elements::call('Customers/CustomersController')->find($task->customers_id);
            $task->customer = $customer->name;
        }
        else{
            $task->customer = '';
        }
        
        //get the agent name
        if($task->insurer_agents_id != '0'){
            $agent = Elements::call('Agents/AgentsController')->retrieveAgents($task->insurer_agents_id);
            $task->agent = $agent->names;
        }
        else{
            $task->agent = '';
        }
        
        return $task;
    }
}