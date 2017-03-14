<?php
namespace Jenga\App\Views;

use Jenga\App\Core\App;

class HTML {
    
    public static $notifications;
    public static $tracker = [];
    public static $detect;
    public static $keywords = ['dataTables','overlay_modal','rowreorder'];

    /**
     * Registers resources to be loaded into the head section of the view
     * 
     * @param type $resource
     */
    public static function register($resource){
       
        $resources = array();
        
        if(App::$shell->has('current_page')){
            
            $resources = App::$shell->get('current_page');
            
            //add new entry
            if(is_string($resources)){
                $str = $resources;
                
                $resources = [];
                $resources[] = $str;
            }
            elseif(is_null($resources)){
                $resources = [];
            }
            
            array_push($resources, $resource);
        }
        else{
            
            $resources = $resource;
        }       
        
        App::$shell->set('current_page',$resources);
    }

    /**
     * Load the initial starting configurations for the full HTML view
     */
    public static function start(){
        
        //insert the notifications class
        self::$notifications = new Notifications();
        
        //initialize MobileDetect class
        self::$detect = new MobileDetect();
    }
    
    /**
     * Collects all the scripting components declared in all the 
     * panels and consolidates them in a single section
     */
    public static function end(){
        
        echo '<div class="preload" style="display:none">'
                . self::AddPreloader('center','50','50')
            . '</div>';
        
        if(count(self::$tracker)>='1'){
            
            foreach(self::$tracker as $component){
                
                if($component == 'tooltip'){
                    
                    //detect device
                    if(self::$detect->isMobile() || self::$detect->isTablet()){
                        
                        $options = '{ trigger: "click" }';
                    }
                    
                    //initialize all tooltips
                    $build .= "$('[data-toggle=\"tooltip\"]').tooltip(".$options."); ";
                    
                    //remove entry to avoid duplicate entries
                    $key = array_search('tooltip', self::$tracker);
                    unset(self::$tracker[$key]);
                }
                elseif($component == 'popover'){
                    
                    //detect device
                    if(!self::$detect->isMobile() && !self::$detect->isTablet() ){
                        
                        $options = '{ trigger: "hover" }';
                    }
                    
                    //initialize all popovers
                    $build .= "$('[data-toggle=\"popover\"]').popover(".$options."); ";
                    
                    //remove entry to avoid duplicate entries
                    $key = array_search('popover', self::$tracker);
                    unset(self::$tracker[$key]);
                }
            }
        }
        
        //check for dataTable        
        if(App::$shell->has('current_page')){

            $resource = App::$shell->get('current_page');

            if(is_string($resource)){

                $str = $resource;

                $resource = [];
                $resource[] = $str;
            }
            else{
                $resource = [];
            }
        }
        else{
            $resource = [];
        }

        if(in_array('dataTables', $resource)){

            //datatables scripts and css
            echo '<link rel="stylesheet" href="'. RELATIVE_APP_PATH .'/html/facade/DataTables/css/jquery.dataTables.min.css">
            <script src="'. RELATIVE_APP_PATH .'/html/facade/DataTables/js/jquery.dataTables.js"></script>';

            $script .= '//this is to prevent caching of remote bootstrap modals
                        $("table.dataTable").on("click","a[data-toggle=modal]",function(ev) {
                            ev.preventDefault();

                            $(".modal-body").html(\''.self::AddPreloader('center','50','50').'\');
                            var target = $(this).attr("href");

                            if(target != "#"){ 
                                var idtarget = $(this).attr("data-target");

                                // load the url and show modal on success
                                $(idtarget+" .modal-content").load(target, function() { 
                                    $("#"+idtarget).modal("show"); 
                                });
                            }
                        });';
        }

        if(in_array('overlay_modal', $resource)){

            $script .= '//this is to prevent caching of remote bootstrap modals
                $("body").on("click","a[data-toggle=modal]",function(ev){
                
                    ev.preventDefault();
                    var target = $(this).attr("href");

                    if(target != "#"){ 

                        var idtarget = $(this).attr("data-target");
                        $(".modal-body").html(\''.self::AddPreloader('center','50','50').'\');

                        // load the url and show modal on success
                        $(idtarget+" .modal-content").load(target, function() { 
                            $("#"+idtarget).modal("show"); 
                        });
                    }
                });';
        }

        if(in_array('rowreorder', $resource)){

            echo '<script src="'. RELATIVE_APP_PATH .'/html/facade/DataTables/plugins/rowReordering/jquery.dataTables.rowReordering.js"></script>';
        }

        if(count($resource) >= 1){
            
            self::script('$(function () {'
                    . $script
                    . $build
                    .'})');
        }
    }

    /**
     * Loads the referenced CSS file
     * 
     * @param type $csspath
     * @param type $removetemplatepath
     * @param type $inline_css
     */
    public static function css($csspath, $removetemplatepath = FALSE, $inline_css = FALSE){
        
        if($removetemplatepath == FALSE){            
            $tmp_path = TEMPLATE_URL;
        }
        
        if($inline_css == false){
            echo '<link href="'.$tmp_path.$csspath.'" rel="stylesheet" type="text/css" />';
        }
        else{
            
            $filepath = str_replace('/',DS, ABSOLUTE_PROJECT_PATH .DS. 'templates' .DS. $csspath);
            $filecontents = file_get_contents($filepath);
            
            echo '<style>'.$filecontents.'</style>';
        }
    }
    
    /**
     * Wraps the jquery script or file
     * 
     * @param type $jscript
     * @param type $type
     * @param type $return
     * 
     * @return string
     */
    public static function script($jscript, $type = '', $return = FALSE){
        
        if($type == '' || $type == 'script'){
            
            $script = '<script>'
            .$jscript
            . '</script>';
        }
        elseif($type == 'file'){
            
            $script = '<script src="'
            .$jscript
            . '"></script>';
        }
        
        if($return == FALSE){
            
            echo  $script;
        }
        else{
            
            return $script;
        }
    }
    
    /**
     * Function synonym for self::script() within the templates
     * 
     * @param type $script_path
     * @param type $return
     */
    public static function js($script_path, $return = FALSE) {
        self::script(TEMPLATE_URL.$script_path, 'file', $return);
    }


    /**
     * Load the HTML head section
     */
    public static function head($use_native_componenets = TRUE, $inline_css = FALSE){
        
        self::start();
        
        if($use_native_componenets){
            
            self::jQuery();  
            self::jQueryUI();
            self::bootstrap($inline_css);
        }
        
        //load the registered resources
        if(App::$shell->has('current_page')){
            
            $resources = App::$shell->get('current_page');
            
            if(is_string($resources)){
                $str = $resources;
                
                $resources = [];
                $resources[] = $str;
            }
            
            if(!is_null($resources)){
                
                foreach($resources as $resource){

                    if(!in_array($resource, self::$keywords))
                        echo html_entity_decode($resource);
                }
            }
        }   
        
        //load the notifications css and scripts
        //self::$notifications->init();
    }
    
    public static function notifications(){
        
        self::$notifications->display();
    }
    
    /**
     * Loads the jQuery main file
     */
    public static function jQuery(){        
        
        echo '<script src="'.RESOURCES .'/javascript/jquery/jquery-2.1.4.min.js"></script>';
        //echo '<script src="'.RESOURCES .'/javascript/jquery/jquery-2.2.4.js"></script>';
    }
    
    /**
     * Loads the jQueryUI main file
     */
    public static function jQueryUI(){        
        echo '<script src="'.RESOURCES .'/javascript/jquery-ui-1.11.4/jquery-ui.min.js"></script>';    
    }
    
    /**
     * Loads the bootstrap files
     */
    public static function bootstrap($inline_css = FALSE){
        
        if($inline_css == FALSE){
            echo '<link rel="stylesheet" href="'.RESOURCES .'/bootstrap/3.3.4/css/bootstrap.min.css">';
        }
        else{
            
            $filepath = str_replace('/',DS, ABSOLUTE_PATH .DS. 'app/resources/bootstrap/3.3.4/css/bootstrap.min.css');
            $filecontents = file_get_contents($filepath);
            
            echo '<style>'.$filecontents.'</style>';
        }
        
        echo '<script src="'.RESOURCES .'/bootstrap/3.3.4/js/bootstrap.min.js"></script>';
    }
    
    /**
     * Loads the fancyBox files
     */
    public static function fancyBox(){
        
        echo '<script src="'.RELATIVE_VIEWS.'/fancyBox/jquery.fancybox.js"></script>';
        echo '<link rel="stylesheet" href="'.RELATIVE_VIEWS.'/fancyBox/jquery.fancybox.css" type="text/css" media="screen" />';
    }
    
    /**
     * returns the default preloader image and HTML
     * 
     * @param type $width
     * @param type $height
     * @return type
     */
    public static function AddPreloader($align='center',$width = null, $height = null, $refreshmsg = TRUE, $loadertxt = null ){
        
        $loader = '<div class="showpreload" style="width:100%;text-align:'.$align.';opacity:0.5;">'
                    . '<img src="'.RELATIVE_APP_PATH .'/views/loading/loading.gif"'
                    . (!is_null($width) ? 'width="'.$width.'" ' : '') 
                    . (!is_null($width) ? 'height="'.$height.'" ' : '') 
                    .' />';
        
        if($refreshmsg == TRUE){
            $loader .= '<p style="font-size: small; color: grey">';
            
            if(is_null($loadertxt)){
                $loader .= 'Please refresh page if this is displayed for more than 15 seconds';
            }
            else{
                $loader .= $loadertxt;
            }
            
            $loader .= '</p>';
        }
        
        $loader .= '</div>';
        
        return $loader;
    }
    
    public static function shortenUrls($data) {
            
        $data = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', array(get_class(self), '_fetchTinyUrl'), $data);
        return $data;
    }

    public static function fetchTinyUrl($url) { 
        
        $ch = curl_init(); 
        
        $timeout = 5; 
        curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url[0]); 
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
        $data = curl_exec($ch); 
        curl_close($ch); 
            
        return '<a href="'.$data.'" target = "_blank" >'.$data.'</a>'; 
    }
    
    /**
     * Searches the sent haystack and returns the first found text inside the 
     * specified tags.
     * 
     * If recursive is set, it will return an array containing all the results
     * from searching the whole haystack
     * 
     * @param type $start_tag
     * @param type $end_tag
     * @param type $haystack
     * @param type $recursive
     * @return mixed
     */
    public static function findInTags($start_tag,$end_tag,$haystack,$recursive = FALSE){
        
        $startno = strlen($start_tag);
        $start_pos = strpos($haystack,$start_tag)+$startno;

        if ($start_pos === FALSE){
            return FALSE;
        }

        $end_pos = strpos($haystack,$end_tag);

        if ($end_pos === FALSE){
            return FALSE;
        }

        if($recursive === FALSE){
            
            return substr($haystack, $start_pos, ($end_pos-$start_pos));
        }
        else{
            
            $value[] = substr($haystack, $start_pos, ($end_pos-$start_pos));
            $strcount = substr_count($haystack, $start_tag);
            
            for($r=1; $r<=$strcount; $r++){

                //create new haystack
                $haystack = substr($haystack, ($end_pos+1));
                $strcount = substr_count($haystack, $start_tag);
                
                if($strcount >= '1'){

                    $value = array_merge($value, self::findInTags($start_tag, $end_tag, $haystack, TRUE));
                }
            }
            
            return $value;
        }
    }
    
    /**
     * Processes any sent attributes from the HTML
     * 
     * @param type $attr
     * @return string
     */
    private static function _parseAttributes($attr){
        
        foreach($attr as $attrname => $attrvalue){
            
            $attributes .= $attrname.'="'.$attrvalue.'" ';
        }
        
        return $attributes;
    }
    
    /**
     * Inserts the Bootstrap heading
     * 
     * @param type $content
     */
    public static function heading($type, $content, $attr = array()){
        
        if(count($attr) >= 1){
            
            $attrs = self::_parseAttributes($attr);
        }
        
        $heading = '<div class="panel-heading">';        
        $heading .= '<'.$type.' '.$attrs.'>'.$content.'</'.$type.'>';        
        $heading .= '</div>';
        
        return $heading;
    }
    
    /**
     * Prints current page
     */
    public static function printPage(){
        
        self::script('window.onload = function () {
                window.print();
            }');
    }
    
    /**
     * Returns simple table based on single ORM object properties
     * 
     * @param string $type either bootstrap or raw
     * @param object $contents_object
     * @param array $table_attrs Any attributes to be added to the table
     * 
     * @return html Fully rendered table
     */
    public static function simpleTable($type, $contents_object, $table_attrs = array()){
        
        if($type == 'raw'){
            
            if(count($table_attrs) >= 1){
            
                $attrs = self::_parseAttributes($table_attrs);
            }
        }
        elseif($type == 'bootstrap'){
            
            if(isset($table_attrs['class'])){
                
                $attrs = 'class="table '.$table_attrs['class'].'"';
                unset($table_attrs['class']);
            }
            
            if(count($table_attrs) >= 1){
            
                $attrs .= self::_parseAttributes($table_attrs);
            }
        }
        
        $table = '<table '.$attrs.'>';
        $table .= '<tbody>';
        
        if(!is_null($contents_object)){
            
            $properties = get_object_vars($contents_object);

            foreach($properties as $property => $value){

                if(is_null($value)){

                    $value = 'Not Specified';
                }

                $table .= '<tr>'
                        . '<td><strong>'.str_replace('_',' ',ucwords($property)).'</strong></td>'
                        . '<td>'.$value.'</td>'
                        . '</tr>';

            }

            $table .= '</tbody>'
                    . '</table>';
        }
        else{
            
            $table = Notifications::Alert('No data provided', 'info', TRUE, TRUE);
        }
        
        return $table;
    }
}