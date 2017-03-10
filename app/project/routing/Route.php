<?php
namespace Jenga\App\Project\Routing;

use Jenga\App\Core\App;
use Jenga\App\Helpers\Help;
use Jenga\App\Project\EventsHandler\Events;

class Route {
    
    private static $_eventstack;
    private static $_routes = [];
    private static $_currentroute;    
    private static $_instance;
    private static $_anyflag = 0;
    
    public static function init ($routesfile){
        
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        
        //this will be used to override the default Route
        require $routesfile;

        return self::$_instance;
    }   
    
    /**
     * Processes the routes inside group
     * @param array $groupname
     * @param \Closure $closure
     */
    public static function group(array $groupname, \Closure $closure){
        
        if(Events::keywordGroupCheck($groupname)){            
            self::_setEventStack($groupname);
        }
        
        App::call($closure, self);
        
        if(!is_null(self::$_eventstack))
            array_pop(self::$_eventstack);
        
        return $this;
    }
    
    /**
     * Processes routes with the GET request method
     * @param type $url
     * @param type $engine
     */
    public static function get($url, $engine, array $defaults = null, $return = true){
        
        $alias = self::_createAlias($url,'GET');
        
        if(substr($url, -1) == '/'){
            $url = rtrim($url,'/');
        }
        
        self::$_routes[$alias]['url'] = $url;
        
        if(Help::is_closure($engine)){
            self::$_routes[$alias]['engine'] = $engine();
        }
        else
            self::$_routes[$alias]['engine'] = $engine;
        
        //process the defaults
        self::$_routes[$alias]['defaults'] = $defaults;
        
        //add the event into the route
        if(count(self::$_eventstack) >= 1){                
            self::$_routes[$alias]['event'] = self::$_eventstack;
        }  
        
        if(self::$_anyflag !== 0 && $return == false){
            
            if(!is_array(self::$_currentroute)){
                self::$_currentroute = [];
            }
            
            self::$_currentroute[ self::$_anyflag ][] = $alias;
        }
        elseif(self::$_anyflag !== 0 && $return == true){
            
            if(!is_array(self::$_currentroute)){
                self::$_currentroute = '';
            }
            
            self::$_currentroute = $alias;
        }
        else{
            self::$_currentroute = $alias;
        }
        
        if($return == true)
            return self::$_instance;
    }
    
    /**
     * Processes routes with the POST request method
     * @param type $url
     * @param type $engine
     */
    public static function post($url, $engine, array $defaults = null, $return = true){
        
        $alias = self::_createAlias($url,'POST');
        
        if(substr($url, -1) == '/'){
            $url = rtrim($url,'/');
        }
        
        self::$_routes[$alias]['url'] = $url;
        
        if(Help::is_closure($engine))
            self::$_routes[$alias]['engine'] = $engine();
        else
            self::$_routes[$alias]['engine'] = $engine;
        
        //process the defaults
        self::$_routes[$alias]['defaults'] = $defaults;
        
        //add the event into the route
        if(count(self::$_eventstack) >= 1){                
            self::$_routes[$alias]['event'] = self::$_eventstack;
        } 
        
        if(self::$_anyflag !== 0 && $return == false){
            
            if(!is_array(self::$_currentroute)){
                self::$_currentroute = [];
            }
            
            self::$_currentroute[ self::$_anyflag ][] = $alias;
        }
        elseif(self::$_anyflag !== 0 && $return == true){
            
            if(!is_array(self::$_currentroute)){
                self::$_currentroute = '';
            }
            
            self::$_currentroute = $alias;
        }
        else{
            self::$_currentroute = $alias;
        }
        
        if($return == true)
            return self::$_instance;
    }
    
