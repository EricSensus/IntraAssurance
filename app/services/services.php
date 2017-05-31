<?php

use Jenga\App\Core\Ajax;
use Jenga\App\Core\Cache;
use Jenga\App\Request\Session;
use Jenga\App\Database\Mysqli\Database;
use Jenga\MyProject\Users\Acl\Gateway;

use Jenga\MyProject\Elements;

/**
 * Register all the Application and Project services here
 */
return [
    'handlers' => [
        'database' => Database::class,
        'session' => [
                'class' => Session::class,
                'mode' => 'lazy'
            ],
        'cache' => [
                'class' => Cache::class,
                'mode' => 'lazy'
            ],
        'auth' => [
                'class' => Gateway::class,
                'mode' => 'lazy',
                'auth_source' => 'file', // or file - This configuration determines if the system access levels and policies are stored in a database or a flat file
                'auth_table' => '' //if database - define database table
            ],
        'ajax' => [
                'class' => Ajax::class,
                'mode' => 'lazy'
            ],
        'notice' => function(){
                        //insert the Notifications element
                        return Elements::call('Notifications/NotificationsController');
                     }
    ]
];