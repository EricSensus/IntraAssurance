<?php
namespace Jenga\App\Project\Security;

use Jenga\App\Project\Security\Permissions;

class Roles {
    
    public $name;
    public $alias;
    public $description;
    public $level;
    public $permissions;
    
    public function __construct($roles) {
        
        if(is_bool($roles)){
            $roles = $this->defaultRole();
        }
        
        extract($roles, EXTR_SKIP);
        
        $this->setName($name);
        $this->setAlias($alias);
        $this->setDescription($description);
        $this->setLevel($level);
        $this->setPermissions($permissions);
    }
    
    /**
     * Sets the role name
     * @param type $name
     */
    public function setName($name){
        $this->name = $name;
    }
    
    public function setAlias($alias = null){
        
        if(is_null($alias)){            
            $this->alias = str_replace(' ', '-', $this->name);
        }
        else{
            $this->alias = $alias;
        }
    }
    
    public function setDescription($desc = null){
        $this->description = $desc;
    }
    
    public function setLevel($level){
        $this->level = $level;
    }
    
    public function setPermissions($permissions){
        
        $this->permissions = new Permissions($permissions, $this->level);
    }
    
    /**
     * Returns lowest level if access levels aren't defined
     * 
     * @return type
     */
    public function defaultRole(){
         return [
             'level' => 0
         ];
     }
}