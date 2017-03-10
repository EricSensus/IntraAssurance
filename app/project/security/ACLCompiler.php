<?php
namespace Jenga\App\Project\Security;

/**
 * ACLCompiler
 * 
 * This class analyzes class method annotations and allocates the base Access Control Level 
 * using the following annotations
 * 
 * @acl:level - Takes an integer indicating the minimum access level allowed access to the class method
 * @example acl:level 0
 * 
 * $acl:alias - Assigns a human readable alias to the class method
 * @example acl:alias "Human Readable Method Name"
 *
 * @author Stanley Ngumo
 */

use Jenga\App\Core\File;
use Jenga\App\Views\Notifications;
use Jenga\App\Project\Core\Project;

use DocBlockReader\Reader;

class ACLCompiler {
    
    public $elements;
    public $elementkeys;
    public $actions;
    public $approvedlist;

    public $annotations = ['acl\level','acl\alias'];
    public $compilelist;
    
    /**
     * This is designed to bypass invocation of the __construct() 
     * which hinders Symfony argument resolution in Jenga
     * @return type
     */
    function __invoke() {
        
        $ctrl = new \ReflectionClass(get_class($this));
        return $ctrl->newInstanceWithoutConstructor();
    }
    
    public function __construct($action = null) {
        
        $this->elements = Project::elements();        
        $this->elementkeys = array_keys($this->elements);
        
        //get the cntrollers for each element
        foreach ($this->elementkeys as $ename) {
            
            if(!is_null($this->elements[$ename]['controllers'])){
                
                $econtrollers = array_keys($this->elements[$ename]['controllers']);
                
                //link each controller to its namespaced class
                foreach ($econtrollers as $controller) {

                    $elist[$ename][$controller] = 'Jenga\MyProject\\'.ucfirst($ename).'\Controllers\\'.ucfirst($controller);
                }
            }
        }
        
        $this->compilelist = $elist;        
        $this->actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
        
        if(!is_null($action)){
            $this->{$action['action']}();
        }
        else{
            $this->run();
        }
    }
    
    /**
     * Gets the class methods which have been annotaed
     */
    public function getAnnotatedActions() {
        
        //process class
        foreach ($this->compilelist as $element => $controller) {
            
            foreach ($controller as $ctrlname => $fullctrl) {
                
                $methodlist = [];
                $methods = $this->_get_this_class_methods($fullctrl);
                
                //process class methods
                foreach($methods as $method){

                    $reader = new Reader($fullctrl, $method);
                    $annotations = $reader->getParameters();
                    
                    $result = $this->_parseAnnotations($annotations);
                    
                    if(!is_null($result)){
                        $methodlist[$ctrlname][$method] = $result;
                    }
                }
            }
            
            $elmlist[$element] = $methodlist;
        }
        
        return $elmlist;
    }
    
    private function _get_this_class_methods($class){
        
        $array1 = get_class_methods($class);
        
        if($parent_class = get_parent_class($class)){
            
            $array2 = get_class_methods($parent_class);
            $array3 = array_diff($array1, $array2);
        }else{
            
            $array3 = $array1;
        }
        
        return($array3);
    }
    
    /**
     * Filter out only the ACL annotations
     * @param type $annotations
     */
    private function _parseAnnotations($annotations) {
        
        if(count($annotations) > 0){
            
            $akeys = array_keys($annotations);
            
            foreach($akeys as $akey){
                
                if(in_array($akey, $this->annotations)){
                    
                    foreach($this->annotations as $filter){
                        
                        if($akey == $filter){
                            $list[$filter] = $annotations[$akey];
                        }
                    }
                }
            }
        }
        
        return $list;
    }
    
    public function compileBaseACLs() {
        
        //get the cntrollers for each element
        foreach ($this->elementkeys as $ename) {  
            
            $acl = $this->elements[$ename]['acl'];
            
            if(!is_null($acl)){
                $this->actions[$ename]['level'] = $acl;
            }
        }
    }
    
    public function compileAclActions($list){
        
        foreach ($this->actions as $ctrl => $acl) {
            
            if(count($list[$ctrl]) > 0){
                
               $controller = $list[$ctrl];
               $ctrlkeys = array_keys($controller);
               
               foreach ($ctrlkeys as $ctrlkey) {
                   
                   foreach($controller[$ctrlkey] as $method => $acls){
                       
                        $this->approvedlist[$ctrl]['actions'][strtolower($method)] = $ctrlkey.'@'.$method;
                        $this->approvedlist[$ctrl]['alias'][strtolower($method)] = $acls['acl\alias'];
                        $this->approvedlist[$ctrl]['level'] = $acls['acl\level'];
                   }
               }
            }
            else{
                
                $this->approvedlist[$ctrl] = $acl;
            }
        }
    }

    public function run() {
        
        //run the xml acl levels
        $this->compileBaseACLs();
        
        //run annotated actions
        $list = $this->getAnnotatedActions();
        
        //run the acl actions and aliases
        $this->compileAclActions($list);
        
        $this->save();
        
        echo Notifications::Alert('The ACL settings have been saved. Please refresh page to see new settings', 'success', TRUE, TRUE);
    }
    
    public function save() {
        File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($this->approvedlist));
    }
}
