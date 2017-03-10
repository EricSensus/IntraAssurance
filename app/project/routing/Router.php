<?php
namespace Jenga\App\Project\Routing;

use Jenga\App\Core\App;
use Jenga\App\Helpers\Help;
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;
use Jenga\App\Project\Core\Resources;
use Jenga\App\Project\EventsHandler\RouteEvents;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route as SymfonyRoute;

class Router {
    
    public $jroutes;
    public $collector;
    public $resources;
    public $eventscheduler;
    
    private $_currentalias;
    private $_templates;
    private $_url;
    
    public $project;
    
    /**
     * @Inject({"routesfile","_project"})
     * @return \Jenga\App\Project\Routing\Router
     */
    public function __construct($routesfile, $project) {
        
        $this->collector = new RouteCollection();
        $this->resources = new Resources();
        $this->eventscheduler = new RouteEvents();
        
        $this->project = $project;  
        $this->_templates = Project::getTemplates();
        
        //insert the declared routes file & assign the route collection
        $routes = Route::init($routesfile);
        $this->jroutes = $routes->process();
        
        $this->_translate($this->jroutes);
        
        return $this;
    }
    
    /**
     * Translates the sent Jenga Route into a compatible Symfony Route     * 
     * @param array $jroutes
     */
    private function _translate(array $jroutes){
        
        //add default ajax Jenga routes for Symfony
        $this->_addAjaxRoutes('any_app_ajax','Jenga\App\Core\Ajax::processrows');
        
        //add the project acl compiler
        $this->__addCompiler('any_app_project_compile_action','Jenga\App\Project\Security\ACLCompiler');
        
        foreach($jroutes as $alias => $route ){
            
            $this->_currentalias = $alias;
            $this->resources->setCurrentRoute($this->_currentalias);
            
            //process the url
            $this->_url = $route['url'];
            $urilist[$alias] = $this->_url;
            
            //process the engine
            $engine = $this->_engine($route['engine']);
            
            //process the route defaults
            $defaults = $this->_getDefaults($route['defaults']);
            
            //process the panels in the secondary positions
            if(!is_null($route['panels']))
               $this->resources->setRoutePanel($route['panels'],$alias,'secondary');
            
            //if engine is null its assumed to be a pure resource route
            if(!is_null($route['resources']))
               $this->resources->setRouteResources($route['resources'], $this->_currentalias);
            
            if(!is_null($engine)){
                
                //process route defaults
                if(array_key_exists('defaults', $route)){

                    if(is_array($engine)){

                        $keys = array_keys($engine);
                        $lastkey = array_pop($keys);
                    }
                    else{

                        $engine = ['_controller' => $engine];
                        $lastkey = '_controller';
                    }
                    
                    Help::array_splice_assoc($engine, $lastkey, 0, $defaults);
                }
                else{
                    $engine = ['_controller' => $engine];
                }
                
                //process the template
                if(array_key_exists('template', $route)){
                    $engine['_template'] = $route['template'];
                }
                else{
                    $engine['_template'] = $this->_primaryTemplate();
                }
                
                //assign template to resources
                $this->resources->setRouteTemplate($engine['_template'], $alias);
                
                $regexes = array_key_exists('regex', $route) ? $route['regex'] : [];
                $options = [];
                $host = '';
                $schemes = [];
                $method = $this->_parseMethods($alias);
                
                $this->collector->add($alias, new SymfonyRoute($this->_url, $engine, $regexes, $options, $host, $schemes, $method));
                unset($regexes, $options, $host, $schemes, $method);
            }
            
            //schedule the events
            $this->eventscheduler->scheduleRouteEvents($route['event'], $alias);
        }
        
        //save route keys for later use
        App::set('_route_keys', array_keys($this->jroutes));
        App::set('_route_uris', $urilist);
    }
    
    private function _addAjaxRoutes($alias, $engine) {
        
        //add alias to route keys
        $this->jroutes[$alias] = $engine;
        
        if(is_string($engine)){
            $engine = ['_controller' => $engine];
        }
        
        $regexes = [];
        $options = [];
        $host = '';
        $schemes = [];
        $method = $this->_parseMethods($alias);
        
        $this->collector->add($alias, 
                new SymfonyRoute('/app/processrows', 
                        $engine, $regexes, $options, $host, $schemes, $method));
    }
    