    /**
     * Processes dual routes for the GET & POST request methods
     * 
     * @param type $url
     * @param type $engine
     * @param type $defaults
     */
    public static function any($url, $engine, array $defaults = null){
        
        //set the any flag to true, for multiple processing
        self::$_anyflag = rand(0, 10000000);
        
        //process the GET method
        self::get($url, $engine, $defaults, false);
        
        //process the POST method
        self::post($url, $engine, $defaults, false);
        
        return self::$_instance;
    }
    
    /**
     * Processes routes with the PUT request method
     * 
     * @param type $url
     * @param type $engine
     */
    public static function put($url, $engine, $defaults = null){
        
        $alias = self::_createAlias($url,'PUT');
        
        if(substr($url, -1) == '/'){
            $url = rtrim($url,'/');
        }
        
        self::$_routes[$alias]['url'] = $url;
        
        if(Help::is_closure($engine))
            self::$_routes[$alias]['engine'] = $engine();
        else
            self::$_routes[$alias]['engine'] = $engine;
        
        //process the defaults
        self::$_routes[$alias]['defaults'] = $defaults;
        
        //add the event into the route
        if(count(self::$_eventstack) >= 1){                
            self::$_routes[$alias]['event'] = self::$_eventstack;
        } 
        
        self::$_currentroute = $alias;
        
        return self::$_instance;
    }
    
    /**
     * Processes routes with the DELETE request method
     * 
     * @param type $url
     * @param type $engine
     */
    public static function delete($url, $engine, $defaults = null){
        
        $alias = self::_createAlias($url,'DELETE');
        self::$_routes[$alias]['url'] = $url;
        
        if(Help::is_closure($engine))
            self::$_routes[$alias]['engine'] = $engine();
        else
            self::$_routes[$alias]['engine'] = $engine;
        
        //process the defaults
        self::$_routes[$alias]['defaults'] = $defaults;
        
        //add the event into the route
        if(count(self::$_eventstack) >= 1){                
            self::$_routes[$alias]['event'] = self::$_eventstack;
        } 
        
        self::$_currentroute = $alias;
        
        return self::$_instance;
    }
    
    /**
     * Processes routes with the HEAD request method
     * 
     * @param type $url
     * @param type $engine
     */
    public static function head($url, $engine, $defaults = null){
        
        $alias = self::_createAlias($url,'HEAD');
        
        if(substr($url, -1) == '/'){
            $url = rtrim($url,'/');
        }
        
        self::$_routes[$alias]['url'] = $url;
        
        if(Help::is_closure($engine))
            self::$_routes[$alias]['engine'] = $engine();
        else
            self::$_routes[$alias]['engine'] = $engine;
        
        //process the defaults
        self::$_routes[$alias]['defaults'] = $defaults;
        
        //add the event into the route
        if(count(self::$_eventstack) >= 1){                
            self::$_routes[$alias]['event'] = self::$_eventstack;
        } 
        
        self::$_currentroute = $alias;
        
        return self::$_instance;
    }
    
    /**
     * Processes routes with static URL regardless of request method
     * 
     * @param type $url
     * @param type $engine
     * @param type $defaults
     * @return type
     */
    public static function url($url, $engine){
        
        $alias = self::_createAlias($url,'ANY');
        self::$_routes[$alias]['url'] = $url;
        
        if(Help::is_closure($engine))
            self::$_routes[$alias]['engine'] = $engine();
        else
            self::$_routes[$alias]['engine'] = $engine;
        
        //add the event into the route
        if(count(self::$_eventstack) >= 1){                
            self::$_routes[$alias]['event'] = self::$_eventstack;
        } 
        
        self::$_currentroute = $alias;
        
        return self::$_instance;
    }
    
    /**
     * Allows for specifying of certain conditions for a route
     * 
     * @param array $regex The format should be ['placeholder'=>'Regular Expression']
     */
    public function where(array $regex){
        
        if(is_array(self::$_currentroute) && self::$_anyflag !== 0){
            
            if(count(self::$_currentroute[ self::$_anyflag ]) >= 1){
                
                foreach (self::$_currentroute[ self::$_anyflag ] as $route) {
                    self::$_routes[$route]['regex'] = $regex;
                }
            }
        }
        else{
            self::$_routes[self::$_currentroute]['regex'] = $regex;
        }
        
        return $this;
    }
    
