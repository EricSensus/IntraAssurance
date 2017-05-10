<?php
namespace Jenga\MyProject\Users\Acl;

use Jenga\App\Models\ORM;
use Jenga\App\Request\Session;
use Jenga\App\Project\Security\Traits\Authentication;
use Jenga\App\Project\Security\UserPermissionsInterface;

class Gateway extends ORM implements UserPermissionsInterface {
    
    use Authentication;
    
    /**
     * Set the user attributes in the attributes array
     * @example $attributes = ['id','fullname','username','password','accesslevel','loggedin'];
     *
     * The attributes set here will be accessed from the global $this->user() function
     * @example $this->user()->fullname or $this->user()->getFullname()
     */
    public function setUserAttributes() { 
 
        $attributes = ['id','fullname','username','password','accesslevel','loggedin','user_profiles_id',
            'insurer_agents_id','enabled','verified'];
        $this->user->setAttributes($attributes);
    }
    
    /**
     * This set the element that will be used to authenticate the system users and 
     * therefore isn't subject to any autorization events which may restrict its access
     */
    public function setAuthorizationElement() {
        $this->auth_element = 'users';
    }
    
    /**
     * Checks if user is logged in
     * 
     * @return boolean
     */
    public static function isLogged(){
        
        if(is_int(self::user()->loggedin))
            return TRUE;
        
        return FALSE;
    }
}
