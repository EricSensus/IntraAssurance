<?php
namespace Jenga\App\Project\Security\Acl;

/**
 * ACLCompiler
 * 
 * This class analyzes class method annotations and allocates the base Access Control Level 
 * using the following annotations
 * 
 * @acl\role - Assigns the role alias indicating the minimum access level allowed access to the class method
 * @example acl\role guest
 * 
 * @acl\level - Takes an integer indicating the minimum access level allowed access to the class method
 * @example acl\level 0
 * 
 * @acl\action - Assigns a user action to the class method
 * @example acl\action 
 * 
 * @acl\alias - Assigns a human readable alias to the class method
 * @example acl\alias "Human Readable Method Name"
 *
 * @author Stanley Ngumo
 */

use Jenga\App\Core\App;
use Jenga\App\Core\File;
use Jenga\App\Views\Notifications;
use Jenga\App\Project\Core\Project;

use DocBlockReader\Reader;

class ACLCompiler {
    
    public $elements;
    public $elementkeys;
    public $actions;
    public $approvedlist;

    public $annotations = ['acl\level','acl\role','acl\action','acl\alias'];
    public $compilelist;
    
    /**
     * This is designed to bypass invocation of the __construct() 
     * which hinders Symfony argument resolution in Jenga
     * @return type
     */
    public function __invoke(){
        
        $ctrl = new \ReflectionClass(get_class($this));
        return $ctrl->newInstanceWithoutConstructor();
    }
    
    public function __construct() {
        
        $this->elements = Project::elements();        
        $this->elementkeys = array_keys($this->elements);
        
        //get the controllers for each element
        $elist = [];
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
        //$this->actions = unserialize(File::get(APP_PROJECT .DS. 'security' .DS. 'acl' .DS.'element_actions_levels.php'));
        
        return $this->run();
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
                
                if(!is_null($methods)){
                    
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
    private function _parseAnnotations($annotations){
        
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
        
        //get the controllers for each element
        foreach ($this->elementkeys as $ename) {  
            
            $acl = $this->elements[$ename]['acl'];
            
            if(!is_null($acl)){
                
                //add acl role or level based on whether its a number or string
                if(ctype_digit($acl))
                    $this->actions[$ename]['base']['level'] = $acl;
                elseif(!ctype_digit($acl))
                    $this->actions[$ename]['base']['role'] = $acl;
            }
        }
    }
    
    public function compileAclActions($list){
        
        foreach ($list as $ctrl => $method) {
            
            $acls = $this->actions[$ctrl];
            
            if(count($method) > 0){
                
               $controller = $list[$ctrl];
               $ctrlkeys = array_keys($controller);
               
               //add base attributes
               $this->approvedlist[$ctrl]['base'] = $acls['base'];
               
               foreach ($ctrlkeys as $ctrlkey) {
                   
                   foreach($controller[$ctrlkey] as $method => $acls){
                       
                       //set acl actions
                        $this->approvedlist[$ctrl]['actions'][strtolower($method)] = $ctrlkey.'@'.$method;
                        
                        //add alias
                        if(array_key_exists('acl\alias', $acls))
                            $this->approvedlist[$ctrl]['alias'][strtolower($method)] = $acls['acl\alias'];
                        
                        //add role 
                        if(array_key_exists('acl\role', $acls))
                            $this->approvedlist[$ctrl]['role'][strtolower($method)] = $acls['acl\role'];
                        
                        //add level
                        if(array_key_exists('acl\level', $acls))
                            $this->approvedlist[$ctrl]['level'] = $acls['acl\level'];
                   }
               }
            }
            else{
                
                if(array_key_exists('base', $acls))
                    $this->approvedlist[$ctrl]['base'] = $acls['base'];    
                else
                    $this->approvedlist[$ctrl]['base'] = ['level'=>0];
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
        
        return $this->save();
    }
    
    /**
     * Save the new ACL levels
     */
    public function save() {
        
        $file = APP_PROJECT .DS. 'security' .DS. 'acl' .DS. 'element_actions_levels.php';
        
        if(File::exists($file))
            File::delete ($file);
            
        return File::put($file, serialize($this->approvedlist));
    }
}
