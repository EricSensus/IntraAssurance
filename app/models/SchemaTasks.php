<?php
namespace Jenga\App\Models;

/**
 * This is for creating a unified fluent approach to performing tasks on the db table from the Jenga CLI
 * to be executed from the Jenga CLI
 */
use Jenga\App\Core\App;
use Jenga\App\Core\IoC;
use Jenga\App\Models\ORM;
use Jenga\App\Models\Schema;

use Jenga\MyProject\Config;

class SchemaTasks {

    protected static $table;
    protected static $config;
    public static $app;

    private static $_schedule;
    
    public static function __callStatic($name, $arguments) {
        
        self::_init();
        $orm = self::$_schedule['orm'];
        
        return \call_user_func_array([$orm->dbobject, $name], $arguments);
    }


    public static function shellInit(){
        
        //initiate and run the handlers
        self::$config = new Config();
        
        $ioc = new IoC(self::$config);        
        $ioc->registerHandlers();
        
        //register the IoC app shell into the application as the shell
        self::$app = $ioc->register();
    }
    
    /**
     * Loads the necessary schema classes
     */
    private static function _init(){
        
        self::shellInit();
        
        self::$_schedule['schema'] = self::$app->get(Schema::class);
        self::$_schedule['orm'] = self::$app->get(ORM::class);
    }
    
    /**
     * Returns the Jenga App Shell
     * 
     * @return type
     */
    public static function App(){
        self::_init();
        return self::$app;
    }
    
    /**
     * Creates table in assigned databse
     * 
     * @param type $name
     * @return type
     */
    public static function create($name){
        
        self::_init();
        self::$table = $name;
        
        return self::$_schedule['schema']->table($name);
    }
    
    /**
     * Adds a drop if exists clause to the table creation process
     * 
     * @param type $name
     * @return type
     */
    public static function dropAndCreate($name) {
        
        $create = self::create($name);
        return $create->dropIfExists();
    }
    
    /**
     * Inserts new rows into new table 
     * 
     * @param type $name
     * @param array $tableData
     */
    public static function insert($name, array $tableData){
        
        self::_init();
        $db = self::$_schedule['orm']->dbobject;
        
        foreach($tableData as $row){
            
            if(!is_array($row)){
                $db->insert($name, $tableData);
                break;
            }
            else{
                $db->insert($name, $row);
            }
        }
    }
    
    /**
     * Updates the set table 
     * 
     * @param type $table
     * @param array $data
     * @param array $conditions
     */
    public static function update($table, array $data, array $conditions){
        
        self::_init();
        
        $db = self::$_schedule['orm']->dbobject;
        
        if(is_array($conditions[1])){
            
            //check the end array item to see if condition has been specified
            $end = end($conditions);
            
            if($end[0] == 'AND' || $end[0] == 'and' 
                    || $end[0] == 'OR' || $end[0] == 'or'){
                $concat = strtoupper($end[0]);
            }
            else{
                $concat = '';
            }
            
            //unset if condition
            if($concat != ''){
                array_pop($conditions);
            }
            
            $count = 0;
            foreach ($conditions as $condition) {
                
                if(in_array($condition[1], self::$_schedule['orm']->operators)){
                    
                    if($concat == 'AND' || $concat == '' || $count == 0){
                        $db->where($condition[0],$condition[2],$condition[1]);
                    }
                    else{
                        $db->orWhere($condition[0],$condition[2],$condition[1]);
                    }
                }
                else{
                    
                    if($concat == 'AND' || $concat == '' || $count == 0){
                        $db->where($condition[0],$condition[1]);
                    }
                    else{
                        $db->orWhere($condition[0],$condition[1]);
                    }
                }
                
                $count++;
            }
        }
        else{
            
            if(in_array($conditions[1], self::$_schedule['orm']->operators))
                $db->where($conditions[0],$conditions[1],$conditions[2]);
            else
                $db->where($conditions[0],$conditions[1]);
        }
        
        return $db->update($table, $data);
    }
    
    /**
     * Delete the set table rows
     * 
     * @param type $table
     * @param array $conditions
     */
    public static function delete($table, array $conditions){
        
        self::_init();
        
        $db = self::$_schedule['orm']->dbobject;
        
        if(is_array($conditions[1])){
            
            //check the end array item to see if condition has been specified
            $end = end($conditions);
            
            if($end[0] == 'AND' || $end[0] == 'and' 
                    || $end[0] == 'OR' || $end[0] == 'or'){
                $concat = strtoupper($end[0]);
            }
            else{
                $concat = '';
            }
            
            //unset if condition
            if($concat != ''){
                array_pop($conditions);
            }
            
            $count = 0;
            foreach ($conditions as $condition) {
                
                if(in_array($condition[1], self::$_schedule['orm']->operators)){
                    
                    if($concat == 'AND' || $concat == '' || $count == 0){
                        $db->where($condition[0],$condition[2],$condition[1]);
                    }
                    else{
                        $db->orWhere($condition[0],$condition[2],$condition[1]);
                    }
                }
                else{
                    
                    if($concat == 'AND' || $concat == '' || $count == 0){
                        $db->where($condition[0],$condition[1]);
                    }
                    else{
                        $db->orWhere($condition[0],$condition[1]);
                    }
                }
                
                $count++;
            }
        }
        else{
            
            if(in_array($conditions[1], self::$_schedule['orm']->operators))
                $db->where($conditions[0],$conditions[1],$conditions[2]);
            else
                $db->where($conditions[0],$conditions[1]);
        }
        
        return $db->delete($table);
    }
    
    
    public static function schema(){
        
        self::_init();
        return self::$_schedule['schema'];
    }
}
