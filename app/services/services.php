<?php

use Jenga\App\Core\Ajax;
use Jenga\App\Core\Cache;
use Jenga\App\Request\Session;
use Jenga\App\Database\Mysqli\Database;
//use Jenga\App\Project\Security\Gateway;

use Jenga\MyProject\Users\Handlers\Gateway;

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
                'auth_source' => 'database', // or file - This configuration determines if the system access levels and policies are stored in a database or a flat file
                'auth_table' => 'accesslevels', //if database - define database table
                'auth_file' => ''
            ],
        'ajax' => [
                'class' => Ajax::class,
                'mode' => 'lazy'
            ]
    ]
];