    private function __addCompiler($alias, $engine){
        
        //add alias to route keys
        $this->jroutes[$alias] = $engine;
        
        if(is_string($engine)){
            
            $engine = ['_controller' => $engine];
            $engine['action'] = null;
        }
        
        $regexes = [];
        $options = [];
        $host = '';
        $schemes = [];
        $method = $this->_parseMethods($alias);
        
        $this->collector->add($alias, 
                new SymfonyRoute('/app/project/compile/{action}', 
                        $engine, $regexes, $options, $host, $schemes, $method));
    }
    
    /**
     * Processes the Jenga engine into viable Symfony parts
     * @param type $jengine
     */
    private function _engine($jengine) {
        
        if(strpos($jengine, '@') != false){
            
            $engineparts = explode('@', $jengine);
            $controller = Project::generateNamespacedClass($engineparts[0]);
            
            //check for route panel
            if(strpos($engineparts[1], ':') != false){
                
                $panel = explode(':', $engineparts[1]);
                $method = $panel[0];
                
                $this->resources->setMainPanel($controller,$method, $panel[1], $this->_currentalias);   
            }
            else{
                
                $method = $engineparts[1];
                
                //set the mainview panel
                $this->resources->setMainPanel($controller, $method, '', $this->_currentalias);
            }
            
            return $controller.'::'.$method;
        }
        elseif(strpos($jengine, DS) != false){
            
            //add static engine to route resources
            $this->resources->setStaticRouteEngine($jengine, $this->_currentalias);
            
            return [
                '_controller' => '_static', 
                '_template' => $jengine
            ];
        }
        else{
            
            //get the controller from the sent engine
            if(!is_null($jengine) && $jengine != '{default}'){
                
                $controller = Project::generateNamespacedClass($jengine);
                
                if($controller != 'not_found'){
                    
                    //set the mainview panel
                    $this->resources->setMainPanel($controller);
                    
                    return $controller.'::index';
                }
                else {
                    App::critical_error('MVC component:'.$jengine.' not found in the registered elements.');
                }
            }
            //{default}
            elseif($jengine == '{default}'){
                
                //look for {element} placeholder in the url
                if(strpos($this->_url, '{element}') == FALSE){
                    
                    //get the controller
                    $controller = Project::generateNamespacedClass($this->project->defaults['controller']);
                    
                    if($controller != 'not_found'){
                        
                        //get and set the main view panel
                        $this->resources->setMainPanel($controller,'index');

                        //return controller
                        return $controller.'::index';
                    }
                    else {
                        
                        App::critical_error('Jenga default element not found in the registered elements.');
                    }
                }
                else{
                    
                    /**
                     * This setting means that the controller for the route will be determined
                     * once the {element} placeholder has been resolved from the url
                     */
                    $controller = '_dynamic';
                    return $controller;
                }
            }
            else {
                return null;
            }
        }
    }
    
    /**
     * Processes the default entries set into the routes and thus making all the placeholders optional
     * @param type $entries
     */
    private function _getDefaults($entries = null) {
        
        //split the url
        $urlparts = explode('/', ltrim($this->_url,'/'));
            
        foreach($urlparts as $urlpart){
            
            //search for the placeholder brackets and get the variable name
            if(strpos($urlpart, '{') !== false){   
                
                $defaultname = Help::stripText('{', '}', $urlpart);
                
                //scan all the entries and replace empty values with null values
                if(!is_null($entries)){
                    
                    if(is_null($entries[$defaultname])){
                        $defaults[$defaultname] = null;
                    }
                    else{
                        $defaults[$defaultname] = $entries[$defaultname];
                    }
                }
                else{
                    
                    //set default to entry value
                    if(!is_null($defaultname))
                        $defaults[$defaultname] = null;
                }
            }
        }
        
        if(!is_null($defaults)){
            return $defaults;
        }
    }
    
    /**
     * Parses alias and returns request method
     * @param type $alias
     * @return type
     */
    private function _parseMethods($alias) {
        
        $method = strtoupper(explode('_', $alias)[0]);
        
        if($method == 'ANY')
            return ['GET','POST','PUT','DELETE','HEAD'];
        else
            return [$method];
    }
    
    /**
     * Returns the primary / default template
     * @return string
     */
    private function _primaryTemplate() {
        return $this->_templates['primary'][0];
    }

    /**
     * Returns the processed routes
     * @return type
     */
    public function returnRoutes(){        
        return $this->collector;
    }
}