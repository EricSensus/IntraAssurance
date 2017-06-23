<?php
namespace Jenga\MyProject\Agents\Models;

use Jenga\App\Models\ORM;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class AgentsModel extends ORM {
    
    public $table = 'insurer_agents';
    
    public function getAgents($id = null){
        if(is_null($id)){
            
            $agents = $this->show();
            
            //check the agent login details
            foreach($agents as $agent){
                
                $users = Elements::call('Users/UsersController')->model->where('insurer_agents_id', $agent->id)->show();
                $usercount = count($users);
                
                if($usercount >= 1){
                    
                    foreach($users as $user){
                        
                        $agent->login = '<a href="'.Url::base().'/ajax/admin/agents/configureagentlogin/'.$user->insurer_agents_id.'" '
                                        . 'class="edit" data-toggle="modal" data-backdrop="static" data-target="#editformfield2">'
                                    . '<img '.Notifications::tooltip('Edit Agent Login Details').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/padlock_icon.png"/>'
                                    . '</a>';
                    }
                }
                else{
                    
                    $agent->login = '<a href="'.Url::base().'/ajax/admin/agents/configureagentlogin/'.$agent->id.'" '
                                        . 'class="edit" data-toggle="modal" data-backdrop="static" data-target="#editformfield2">'
                                        . 'No Logins Set'
                                    . '</a>';                    
                }
                
                $agentslist[] = $agent;
            }
            
            return $agentslist;
        }
        else
            return $this->where ('id',$id)->first();
    }
    
    public function getAgentAnalysis(){
        
        $agents = $this->show();
        
        foreach($agents as $agent){
            $quote_ctrl = Elements::call('Quotes/QuotesController');
            
            $quotes = Elements::call('Quotes/QuotesController')->model->show();
            $fullcount = count($quotes);
            
            $agentquotes = $quote_ctrl->model->select(TABLE_PREFIX . 'customer_quotes.*, '
                . TABLE_PREFIX . 'customers.insurer_agents_id as customeragents, '
                . TABLE_PREFIX . 'customer_quotes.*')
                ->join('customers', TABLE_PREFIX . "customers.id = " . TABLE_PREFIX . "customer_quotes.customers_id")
                ->where('insurer_agents_id', $agent->id)->get();
            
            $agentcount = count($agentquotes);
            
            $percentage = ($agentcount / $fullcount)*100;
            $acount[$agent->names] = number_format($percentage, 1, '.', '');
        }        
        
        return $acount;
    }
}