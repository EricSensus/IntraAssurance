<?php
namespace Jenga\App\Views;

use Jenga\App\Request\Session;

class Notifications extends Overlays {
    
    public $basecss;
    public $basescript;
    public $attributes = '';
    
    //private $_types = ['tooltip','popover','alert'];
    private $_msglevels = ['notice','error','warning','success','confirm'];
    
    /**
     * Assigns a tooltip to every assigned HTML element
     * 
     * @param type $msg
     * @param type $attributes
     * @return type
     */
    public static function tooltip($msg, $attributes = array()){
        
        //add to tracker
        self::$tracker[] = 'tooltip';
        
        if(isset($attributes['data-toggle'])){
            
            unset($attributes['data-toggle']);
        }
        
        //process smg and attributes       
        return 'title="'.$msg.'" '.self::processAttributes($attributes).' data-toggle="tooltip" ';
    }
    
    /**
     * Assigns a popover to every assigned HTML element
     * 
     * @param type $msg
     * @param type $attributes
     * @return type
     */
    public static function popover($msg, $attributes = array()){
        
        //add to tracker
        self::$tracker[] = 'popover';
        
        if(isset($attributes['data-toggle'])){
            unset($attributes['data-toggle']);
        }
        
        //process smg and attributes       
        return 'data-content="'.$msg.'" '.self::processAttributes($attributes).' data-toggle="popover" ';
    }
    
    /**
     * Processes any sent attributes from the notifications
     * 
     * @param type $attr
     * @return string
     */
    private static function processAttributes($attr){
        
        foreach($attr as $attrname => $attrvalue){
            
            $attributes .= $attrname.'="'.$attrvalue.'" ';
        }
        
        return $attributes;
    }
    
    /**
     * Sets message into session for display
     * 
     * @param type $msglevel Message types: Notice, Success, Warning, Error
     * @param type $msg
     * @param type $attributes
     * @return none
     */
    public function setMessage($msglevel, $msg, $attributes = ''){
                
        $level = strtolower($msglevel);
        
        if(in_array($level, $this->_msglevels)){
            
            $session_key = 'message_'.$level;
            
            Session::flash($session_key, $msg);

            if($attributes == 'sticky'){

                Session::keep($session_key);
                $this->attributes = $attributes;
            }  
        }
    }
    
    /**
     * Clears all sticky notices
     * @param type $key
     */
    public static function clear($key = null){
        
        if(is_null($key)){
            
            $notices = Session::all();
            
            foreach($notices as $key => $notice){
                
                if(strpos($key, 'message_') === 0){
                    Session::delete($key);
                }
            }
        }
        else{
            Session::delete($key);
        }
    }
    
    /**
     * Configures the display based on the Session data
     * 
     * @return string the sent message
     */
    public function display(){
        
        if(Session::has('message_notice')){

            $message = '<div class="alert alert-info fade">';
            $strongmsg = 'Info: ';

            $msg = Session::get('message_notice');
            $type = 'info';
        }
        elseif(Session::has('message_success')){

            $message = '<div class="alert alert-success fade">';
            $strongmsg = 'Success: ';

            $msg = Session::get('message_success');
            $type = 'success';
        }
        elseif(Session::has('message_warning')){

            $message = '<div class="alert alert-warning fade">';
            $strongmsg = 'Warning: ';

            $msg = Session::get('message_warning');
            $type = NULL;
        }
        elseif(Session::has('message_error')){

            $message = '<div class="alert alert-error fade">';
            $strongmsg = 'Error: ';

            $msg = Session::get('message_error');
            $type = 'danger';
        }
        
        if(!self::$detect->isMobile() && isset($msg)){
            
            HTML::script(RELATIVE_VIEWS.'/notifications/notifications.js','file');
            HTML::script('$.bootstrapGrowl("<strong>'.$strongmsg.'</strong>'.$msg.'", {
            type: "'.$type.'",
            width: "auto",
            allow_dismiss: true
        });');
            
        }
        elseif(isset($msg)){
            
            $message .= '<a href="#" class="close" data-dismiss="alert">&times;</a>'
                            .'<strong>'.$strongmsg.'</strong>'.$msg
                        . "</div>";

            echo $message;
        }
    }
    
    /**
     * Generetaes a static alert 
     * 
     * @param type $msg
     * @param string $type alert type: info, notice, success, warning, error
     * @param boolean $return Whether the alert sould be echoed or returned
     * @param boolean $noclose Disables the close button
     * @return string
     */
    public static function Alert($msg, $type, $return = FALSE, $noclose = FALSE){
        
        if($type == 'info'){

            $message = '<div class="alert alert-info">';
            $strongmsg = 'Info: ';
        }
        elseif($type == 'notice'){

            $message = '<div class="alert alert-info">';
            $strongmsg = 'Info: ';
        }
        elseif($type == 'success'){

            $message = '<div class="alert alert-success">';
            $strongmsg = 'Success: ';
        }
        elseif($type == 'warning'){

            $message = '<div class="alert alert-warning">';
            $strongmsg = 'Warning: ';
        }
        elseif($type == 'error'){

            $message = '<div class="alert alert-error">';
            $strongmsg = 'Error: ';
        }
        
        if($noclose == FALSE){
            
            $message .= '<a href="#" class="close" data-dismiss="alert">&times;</a>';
        }
        
        $message .= '<strong>'.$strongmsg.'</strong>'.$msg
                    . "</div>";
        
        if($return == FALSE){
            
            echo $message;
        }
        else{
            
            return $message;
        }
    }
}