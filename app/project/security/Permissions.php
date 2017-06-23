<?php
namespace Jenga\App\Project\Security;

use Jenga\App\Core\App;
use Jenga\App\Core\File;
use Jenga\App\Helpers\Help;
use Jenga\App\Project\Core\Project;

use DocBlockReader\Reader;

class Permissions {
    
    //private $_rolepermissions;
    private $_userpermissions;
    private $_level;
    private $_actions;
    private $_calculatedperms;
    
    public $annotations = ['acl\level','acl\role','acl\action','acl\alias'];
    
    public function __construct($userlevel) {
        
        if(is_null($userlevel))
            $this->_level = 0;
        else
            $this->_level = $userlevel;
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
     * @param type $ctrl this is whether the ACL should evaluate the primary controller or another controller emebedded in the element. If its a separate controller just insert the Controller class to be evaluated
     * @param type $debug set to TRUE if you want to see error generated
     * @example $this->user()->can('edit','customers');
     * 
     * @return boolean TRUE allowed or FALSE nor allowed
     */
    public function can($action, $element, $ctrl = 'primary',$debug = TRUE){
        
        //$perm = $this->getPermissionsFromActions($action, $element);
        $elements = Project::elements();
        
        $gateway = App::get('gateway');
        $user = $gateway->user();
        
        //check for the element
        if(array_key_exists($element, $elements)){
            
            //get all the controllers
            $controllers = $elements[$element]['controllers'];
            
            //if its the primary controller
            if($ctrl == 'primary'){                
                $controller = ucfirst($element).'Controller';
            }
            else{
                $controller = $ctrl;
            }
            
            if(array_key_exists($controller, $controllers)){
                
                $ctrlclass = Project::generateNamespacedClass($controller, 'controllers');
                
                //check if the method has been set as the action
                if(method_exists($ctrlclass, $action)){
                    
                    //get the reader
                    $reader = new Reader($ctrlclass, $action);
                    $annotations = $reader->getParameters();
                    
                    $ar = $this->_getActionAndRole($annotations);
                    
                    if(!is_null($ar) && array_key_exists('role', $ar)){
                        
                        $role = $ar['role'];
                        $eval = $this->_evaluate($role);
                        
                        if($eval){
                            
                            //on Allowed
                            $user->role->onAllowed($element, $controller, $action);
                            return TRUE;
                        }
                        else{
                            
                            //on Allowed
                            $user->role->onDenied($element, $controller, $action);
                            return FALSE;
                        }
                    }
                    else{
                        $error_message = 'ROLE_NOT_DEFINED';
                    }
                }
                else{
                    //check if action is annoted
                    $methods = get_class_methods($ctrlclass);
                    
                    //loop through the class methods
                    foreach($methods as $method){
                        
                        //get the reader
                        $reader = new Reader($ctrlclass, $method);
                        $annotations = $reader->getParameters();
                        
                        if(count($annotations) > 0){
                            
                            $ar = $this->_getActionAndRole($annotations);
                            
                            if(!is_null($ar['action'])){
                                
                                if($ar['action'] == $action){
                                    
                                    if(array_key_exists('role', $ar)){
                        
                                        $role = $ar['role'];
                                        $eval = $this->_evaluate($role);
                        
                                        if($eval){

                                            //on Allowed
                                            $user->role->onAllowed($element, $controller, $action);
                                            return TRUE;
                                        }
                                        else{

                                            //on Allowed
                                            $user->role->onDenied($element, $controller, $action);
                                            return FALSE;
                                        }
                                    }
                                    else{
                                        $error_message = 'ROLE_NOT_DEFINED';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        else{
            $error_message = 'ELEMENT_NOT_REGISTRED';
        }
        
        //return error message or not    
        if($error_message != '' && $debug){
            App::warning($error_message);
        }
        
        if($error_message == 'ELEMENT_NOT_REGISTRED'){
            
            //on Denied
            $user->role->onDenied($element, $controller, $action);
            return FALSE;
        }
        else{
            
            //still allow access but throw warning
            $user->role->onAllowed($element, $controller, $action);
            return TRUE;
        }
        
        
    }
    
    /**
     * Returns the annoted action and role
     * 
     * @param type $annotations
     * @return type
     */
    private function _getActionAndRole($annotations){
        
        //get the annoted role
        if(array_key_exists('acl\role', $annotations)){
            $attr['role'] = $annotations['acl\role'];
        }

        //get the annoted action
        if(array_key_exists('acl\action', $annotations)){
            $attr['action'] = $annotations['acl\action'];
        }
        elseif(array_key_exists('acl\alias', $annotations)){
            $attr['action'] = $annotations['acl\alias'];
        }
        
        return $attr;
    }
    
    /**
     * Checks the role alias to verify user role
     * 
     * @param type $alias
     * @example  $this->user()->is('admin')
     * 
     * @return boolean TRUE or FALSE
     */
    public function is($alias){
        
        $gateway = App::get('gateway');
        $useralias = $gateway->user()->role->alias;
        
        if($alias == $useralias)
            return TRUE;
        
        return FALSE;
    }
    
    /**
     * This is the function called from the controller to check user permissions
     * 
     * @param type $element
     * @param type $controller
     * @param type $method
     */
    public function canExecute($element, $controller, $method = 'index'){
        
        //if this the the authorizing element, allow access
        if(App::get('gateway')->auth_element == $element){                
            return TRUE;
        }
        
        $reader = new Reader($controller, $method);
        $annotations = $reader->getParameters();
        
        if(count($annotations) > 0){
            
            foreach($annotations as $key => $value){
                
                if(in_array($key, $this->annotations)){
                    
                    switch ($key) {
                        case 'acl\role':
                            
                            $gateway = App::get('gateway');
                            $role = $gateway->getRoleByAlias($value);
                            
                            $userlevel = $gateway->user()->role->level;
                            $rolelevel = $role->level;
                            
                            if($userlevel >= $rolelevel){
                                return TRUE;
                            }
                            else{
                                return FALSE;
                            }
                            break;
                    }
                }
            }
            
            return TRUE;
        }
        else{
            //if the acl annotations arent set allow access
            return TRUE;
        }
    }
    
    /**
     * Check role against alias
     * 
     * @param type $rolevalue
     * @return boolean
     */
    private function _evaluate($rolevalue){
        
        $gateway = App::get('gateway');
        $role = $gateway->getRoleByAlias($rolevalue);

        $userlevel = $gateway->user()->role->level;
        $rolelevel = $role->level;

        if($userlevel >= $rolelevel){
            return TRUE;
        }
        else{
            return FALSE;
        }
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
}