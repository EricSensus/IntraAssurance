<?php
namespace Jenga\App\Views;

use Jenga\App\Core\App;
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Core\Resources;

use Detection\MobileDetect;
use Symfony\Component\HttpFoundation\Response;

class View extends Project {
     
    public $basetemplate = 'index.php';
    
    public $mainpanel = null;
    
    public $panelsfolder = 'panels';
    public $panel;
    public $panelpath;
    private $_panelcfg = null;
    
    public $_ajax = FALSE;
    public $partials;

    public $variables = array();
    
    protected $_controller;
    protected $_action;
    protected $_route;
    
    public $config;
    public $tplengine;
    public $response;
    
    public $viewpoint;
    
    private $_templates;
    private $_templatefolder;
    private $_settings;
    
    private $_remote = null;
    private $_disableView = FALSE;
    private $_static = '';
     
    public function __construct(Resources $layout = null){
        
        $this->tplengine = $layout;
        $this->_templates = Project::getTemplates();   
        $this->response = new Response();
        
        //initialize MobileDetect class
        $this->viewpoint = new MobileDetect();
        App::set('viewpoint', $this->viewpoint);
        
        if($layout != null){
            
            $this->_templatefolder = $this->tplengine->returnRouteTemplate(); 
            
            //set base template
            if(strpos($this->_templatefolder, '/') != FALSE){
                
                $pathspl = explode('/', $this->_templatefolder);
                unset($pathspl[0]);
                
                $this->basetemplate = join(DS, $pathspl);
            }
            
            $this->_settings = $this->tplengine->returnRoutePanels();   
            
            //load only the main panel in the ajax request or if the template isnt declared
            if(App::has('_ajax_request') || is_null($this->_templatefolder)){
                $this->_settings['_ajax'] = TRUE;
            }
            
            $this->tplengine->registerResources();
        }
    }
    
    /**
     * Configure the view panel according to the sent settings from the template engine
     * 
     * @param type $panel
     * @param type $_ajax
     */
    public function configurePanelView($element, $panel, $_ajax){
        
        $this->_settings['_ajax'] = $_ajax;
        
        $mvc = $this->makeMvcEngine($element);
        $viewClass = end( explode( '\\', end( $mvc ) ) );
        
        $this->setPanelPath($element, $viewClass);
        
        $this->panel = $panel.'.php';        
    }
    
    /**
     * Sets the path for each view panel
     * 
     * @param type $element
     */
    public function setPanelPath($element, $class){
        
        $path = explode('/',$element['path']);
        $path = join(DS, $path);
        
        $viewfolder = $element['views'][$class]['folder'];        
        $this->panelpath = $path .DS. $viewfolder .DS. $this->panelsfolder;
    }
    
    /**
     * Sets the path for the Main view Panel
     * 
     * @param type $element
     */
    public function setViewPanelPath(){
        
        $elements = self::elements();
        $class = end( explode( '\\',get_class($this)));
        
        foreach($elements as $element){
            
            if(!is_null($element['views'])){
                
                if(array_key_exists($class, $element['views'])){
                    $select_element = $element;
                }
            }
        }
        
        $this->setPanelPath($select_element, $class);
    }

    /** 
    * Sets the file for every panel on a page
     * @param type $panel
     */
    public function setViewPanel($panel, $panelcfg = 'direct'){
        
        $this->setViewPanelPath();
        
        if(!is_null($panel)){
            
            /**
             * The principle here is direct configuration always trumps indirect
             */
            if($panelcfg == 'direct'){

                $this->mainpanel = $panel.'.php';
                $this->_panelcfg = $panelcfg;
            }
            elseif($panelcfg == 'indirect' && is_null($this->_panelcfg)){

                $this->mainpanel = $panel.'.php';
            }
        }
    }
    
    /**
     * Sets the primary panel for the current element
     */
    public function setMainPanel(){
       
        $primary = explode(':',$this->_settings['primary']['_main'])[1];
        $this->setViewPanel($primary, 'indirect');
    }
    
    /**
     * Get the primary template path and folder info
     */
    private function _primaryViewPath(){
        
        if(strpos($this->_templates['path'], '/')){            
            $path = explode('/',$this->_templates['path']);
            $path = join(DS, $path);
        }
        else {            
            $path = $this->_templates['path'];
        }
        
        //check for forward slash in template
        $pathspl = [];
        if(strpos($this->_templatefolder, '/') != FALSE){
            
            $pathspl = explode('/', $this->_templatefolder);
            
            $primaryfolder = $pathspl[0];
            unset($pathspl[0]);
        }
        else{
            $primaryfolder = $this->_templatefolder;
        }
        
        $newpath[0] = $path;
        $newpath[1] = $path. DS .$primaryfolder;
        
        return $newpath;
    }
    
