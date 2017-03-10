<?php
namespace Jenga\App\Project\Security\Traits;

use Jenga\App\Core\App;
use Jenga\App\Request\Session;
use Jenga\App\Project\Security\User;
use Jenga\App\Project\Security\Traits\UserSessionHandler;

use Symfony\Component\HttpFoundation\Request;

trait Authentication{
    
    use UserSessionHandler;
    
    protected $config;
    protected $request;
    public static $instance;

    public $user;
    public $roles;
    public $permissions;
    public $outputformat = 'array';
    public $auth_element;
    
    /**
     * @Inject("auth_source")
     */
    public $auth_source;
    
    /**
     * @Inject("auth_table")
     */
    public $auth_table;
    
    /**
     * @Inject("auth_file")
     */
    public $auth_file;
    
    public function init(Request $request) {
        
        $this->request = $request;
        
        $this->getConfigs();
        
        //configure user object into database if configured
        $this->configureSession();
        
        //set the sessions security token
        $this->setSecurityToken();
        
        //set the assigned user attributes from User Defined Auth class
        $this->setUserAttributes();
        
        //get the listed roles eiter from the database or from a flat file
        $this->getRoles();
        
        return $this;
    }
    
    /**
     * Get the system configurations from the App shell
     */
    protected function getConfigs() {        
        $this->config = App::get('_config');
    }
    
    /**
     * Get the configured roles
     * @param type $identifier
     * @param type $format
     * @return type
     */
    public function getRoles($identifier = null,$format = null){
        
        if(strtolower($this->auth_source) == 'database'){
            
            $this->format(($format==null ? $this->outputformat : $format))->table($this->auth_table, 'NATIVE');
            
            if(is_null($identifier)){
                return $this->orderBy('level', 'DESC')->show();
            }
            else{                
                return $this->find($identifier)->data;
            }
        }
    }
    
    /**
     * Returns the lowest role/;
     * @return type
     */
    protected function getLowestRole(){
        
        $roles = $this->getRoles();
        return end($roles);
    }
    
    /**
     * Add a security token to the user object
     */
    public function setSecurityToken(){
        
        $token = $this->token();
        
        //set token into session
        Session::add('token', $token);
        $this->user->setToken($token);
    }
    
    /**
     * Returns current user if called statically
     * @return type
     */
    public static function user(){
        
        return unserialize(Session::get('user_'.Session::get('token')));
    }
    
    /**
     * Generates random token for each session
     * @return type
    */
    public function token(){
        
        // if we need to identify sessions by also checking the user agent
        if ($this->config->lock_to_user_agent 
                && !is_null($this->request->headers->get('User-Agent'))){
            $hash .= $this->request->headers->get('User-Agent');
        }

        // if we need to identify sessions by also checking the host
        if ($this->config->lock_to_ip 
                && !is_null($this->request->getClientIp())){
            $hash .= $this->request->getClientIp();
        }
        
        return md5($hash);
    }
}