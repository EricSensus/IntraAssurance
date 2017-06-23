<?php
namespace Jenga\MyProject\Users\Acl\Roles;

use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Security\Permissions;

class Techadmin extends Project {

    public $name;
    public $alias;
    public $description;
    public $level;
    public $permissions;

    public function __construct(){

        $this->name = 'Technical Admin';
        $this->alias = 'superadmin';
        $this->description = 'This is the role for the external technical system administrator';
        $this->level = 4;
        
        $this->setPermissions();
        
        return $this;
    }
    
    /**
     * This function sets the level permissions into the user role
     */
    public function setPermissions($permissions = NULL){        
        
        $this->permissions = new Permissions($this->level);
        
        if(!is_null($permissions))
            $this->permissions->addUserPermissions($permissions);
    }
    
    /**
     * This function will be called after the designated user has been denied access to a class method
     * 
     * @param type $element
     * @param type $controller
     * @param type $method
     */
    public function onDenied($element, $controller, $method){
    }
    
    /**
     * 
     * This function will be called after the designated user has been allowed access to a class method
     * 
     * @param type $element
     * @param type $controller
     * @param type $method
     */
    public function onAllowed($element, $controller, $method) {        
    }
}