<?php
namespace Jenga\App\Html;

use Jenga\App\Core\App;
use Jenga\App\Html\Form;
use Jenga\App\Html\Table;
use Jenga\App\Request\Facade\Sanitize;

class Generate{
    
    public static $sanitize;

    public function init( Sanitize $sanitize){
        self::$sanitize = $sanitize;
    }
    
    public static function Form($name, $schematic){
        
        App::call(  array(__NAMESPACE__.'\Generate' , 'init') );        
        return new Form($name, $schematic, self::$sanitize);
    }
    
    public static function Table($name, $schematic){
        
        App::call( array(__NAMESPACE__.'\Generate', 'init') );         
        return new Table($name, $schematic, self::$sanitize);
    }
}