    /**
     * Loads the view file based on settings sent
     * @param type $filename
     */
    public function loadTemplate($filename, $section='minor'){
        
        //extract the variables for use everywhere
        extract( $this->variables, EXTR_SKIP);
        
        if($section == 'minor'){
            
            if(!isset($this->_settings['_ajax'])){

                $pview = $this->_primaryViewPath();
                $tpl = $pview[1];
            }
            elseif($this->_settings['_ajax'] == TRUE){
                $tpl = $this->panelpath;
            }
        }
        elseif($section == 'main'){            
            $tpl = $this->panelpath;
        }
        
        if(file_exists(PROJECT_PATH .DS. $tpl .DS. $filename) && !is_null($filename)){
            require PROJECT_PATH .DS. $tpl .DS. $filename;
        }
        
        unset($this->mainpanel, $this->panel, $this->panelpath, $this->_panelcfg);
    }
    
    public function renderStaticPage($page){        
        $this->_static = $page;
    }
    
    /**
     * Wraps the content in the specified Symfony Response
     * @param type $content
     */
    public static function ajaxResponse($content){
        
        $response = new Response($content);
        return $response->getContent();
    }
    
    /**
     * Sets the template variables
     * 
     * @param type $name
     * @param type $value
     */
    public function set($name,$value) {
        
        $this->variables[$name] = $value;
    }
    
    /**
     * Gets any variables sent to the View
     * 
     * @param type $name
     * @return type
     */
    public function get($name){
        
        return $this->variables[$name];
    } 
    
    /**
     * Checks if the variable exists in the View variables list
     * @param type $paramname
     * @return type
     */
    public function has($paramname) {
        
        return array_key_exists($paramname, $this->variables);
    }
    
    /**
     * Loads the main panel of the system
     */
    public function loadMainPanel(){
       
        if($this->_static == ''){
            
            $this->setMainPanel();
            $this->loadTemplate($this->mainpanel,'main');
        }
        else{
            $this->response->setContent(require PROJECT_PATH .DS. $this->_static);
        }
    }
    
    /**
     * Assign positions into which panels will be loaded
     * 
     * @param type $position
     * @param type $panelargs
     */
    public function loadPanel($position, $panelargs = array()) {
        
        $settings = $this->_settings;
        
        if(array_key_exists('disablePanels', $settings)){
            
            if(is_array($settings['disablePanels']) == TRUE) {
                
                if(!in_array($position, $settings['disablePanels'])){
                    
                    parent::loadPanel($position, $panelargs);
                }
            }
        }
        else{
            
            parent::loadPanel($position, $panelargs);
        }        
    }
    
    /**
     * Duplicate of $this->loadPanel
     * 
     * @param type $position
     * @param type $panelargs
     */
    public function loadPanelPosition($position, $panelargs = array()) {
        
        $settings = $this->_settings;
        
        if(array_key_exists('disablePanels', $settings)){
            
            if(is_array($settings['disablePanels']) == TRUE) {
                
                if(!in_array($position, $settings['disablePanels'])){
                    
                    parent::loadPanel($position, $panelargs);
                }
            }
        }
        else{            
            parent::loadPanel($position, $panelargs);
        }        
    }
    
    /**
     * Checks if panel position is assigned and active
     * 
     * @param type $position
     * @return boolean
     */
    public function isPanelActive($position){
        
        if(!array_key_exists('disablePanels', $this->_settings)){
            
            if(array_key_exists($position, $this->_settings['secondary'])){                
                return TRUE;
            }
            else{                
                return FALSE;
            }
        }
        else{            
            return FALSE;
        }
    }
 
    /**
     * Allows for complete disabling of the view section
     */
    public function disable(){  
        $this->_disableView = TRUE;
    }
    
    /**
     * Restores the view section
     */
    public function enable(){       
        $this->_disableView = FALSE;
    }
    
    /** Display TemplateConfig **/     
    public function render() {
       
        if($this->_disableView === FALSE){

            if(isset($this->_settings['_ajax']) && $this->_settings['_ajax'] == TRUE){
                
                if(!is_null($this->mainpanel)){
                    $view = $this->mainpanel;
                }
                elseif(!is_null($this->panel)){
                    $view = $this->panel;
                }
                else{
                    $this->loadMainPanel();
                }
            }
            else{

                $view = $this->basetemplate;
            }
            
            $this->response->setContent( $this->loadTemplate( $view ) );
            
            return $this->response;
        }
    }
}
