<?php
namespace Jenga\App\Request;

use Jenga\App\Core\App;
use Symfony\Component\HttpFoundation\Session\Session as SymSession;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

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
        
        if(App::get('_config')->session_storage_type == 'database'){
            
            //replace the native Symfony handler with the custom Jenga Handler
            $sesshandler = App::get('Jenga\App\Request\Handlers\SessionHandler');

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
    public static function all(){
        
        return self::$_symsession->all();
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
        
        if(is_string($key) || is_int($key)){
            
            if(array_key_exists($key, $sessionkeys)){            
                return TRUE;
            }
            else{    

                //also check the flash massages
                if(self::$_flash->has($key)){
                    return TRUE;
                }
                else{
                    return FALSE;
                }
            }
        }
    }
}