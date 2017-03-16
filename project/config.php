<?php
namespace Jenga\MyProject;

class Config{
    
    /***** Project settings ************************/
    public $project = 'Intra Assurance Portal';
    public $mailfrom = 'noreply@esurance365.com';
    public $mailadmin = 'info@esurance365.com';
    
    /****** Database Settings ***************/
    public $db = 'intraassurance_db';
    public $dbprefix = 'itr_';
    public $host = 'localhost';
    public $username = 'root';
    public $password = '';
    public $port = '';
    
    /****** Trusted Proxy IPs ***************/
    public $trustedips = ['127.0.0.1']; //add any other trusted IPs
    
    /****** Development Environment Settings ********/
    public $development_environment = true;
    public $error_reporting = 'maximum'; //options are: default | -1, none | 0, simple, maximum, development
    
    /****** Esurance Settings ***********************/
    public $policy_prefix = 'ESU';
    
    /***** User State Settings *******************/
    public $session_storage_type = 'file'; //database for db session storage or file if you want to store the session in your computer as a flat file
    public $session_table = 'session_data'; /*Name of the MySQL table used by the class. NOTE: the table prefix will be added*/
    public $session_lifetime = ''; /*(Optional) The number of seconds after which a session will be considered as expired.*/    
    public $lock_to_user_agent = true; /*(Optional) Whether to restrict the session to the same User Agent (or browser) as when the session was first opened.*/
    public $lock_timeout = 1200; /*(Optional) The maximum amount of time (in seconds) for which a lock on the session data can be kept. Default is 60*/
    public $lock_to_ip = false; /*(Optional) Whether to restrict the session to the same IP as when the session was first opened.*/
}
