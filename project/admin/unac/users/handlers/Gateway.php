<?php
namespace Jenga\MyProject\Users\Handlers;

use Jenga\App\Models\ORM;
use Jenga\App\Request\Session;
use Jenga\App\Project\Security\Traits\Authentication;
use Jenga\App\Project\Security\UserPermissionsInterface;

class Gateway extends ORM implements UserPermissionsInterface {
    
    use Authentication;
    
    public function setUserAttributes() {  
        
        $this->user->setAttributes(['id','fullname','username','password','accesslevel','loggedin','profileid',
        'agentid','enabled','lastlogin','permissions']);
    }
    
    /**
     * This set the element that will be used to authenticate the system users and 
     * therefore isn't subject to any autorization events which may restrict its access
     */
    public function setAuthorizationElement() {
        $this->auth_element = 'users';
    }
    
    /**
     * Checks if the user is a guest or has been logged in
     * @return boolean
     */
    public static function isGuest(){
        
        if(Session::has('token')){             
            return FALSE;
        }
        else{
            return true;
        }
    }
    
    public static function isLogged(){
        
        if(is_int(self::user()->loggedin))
            return TRUE;
        
        return FALSE;
    }
}
