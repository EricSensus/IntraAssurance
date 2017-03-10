<?php
/**
 * This is where all the various events will be declared 
 * and subsequently executed by the system
 * 
 * Events Format - array( 'event_name' => array( 'event object, closure or value', 'priority', 'cycle_hook_point' ) );
 */

use Jenga\App\Views\Redirect;
use Jenga\MyProject\Users\Handlers\Gateway;

return [
        'auth.check' =>    
            [
                function(){
                    if(!Gateway::isLogged()){
                        Redirect::withNotice('Please login to view this section')->toDefault();
                    }
                    else{
                        return TRUE;
                    }
                 },10
            ]                
        ];
            