    /**
     * Assigns resources to sent url
     * 
     * @param type $url
     * @param type $resources
     */
    public static function resources($url,$resources){
        
        $alias = self::_createAlias($url,'ANY');  
        self::$_routes[$alias]['resources'] = $resources;
    }
    
    /**
     * Same function as self::resources but applies to current route
     * @param type $url
     * @param type $resources
     */
    public function assignResources($resources){
           
        if(is_array(self::$_currentroute) && self::$_anyflag !== 0){
            
            if(count(self::$_currentroute[ self::$_anyflag ]) >= 1){
                
                foreach (self::$_currentroute[ self::$_anyflag ] as $route) {
                    self::$_routes[$route]['resources'] = $resources;
                }
            }
        }
        else{
            self::$_routes[self::$_currentroute]['resources'] = $resources;
        }
        
        return $this;
    }
    
    /**
     * Assign the template to current route
     * @param type $template
     */
    public function attachTemplate($template) {
        
        if(is_array(self::$_currentroute) && self::$_anyflag !== 0){
            
            if(count(self::$_currentroute[ self::$_anyflag ]) >= 1){
                
                foreach (self::$_currentroute[ self::$_anyflag ] as $route) {
                    self::$_routes[$route]['template'] = $template;
                }
            }
        }
        else{
            self::$_routes[self::$_currentroute]['template'] = $template;
        }
        return $this;
    }
    
    /**
     * Assign the route panels to current route
     * @param type $template
     * @param type $panels
     * @return type
     */
    public function assignPanels($template ,$panels = null) {
       
        if(is_string($template)){
            $this->attachTemplate($template);
        }
        elseif(is_array($template)){
            $panels = $template;
        }
       
        if(is_array(self::$_currentroute) && self::$_anyflag !== 0){
            
            if(count(self::$_currentroute[ self::$_anyflag ]) >= 1){
                
                foreach (self::$_currentroute[ self::$_anyflag ] as $route) {
                    self::$_routes[$route]['panels'] = $panels;
                }
            }
        }
        else{            
            self::$_routes[self::$_currentroute]['panels'] = $panels;
        }
        
        return $this;
    }

    /**
     * Processes the event stack
     * 
     * @param type $event
     */
    private static function _setEventStack($event){
        
        self::$_eventstack = $event;
    }
    
    /**
     * Creates a route alias to be used to identify the route
     */
    private static function _createAlias(&$url, $method){
        
        //remove url variables
        if(is_string($url)){
            
            $url1 = str_replace('{', '', $url);
            $url2 = str_replace('}', '', $url1);

            if($url2 == '/'){
                $url2 .= 'none';
            }

            //replace forward slash with underscore
            $alias = strtolower($method).str_replace('/', '_', rtrim($url2, '/'));
        }
        elseif(is_array($url)){
            
            $key = array_keys($url)[0];
            
            $url = $url[$key];
            $alias = strtolower($method).'_'.$key;
        }
        
        return $alias;
    }
    
    /**
     * Creates a route alias to be used to identify the route
     * @uses Url::route Used for URL generation
     */
    public static function generateAlias($url){
        
        //remove url variables
        $url1 = str_replace('{', '', $url);
        $url2 = str_replace('}', '', $url1);
        
        if($url2 == '/'){
            $url2 .= 'none';
        }
           
        //replace forward slash with underscore
        $alias = str_replace('/', '_', rtrim($url2, '/'));
        $alias = ltrim($alias, '_');
        
        return $alias;
    }
    
    /**
     * Returns the processed routes
     * @return type
     */
    public function process() {
        
        return self::$_routes;
    }
}