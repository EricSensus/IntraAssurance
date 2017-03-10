<?php
namespace Jenga\MyProject\Tasks\Views;

use Jenga\App\Views\View;
use Jenga\App\Request\Url;
use Jenga\App\Html\Generate;
use Jenga\App\Request\Input;
use Jenga\App\Views\Overlays;
use Jenga\App\Request\Facade\Sanitize;
use Jenga\App\Views\Notifications;
use Jenga\App\Helpers\Help;

use Jenga\MyProject\Elements;

class TasksView extends View {
    
    public function addForm($agentslist, $taskrecord = null, $agent = false){

        $agent_id = ($agent) ? $taskrecord : $taskrecord->insurer_agents_id;
        
        //create the return url from the Navigation element
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'dashboard']);
        
        $agents = $agentslist + [0 => '-- Link to All Agents --'];
        asort($agents);
        
        //process date gen
        if($taskrecord != null && $taskrecord->dategen != '0'){
            $dategen = date('d F Y',$taskrecord->dategen);
        }
        else{
            $dategen = date('d F Y', time());
        }
        
        //process remainder
        if($taskrecord != null && $taskrecord->remainder != '0'){
            $remainder = date('d F Y',$taskrecord->remainder);
        }
        else{
            $remainder = '';
        }
        
        //process completed tag
        if($taskrecord != null && $taskrecord->completed != '0'){
            $completed['date'] = ' - Completed at '.date('d F Y',$taskrecord->completed);
            $completed['status'] = ['checked'=>'checked'];
        }

        if($agent)
            $completed['status'] = [];
        
        if(!is_null(Input::get('customerid'))){
            
            $customer = Elements::call('Customers/CustomersController')->getCustomerById(Input::get('customerid'),'raw');
            
            $url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'customers']);
            $url = $url.'/show/'.$customer->id;
            
            $customerid = $customer->id;
            $customername = $customer->name;
        }
        else{
            $customerid = $taskrecord->customers_id;
            $customername = $taskrecord->customer;
        }
        
        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/tasks/save',
            'controls' => [
                '{id}' => ['hidden','id',($taskrecord->id == '' ? '' : $taskrecord->id)],
                '{destination}' => ['hidden','destination', $url],
                '{customerid}' => ['hidden','customerid',$customerid],
                'Linked Customer' => ['text','customer',$customername],
                'Link to Agent' => ['select','agent',$agent_id,$agents],
                'Date' => ['date','dategen',$dategen,['format'=>'d F Y']],
                'Task' => ['select','tasktype',($taskrecord->tasktype == '' ? 'task' : $taskrecord->tasktype), ['task'=>'task','meeting'=>'meeting','correspondence'=>'correspondence - send email or make phone call']],
                'Subject' => ['text','subject',$taskrecord->subject],
                'Description' => ['textarea','description',$taskrecord->description],
                'Priority' => ['select','priority',$taskrecord->priority=='' ? 1 : '',['Low','Normal','High']],
                'Remind me of this task on' => ['date','remainder',$remainder,['format'=>'d F Y']],
                'Completed' => ['checkbox','completed','yes',$completed['status']]
            ],   
            'validation' => [
                'dategen' => [
                    'required' => 'Please enter the task date'
                ],
                'subject' => [
                    'required' => 'Please enter your task subject'
                ]
            ]
        ];
        
        $form = Generate::Form('addtaskform', $schematic);        
        $aform = $form->render(ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'tasks' .DS. 'views' .DS. 'panels' .DS. 'add' .DS. 'addform.php',TRUE);
        
        $modalsettings = [
            'id' => 'addtaskmodal',
            'formid' => 'addtaskform',
            'role' => 'dialog',
            'title' => ($agent) ? 'Create Task for the attached Agent' : ($taskrecord == null ? 'Add New Task' : 'Edit Existing Task'.$completed['date']),
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save New Task' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];
        
        $addform = Overlays::ModalDialog($modalsettings, $aform);
        $this->set('addtaskform',$addform);         

        if(!$agent)
            $this->setViewPanel('add'.DS.'addpanel');
        else
            echo $addform;
    }
    
    public function generateTasks($tasks, $return = false, $params = []){
        
        //create the return url from the Navigation element
        if(!array_key_exists('url', $params)){
            
            $url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'dashboard']);
        }
        else{
            
            $url = $params['url'];
        }
        
        if(count($tasks) >= 1){
            
            $tasksoutput = '<form id="tasksform">'
                    . '<ul class="tasks">';

            foreach($tasks as $task){

                //process completed tag
                if($task->completed != '0'){
                    $completed['date'] = ' - Completed at '.date('d F Y',$task->completed);
                    $completed['status'] = ['checked'=>'checked'];
                }

                $tasksoutput .= '<li id="'.$task->id.'" '.($task->priority == '2' ? 'class="high"' : '').'>'
                        . '<div class="task">';

                $tasksoutput .= '<div class="tasktop">'
                        . '<div class="icon floatleft">'
                            . '<img class="icontype" src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/'.$task->tasktype.'_icon.png" title="'.$task->tasktype.'" />'
                        . '</div>'
                        . '<div class="title_date">'
                            . '<div class="title floatleft">'
                                . '<p class="title">';

                if(!array_key_exists('disable_edit', $params)){

                    $tasksoutput .= '<a href="'.Url::base().'/ajax/admin/tasks/edit/'.$task->id.'"'
                            . ' data-toggle="modal" data-backdrop="static" data-target="#'.$this->get('addtaskmodal')['id'].'">';
                }

                $tasksoutput .= Sanitize::shorten($task->subject, 40);

                if(!array_key_exists('disable_edit', $params)){
                    $tasksoutput .= '</a>';
                }

                $tasksoutput .= '</p>'
                                . '<p class="date">'.date('d F Y',$task->dategen).'</p>'
                            . '</div>'
                            . '<div class="float-right" style="margin-right: 15px;">'
                                . '<a '.($task->completed == '0' ? 'class="taskicons" data-confirm="Mark task as complete. Proceed?" href="'.Url::base().'/ajax/admin/tasks/markascomplete/'.$task->id.'/'.Help::encrypt($url).'"' : '').'>'
                                    . '<img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/tick_icon.png" '.($task->completed == '0' ? 'title="Mark as Complete"' : 'title="Status'.$completed['date'].'"').' />'
                                . '</a>'
                                . '<a class="taskicons" href="'.Url::base().'/ajax/admin/tasks/delete/'.$task->id.'/'.Help::encrypt($url).'" data-confirm="Confirm deletion of task - '.Sanitize::shorten($task->subject, 40).'. Proceed?" >'
                                    . '<img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/x_icon.png" title="Delete Task" />'
                                . '</a>'                            
                            . '</div>'
                        . '</div>'
                        . '</div>';

                $tasksoutput .= '<div class="taskbottom">'
                                . '<p class="description">'.Sanitize::shorten($task->description, 100).'</p>';

                if(property_exists($task, 'customer')){

                    $url = Elements::call('Navigation/NavigationController')->getUrl('customers');                
                    $tasksoutput .= '<p class="customer">Linked to <strong>'
                            .'<a href="'.Url::base().$url.'/show/'.$task->customers_id.'">'.$task->customer.'</a></strong>';

                    if(property_exists($task, 'agent')){
                        $tasksoutput .= ' handled by <strong>'.$task->agent.'</strong>';
                    }

                    $tasksoutput .= '</p>';
                }

                if($task->remainder > 0){
                    $tasksoutput .= '<div class="icon" '.Notifications::popover('Remainder set at '.date('d F Y',$task->remainder),['data-placement'=>'top']).' style="float:right; opacity: 0.5; margin-top:-35px; margin-right: 5px">'
                                    . '<img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/remainder_icon.png" />'
                                . '</div>';
                }

                $tasksoutput .= '</div>';            
                $tasksoutput .= '</div>'
                        . '</li>';
            }

            $tasksoutput .= '</ul>'
                    . '</form>';
        }
        
        if($return == false){
            $this->set('tasks', $tasksoutput);
        }
        elseif($return == true){
            
            $this->disable();
            return $tasksoutput;
        }
    }
    
    public function taskTable($task){
                
        $table = '<h3>Subject: '.$task->subject.'</h3>'
                . '<button type="button" class="btn btn-primary pull-right">Priority: '.$task->priority.'</button>'
                . '<p><strong>Date:</strong> '.date('d F Y',$task->dategen).'</p>'
                . '<h5><strong>Description:</strong></h5>'
                . '<p>'.$task->description.'</p>'
                . '<div class="clearfix"></div>';
        
        if($task->completed != 0){
            $table .= '<button type="button" class="btn btn-default pull-right">'
                        . 'Completed at '.date('d F Y',$task->completed)
                    .'</button>';
        }
        
        if($task->remainder != 0){
            $table .= '<button type="button" class="btn btn-default pull-right">'
                        . 'Remainder set at '.date('d F Y',$task->remainder)
                    .'</button> ';
        }
        
        return $table;
    }
}