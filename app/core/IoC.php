<?php
namespace Jenga\App\Core;

use DI\ContainerBuilder;
use Jenga\MyProject\Config;

/**
 * This is a facade for PHP-DI to allow for proper integration into the Jenga Framework
 */
class IoC extends ContainerBuilder {
    
    private $_config;
    
    public $build;
    public $autowiring;
    public $definitions;
    
    public static $handlers;
    
    public function __construct(Config $config){
        
        parent::__construct();
        
        $this->build = $this;
        $this->_config = $config;
        
        $this->build->useAutowiring(true);
        $this->build->useAnnotations(true);
    }
    
    /**
     * Processes the registered service handlers
     */
    public function registerHandlers(){
        
        self::$handlers = include APP .DS. 'services' .DS. 'services.php';
        
        //process the services
        foreach(self::$handlers['handlers'] as $handle => $service){
            
            $handle = str_replace(' ', '', strtolower($handle));
            
            if(!is_array($service)){
                
                $this->definitions[$handle] = \DI\object($service);
            }
            else{
                
                //check if mode is set
                if(array_key_exists('mode', $service)){

                    switch ($service['mode']) {

                        case 'lazy':
                            $this->definitions[$handle] = \DI\object($service['class'])->lazy();
                            break;

                        default:
                            $this->definitions[$handle] = \DI\object($service['class']);
                            break;
                    }
                }
                
                //unset service mode and class
                unset($service['class'], $service['mode']);
                
                if(count($service)>=1){
                    
                    foreach($service as $servicekey => $value){
                        $this->definitions[$servicekey] = $value;
                    }
                }
            }
            
            /**
            //get any additional configs
            if(file_exists(APP .DS. 'services' .DS. 'config' .DS. $handle.'.php')){
                
                $configs = include APP .DS. 'services' .DS. 'config' .DS. $handle.'.php';
                
                if(is_array($configs)){
                    
                    foreach($configs as $config => $value){  
                        $this->definitions[$config] = $value;
                    }
                }
            }
             * 
             */
        }
        
        //register database entries
        $this->_registerDatabaseConfigurations();
        
        //add definitions to IoC shell
        $this->build->addDefinitions($this->definitions);
        
        return $this;
    }
    
    /**
     * Registers the database entries into the IoC definitions
     */
    private function _registerDatabaseConfigurations() {
        
        //database name
        $this->definitions['db'] = $this->_config->db;
        
        //database prefix
        $this->definitions['dbprefix'] = $this->_config->dbprefix;
        
        //host
        $this->definitions['host'] = $this->_config->host;
        
        //username
        $this->definitions['username'] = $this->_config->username;
        
        //password
        $this->definitions['password'] = $this->_config->password;
        
        //port
        if($this->_config->password == ''){
            $this->definitions['port'] = ini_get('mysqli.default.port');
        }
        else{
            $this->definitions['port'] = $this->_config->port;
        }
    }
    
    public function register($ignoredocerrors = FALSE) {
        
        if($ignoredocerrors == TRUE){
            $this->build->ignorePhpDocErrors($ignoredocerrors);
        }
        
        return $this->build->build();
    }
}

