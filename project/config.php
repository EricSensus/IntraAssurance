<?php

namespace Jenga\MyProject;

class Config
{
    /*     * *** Project settings *********************** */

    public $project = 'Intrasurance';
    public $mailfrom = 'noreply@intrasurance.com';
    public $mailadmin = 'info@intrasurance.com';

    /*     * **** Database Settings ************** */
    public $db = 'intra';
    public $dbprefix = 'intra_';
    public $host = '127.0.0.1';
    public $username = 'root';
    public $password = 'root';
    public $port = '';

    /*     * **** Trusted Proxy IPs ************** */
    public $trustedips = ['127.0.0.1']; //add any other trusted IPs

    /******** Default Time Settings **********/
    public $timezone = "Europe/Berlin";

    /*     * **** Development Environment Settings ******* */
    public $development_environment = true;
    public $error_reporting = 'simple'; //options are: default | -1, none | 0, simple, maximum, development

    /*********** Error & Log Handling *******************/
    public $send_log_to = 'console'; //options are: file, console
    public $logpath = 'tmp' . DS . 'logs';
    public $log_to_console = true;

    /*     * **** Cache Settings ******************** */
    public $cache_files = true;

    /*     * *** User State Settings ****************** */
    public $session_storage_type = 'file'; //database for db session storage or file if you want to store the session in your computer as a flat file
    public $session_table = 'session_data'; /* Name of the MySQL table used by the class. NOTE: the table prefix will be added */
    public $session_lifetime = ''; /* (Optional) The number of seconds after which a session will be considered as expired. */
    public $lock_to_user_agent = true; /* (Optional) Whether to restrict the session to the same User Agent (or browser) as when the session was first opened. */
    public $lock_timeout = 1200; /* (Optional) The maximum amount of time (in seconds) for which a lock on the session data can be kept. Default is 60 */
    public $lock_to_ip = false; /* (Optional) Whether to restrict the session to the same IP as when the session was first opened. */

    /*     * **** Esurance Settings ********************** */
    public $policy_prefix = 'INTRA';
}
