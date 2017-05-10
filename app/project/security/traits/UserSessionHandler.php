<?php
namespace Jenga\App\Project\Security\Traits;

use Jenga\App\Core\App;
use Jenga\App\Models\ORM;
use Jenga\App\Request\Session;
use Jenga\App\Project\Security\User;

trait UserSessionHandler {
    
    /**
     * Creates the user instance to be used through out the session
     * @return object the User instance
     */
    public function createUserState(){
        
        //get User object, instatiated as singleton and thus will be reused
        $user = App::$shell->get(User::class);
        
        //on initial login the lowest role is assigned to the user
        $lrole = $this->getLowestRole();
        $user->attachRole($lrole);
        
        $this->user = $user;
        return $this->user;
    }
    
    /**
     * Configures full User State session
     */
    public function configureSession(){
        
        if(!is_null($this->user) && !is_null(Session::getSecurityToken())){
            Session::add('user_'.Session::getSecurityToken(), serialize($this->user));
        }
    }
    
    public function destroyUserState(){        
        return Session::delete('user_'.Session::get('token'));     
    }
}
