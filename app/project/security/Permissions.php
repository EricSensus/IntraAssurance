<?php
namespace Jenga\App\Project\Security;

use Jenga\App\Core\App;
use Jenga\App\Core\File;
use Jenga\App\Helpers\Help;
use Jenga\App\Project\Core\Project;

class Permissions {
    
    private $_rolepermissions;
    private $_userpermissions;
    private $_level;
    private $_actions;
    private $_calculatedperms;
    
    public function __construct($permissions, $userlevel) {
        
        //process the json permissions
        if(Help::is_json($permissions)){
            $this->_rolepermissions = json_decode($permissions);
        }
        else{
            $this->_rolepermissions = $permissions;
        }
        
        $this->_level = $userlevel;        
        $this->_actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
        
        $this->_confirmAccessAction();
        return $this->_calculate();
    }
    
    /**
     * Computes the user role, user and hierachy settings to create an overall user permissions profile
     */
    private function _calculate() {
        
        $build = [];
        foreach ($this->_actions as $elementname => $element) {
            
            $actions = @array_keys($element['actions']);
            $level = $element['level'];
            
            if(!is_null($actions)){
                
                $actionslist = [];
                foreach($actions as $action){

                    /**
                     * If no role or user permisiions have been set, 
                     * calculate the permissions entirely from the assigned element levels
                     * and the assigned user level
                     */
                    if($this->_rolepermissions == '' && $this->_userpermissions == ''){

                        if($this->_level >= $level){
                             $actionslist[$action] = TRUE;
                        }
                        else{
                            $actionslist[$action] = FALSE;
                        }
                    }
                    else{
                        
                        $elmperms = $this->_rolepermissions->{$elementname};
                        $actionslist[$action] = $elmperms->{$action};
                    }
                }
            }
            
            if(!is_null($actionslist)){
                
                ksort($actionslist);
                
                $build[$elementname] = $actionslist;
                unset($actionslist);
            }
        }
        
        if(!is_null($build)){
            
            $this->_calculatedperms = $build;
            return $this->_calculatedperms;
        }
    }
    
    /**
     * Allocates the specific user permissions
     * @param type $userpermissions
     */
    public function addUserPermissions($userpermissions){
        
        $this->_userpermissions = $userpermissions;
    }
    
    /**
     * Checks if sent user can perform sent actions
     * 
     * @param type $action
     * @param type $element
     * @param type $debug set to TRUE if you want to see error generated
     * @example $this->user()->can('edit','customers');
     * 
     * @return boolean TRUE allowed or FALSE nor allowed
     */
    public function can($action, $element, $debug = false){
        
        $perm = $this->getPermissionsFromActions($action, $element);
        
        if(gettype($perm) == 'boolean'){
            return $perm;
        }
        else{
            
            if($debug == FALSE){
                return FALSE;
            }
            else {
                return $perm;
            }
        }
    }
    
    /**
     * This is the function called from the controller to check user permissions
     * @param type $element
     * @param type $method
     */
    public function canExecute($element, $controller, $method = 'index'){
        
        $controller = end(explode('\\', $controller));
        $elementactions = $this->_actions[strtolower($element)]['actions'];
        
        $actionname = $controller.'@'.$method;
        
        if(!is_null($elementactions) && in_array($actionname, $elementactions)){
            
            //get permissions
            $action = array_search($actionname, $elementactions);
            $perm = $this->getPermissionsFromActions($action, $element);
            
            //if this the the authorizing element, allow access
            if(App::get('gateway')->auth_element == $element){
                
                return TRUE;
            }
            
            switch (gettype($perm)) {
                
                case 'boolean':
                    return $perm;
                    //break;

                case 'string':
                    
                    if($perm == 'ELEMENT_NOT_REGISTERED')
                        throw App::exception ('The element "'.$element.'" permissions have not been registered');
                    
                    elseif($perm == 'ACTION_NOT_REGISTERED')
                        throw App::exception ('The permissions for "'.$action.'" action for element "'.$element.'" have not been registered');
                    break;
            }
        }
        
        return TRUE;
    }
    
    /**
     * Returns calculated permissions
     * @return array permissions
     */
    public function returnCalculatedPermissions() {
        return $this->_calculatedperms;
    }
    
    /**
     * Searches through the calculated permissions and returns the linked actions
     * @param type $action
     */
    public function getPermissionsFromActions($action, $element){
        
        $permkeys = array_keys($this->_calculatedperms);
        
        //check if element exists
        if(in_array($element, $permkeys)){
            
            //check if action is registered in element
            $actionkeys = array_keys($this->_calculatedperms[$element]);
            
            if(in_array($action, $actionkeys)){
                
                return $this->_calculatedperms[$element][$action];
            }
            
            return 'ACTION_NOT_REGISTERED';
        }
        
        return 'ELEMENT_NOT_REGISTERED';
    }
    
    /**
     * This function checks the registered actions 
     * and adds an access action for the index() method for any controller
     */
    public function _confirmAccessAction() {
        
        foreach($this->_actions as $element => $items){
            
            if(!is_null($items['actions'])){
                
                $actiontags = array_keys($items['actions']);
                
                if(!in_array('access', $actiontags)){

                    //add the access action
                    $firstaction = $items['actions'][$actiontags[0]];
                    $actionsplit = explode('@', $firstaction);
                    
                    $controller = $actionsplit[0];
                    $action = $controller.'@index';
                }
            }
            else{
                
                //create index action if no actions have been registered
                $fullelement = Project::elements()[$element];
                
                if(!is_null($fullelement)){
                    
                    $ctrlkeys = array_keys($fullelement['controllers']);

                    foreach($ctrlkeys as $ctrl){

                        if(strstr($ctrl, ucfirst($element))){

                            $action = ucfirst($ctrl).'@index';
                        }
                    }
                }
            }
            
            //create the access tag
            $this->_actions[$element]['actions']['access'] = $action;
        }
    }
}