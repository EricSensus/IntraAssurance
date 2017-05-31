<?php
namespace Jenga\App\Request;

use Jenga\App\Core\App;
use Jenga\App\Project\Security\User;
use Jenga\App\Request\Handlers\SessionHandler;

use Symfony\Component\HttpFoundation\Session\Session as SymSession;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/**
 * Handles Session management for the Jenga Framework
 */
class Session {
    
    public static $sanitize;
    public static $filter_rules = NULL;
    
    private static $_keywords = array('event_token');
    private static $_symsession;
    private static $_flash;
    public static $instance; 
    
    public static function __callStatic($name, $arguments) {
        
        self::$instance = new static;
        
        if(method_exists(self::$instance, $name)){
            return call_user_func_array([self::$instance, $name], $arguments);
        }
    }
    
    /**
     * Initializes the session
     */
    public static function start() {
        
        //configure the core session settings
        self::configure(App::get('_config'));
        
        if(App::get('_config')->session_storage_type == 'database'){
            
            //replace the native Symfony handler with the custom Jenga Handler
            $sesshandler = App::get(SessionHandler::class);

            $storage = new NativeSessionStorage([], $sesshandler);
            self::init(new SymSession($storage));        
        }
        elseif(App::get('_config')->session_storage_type == 'file'){            
            self::init(new SymSession());
        }
        
        self::$_symsession->start();
    }

    public static function init(SymSession $symsession){ 
        
        self::$_symsession = $symsession;
        self::$_flash = self::$_symsession->getFlashBag();
    }
    
    /**
     * Initializes the core session functions
     */
    public static function configure($config){
        
        //assign the settings fron the config class
        $session_lifetime = $config->session_lifetime; 
        $gc_probability = $config->gc_probability; 
        $gc_divisor = $config->gc_divisor; 
        
        // make sure session cookies never expire so that session lifetime
        // will depend only on the value of $session_lifetime
        ini_set('session.cookie_lifetime', 0);

        // if $session_lifetime is specified and is an integer number
        if ($session_lifetime != '' && is_integer($session_lifetime))

            // set the new value
            ini_set('session.gc_maxlifetime', (int)$session_lifetime);

        // if $gc_probability is specified and is an integer number
        if ($gc_probability != '' && is_integer($gc_probability))

            // set the new value
            ini_set('session.gc_probability', $gc_probability);

        // if $gc_divisor is specified and is an integer number
        if ($gc_divisor != '' && is_integer($gc_divisor))

            // set the new value
            ini_set('session.gc_divisor', $gc_divisor);
    }
    
    private static function _put($key, $value){        
        self::$_symsession->set($key,$value);
    }
    
    private static function _retrieve($key){   
        
        $chk = self::$_symsession->get($key);  
        
        //if null also check flash messages
        if(is_null($chk)){
            $chk = self::$_flash->get($key);
        }
        
        if(is_array($chk)){
            return $chk[0];
        }
        
        return $chk;
    }

    /**
     * Return the current session id
     * @return type $sessionid
     */
    public static function id(){        
        return self::$_symsession->getId();
    }
    
    /**
     * Used to add new vaues into the session
     * 
     * @param type $key
     * @param type $value
     */
    public static function add($key, $value){
        
        if(!array_key_exists($key, self::$_keywords)){            
            self::_put($key, $value);
        }
        else{
            
            App::critical_error('The session key ['.$key.'] is already in use, please use another');
        }
    }
    
    /**
     * Used to add new vaues into the session
     * 
     * @param type $key
     * @param type $value
     */
    public static function set($key, $value){
        
        if(!array_key_exists($key, self::$_keywords)){            
            self::_put($key, $value);
        }
        else{
            
            App::critical_error('The session key ['.$key.'] is already in use, please use another');
        }
    }
    
    /**
     * Used to retrieve values from the current session
     * 
     * @param type $key
     * @return mixed session values
     */
    public static function get($key){        
        return self::_retrieve($key);
    }
    
    /**
     * Returns all the values saved in the session
     * 
     * @return type
     */
    public static function all($include_token = false){        
        
        $allvalues = self::$_symsession->all();
        
        foreach($allvalues as $key => $value){
            
            //remove security token and user class
            if($include_token === FALSE){
                if(!(unserialize($value) instanceof User) && $key !== 'token'){
                    $list[$key] = $value;
                }
            }
            else{
                $list = $allvalues;
            }
        }
        
        return $list;
    }
    
    /**
     * Removes a specified key from the session
     * 
     * @param type $key
     */
    public static function delete($key){        
        return self::$_symsession->remove($key);
    }
    
    /**
     * Destroys the current session
     */
    public static function destroy(){        
        return self::$_symsession->invalidate();
    }
    
    /**
     * Sets up the values for flash data
     * @param type $type
     * @param type $value
     */
    public static function flash($type, $value){        
       self::$_flash->add($type, $value);
    }
    
    /**
     * Converts the flash data into more permanent session information which is kept over many requests
     * @param type $key
     */
    public static function keep($key){
        
        $data = self::get($key);
        
        self::delete($key);
        self::add($key, $data);
    }
    
    /**
     * Checks if the entered session key exists
     * @param type $key
     * @return boolean
     */
    public static function has($key){
        
        $sessionkeys = self::all();
        
        if(!is_null($sessionkeys)){
            
            if(array_key_exists($key, $sessionkeys)){   

                //check for null session key
                if(!is_null($sessionkeys[$key]))
                    return TRUE;            
            }
            else{    

                //also check the flash massages
                if(self::$_flash->has($key)){
                    return TRUE;
                }
            }
        }
        
        return FALSE;
    }
    
    /**
     * Returns the set user security token
     * @return boolean
     */
    public static function getSecurityToken() {
        
        $token = Session::get('token');
        
        if(is_null($token))
            return FALSE;
        
        return $token;
    }
}