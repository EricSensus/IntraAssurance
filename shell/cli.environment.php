<?php
/**
 * Loads all the prerequisites for running the command line interface for Jenga
 */
define('LOAD_POINT',1);

define('DS', DIRECTORY_SEPARATOR);

//system root
define('ROOT', dirname(__DIR__));

//app root
define('APP', ROOT .DS. 'app');

//shell root
define('SHELL', ROOT .DS. 'shell');

//project path
define('PROJECT_PATH', ROOT .DS. 'project');

//absolute paths
define('ABSOLUTE_PATH',ROOT);
define('ABSOLUTE_PLUGIN_PATH', ROOT . DS . 'plugins');
define('ABSOLUTE_PROJECT_PATH', ROOT . DS . 'project');
define('CACHE_PATH', ROOT .DS. 'tmp' .DS. 'cache');

define('MODELS', APP .DS. 'models');
define('CONTROLLERS', APP .DS. 'controllers');
define('VIEWS', APP .DS. 'views');

define('APP_CORE', APP .DS. 'core');
define('HELPERS', APP .DS. 'helpers');
define('DATABASE', APP .DS. 'database');
define('SERVICES', APP .DS. 'services');
define('APP_PROJECT', APP .DS. 'project');
define('APP_HTML', APP .DS. 'html');
define('APP_PROJECT_CORE', APP .DS. 'project' .DS. 'core');
define('APP_BOOTSTRAP', APP .DS. 'bootstrap');

//load the class autoload shell file
if(file_exists(SHELL .DS. 'autoload.php'))
    require SHELL .DS. 'autoload.php';

//load the dependency injection  plugin for the system
if(file_exists(ABSOLUTE_PLUGIN_PATH . DS . 'autoload.php'))
    require ABSOLUTE_PLUGIN_PATH .DS. 'autoload.php';

// update this to the path to the "plugins/" directory, relative to this file
require_once __DIR__.'/../plugins/autoload.php';

//require the CLI main handler
require_once APP_CORE .DS. 'Cli.php';
