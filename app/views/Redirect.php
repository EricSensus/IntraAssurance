<?php
namespace Jenga\App\Views;

use Jenga\App\Views\Notifications;

class Redirect{
    
    public static $notifications = '';

    public static function to($url = '/'){
                
        if(strpos(strtolower($url), 'http') === 0){           
            $url = $url;
        }
        else{
            $url = RELATIVE_ROOT.$url;
        }
        
        if (headers_sent()) {
		echo "<script>document.location.href='".RELATIVE_ROOT.$url."';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: ".$url );
	}
    }
    
    public static function toDefault(){
        
        self::to('/');
    }
    
    /**
     * Adds a message to the redirect
     * 
     * @param type $msglevel Message types: Notice, Success, Warning, Error
     * @param type $msg
     * @param type $attributes
     * 
     * @return none
     */
    public static function withNotice($notice, $type = 'notice'){
        
        if(self::$notifications == ''){            
            self::$notifications = new Notifications();
        }
        
        self::$notifications->setMessage($type, $notice);
        
        return new self;
    }
    
    /**
     * Adds a sticky message to the redirect
     * 
     * @param type $msglevel Message types: Notice, Success, Warning, Error
     * @param type $msg
     * @param type $attributes
     * 
     * @return none
     */
    public static function withStickyNotice($notice, $type = 'notice'){
        
        if(self::$notifications == '')            
            self::$notifications = new Notifications();
        
        self::$notifications->setMessage($type, $notice, 'sticky');
        
        return new self;
    }
}