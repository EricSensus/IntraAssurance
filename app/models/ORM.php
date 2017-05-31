<?php
/**
 * ORM Class
 * 
 * This is a class which handles all Active Record ORM data manipulation used in App.
 * 
 * @category  Database Access
 * @package   Jenga
 * @author    Stanley Ngumo (revision) <jcampbell@ajillion.com>
 * @copyright Copyright (c) 2010
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @version   1.0
 * 
 */

namespace Jenga\App\Models;

use Jenga\App\Core\App;
use Jenga\App\Helpers\Help;
use Jenga\App\Request\Input;
use Jenga\App\Models\Schema;
use Jenga\App\Request\Session;
use Jenga\App\Models\PHPSQLParser;
use Jenga\App\Database\Mysqli\Database;

class ORM { //extends Mysqli\Database

    /**  
     * This is the container array which holds all the parameters to be sent to the DB
     * 
     * @param array
     */
    public $relations;
    public $fxnstatus = array();
    public $last_altered_row;
    public $errors = [];
    public $schema;

    /**
     * This is the main table name manipulated by the DB 
     * 
     * @var string 
     */
    protected $table;
    
    /**
     * This links to the database object on which all the database raleted operations will run on
     * 
     * @var object 
     */
    public $dbobject;
    
    /**
     * Static instance of self
     *
     * @var Database
     */
    protected static $_instance;
    
    /**
     * This is an additional second table which can be manipulated together with the main table in the model
     * 
     * @var string
     */
    protected $_secondary_table;
    
    /**
     * This is the primary key of the secondary table
     * 
     * @var type 
     */
    protected $_secondary_key;
    
    /**
     * This is the connecting table for two tables with a many-to-many relationship
     * 
     * @var string
     */
    protected $_pivot_table;
    
    /**
     * This is the first column of the first table in the pivot table
     * 
     * @var string
     */
    protected $_pivot_first;
    
    /**
     * This is the second column of the second table in the pivot table
     * 
     * @var string
     */
    protected $_pivot_second;
    
    /**
     * This value allows the deleted_at field to be set without actually deleting the affected row
     * 
     * @var boolean
     */
    protected $_allowrecycling;


    /**
     *This is the primary key of the main table
     * 
     * @var string 
     */
    public $primarykey;
    
    /**
     * This is the value for the primary key
     * 
     * @var array
     */
    public $primary_key_value = array();
    
    /**
     * This will only be used during processing of related tables
     * 
     * @var type 
     */
    public $_primary_table;
    
    /**
     *These are the column names of the selected table being used in the record
     * 
     * @var array 
     */
    public $tablecols;
    
    /**
     * This is the total number of rows in primary table
     * 
     * @var int
     */
    public $totalcount;
    
    /**
     * This is the format which will be used to present the retrieved database results
     * 
     * @var string
     */
    protected $_output_format = 'object';

    /**
     * The variable shows other tables which are linked to the main db table
     *
     * @var mixed 
     * @example /App/core/ORM.mdl.php 
     * 
     * $_relatedtables =  array('student_courses','student_tests_assignments',
     * 'students_courses_prices','students_documents');
     * 
     * $_relatedtables = 'one_table';
     */
    protected $_relatedtables;
    
    /**
     * This variable holds the attributes checked in related table configuration
     * 
     * @var array
     */
    protected $_relatedattributes = ['type','local','foreign','on_update','on_delete'];
    
    /**
     *This shows the mapping of columns from the primary table to the related tables
     * 
     * @uses array <pre><code>
     *  $_relationship = array(
     *        'students_courses' = array(
     * 
     *           'foreign' => array('students_id' => students_id value),
     * 
     *           #foreign columns in relation table, format array('foreign table column')
     * 
     *           'on_update' => 'update', //or dont set
     * 
     *           #when a primary table row is updated, the corresponding table row will be updated auotmatically, if not set, the relation row isnt updated
     * 
     *           'on_delete' => 'delete' //or dont set
     * 
     *           #when the primary table row is deleted, the corresponding table row will be deleted automatically    
     * 
     *       )
     *  );
     * </pre></code>
     * 
     * @var array 
     * 
     * @example /App/core/ORM.mdl.php 
     * 
     */
    protected $_relationship = array();
    
    /**
     * This is an array which will hold the columns & data for the related tables identified by the table name as the array key
     * 
     * <pre><code>
     * array(
     *  'student_courses' => array()
     * );
     * </pre></code>
     * 
     * @var array
     */
    protected $_relateddata = array();

    /**
     * This is to allow for the related tables to be processed with all the other functions used in the class
     * 
     * @var boolean 
     */
    protected $_relationmode = FALSE;
    
    /**
     * This is when a main table has been set proxyly for only limited use. To prevent subsequent operations
     * from being tampered by the change
     */
    protected $_proxymode = FALSE;


    /**
     * This is the data from the table or the user which will be used throughout  the users operations
     * 
     * @var array $data
     * 
     * @var Array
     */
    public $data = array();
    
    /**
     * This is the replacement for data, this object which will be used when the ORM is in repository mode
     * @var object 
     */
    public $entity = null;
    
    /**
     * This is a booloean indicator which is set just in case the user wants to bypass the rendering of the
     * database results into the entity object
     * 
     * @var boolean
     */
    private $_bypassentity = FALSE;
    
    /**
     * This variable just allows a query to be built by subsequent funtions in the DB
     * 
     * @var string 
     * @var array
     */
    protected $_dbquery = array();
    
    /**
     * This is used to hold the selected column for the query
     * 
     * @var string
     */
    
    protected $_selectcolumn;
    
    /**
     * This is used to hold the selected id for the query
     * 
     * @var string
     */
    
    protected $_selectid;
    
    /**
     * This array holds the various operators used in Where mysql statements
     * 
     * @var array
     */
    public $operators = array('BETWEEN','NOT BETWEEN','LIKE','NOT LIKE','IN','NOT IN','IS NOT','IS NOT NULL',
                            '<','<=','=','!=',':=','^','|','<=>','->','>=','>');
    
    
    function __construct(Database $database, Schema $schema) {
        
        $this->dbobject = $database;
        $this->schema = $schema;
        
        //assign the table
        $this->table($this->table, 'NATIVE');
        
        //assign entity into ORM if repository mode is set
        if(method_exists($this, 'register')){
            App::call([$this, 'register']);
        }
    }
    
    /**
     * Handles the processing of related tables
     * 
     * @param type $method
     * @param type $args
     * @return \ORM
     */
    public function __call($method, $args){
        
        if(!is_null($this->_relatedtables) && in_array($method, $this->_relatedtables)){
            
            //switch the main table and the secondary table with the assigned table name and the method / table name
            $this->_secondary_table = $method;            
            $this->_relationmode = TRUE;
            
            $id = $this->_selectid;
            
            $rship = $this->_relationship[$this->_secondary_table];
            
            $this->where(TABLE_PREFIX.$this->_secondary_table.'.'.$rship['foreign'], $id);            
            
            if(isset($this->_relationship[$this->_secondary_table])){
                
                $reltable = $this->_relationship[$this->_secondary_table];
                
                //define the many-to-many relationship
                if($reltable['type'] == strtolower('many-to-many')){
                    
                    if(isset($reltable['pivotTable'])){
                        $ptable = $reltable['pivotTable'];
                        
                        if(is_array($ptable)){
                            if($this->_checkTable($ptable)){
                                
                                $this->_pivot_table = $ptable[0];
                                $this->_pivot_first = $ptable[1];
                                $this->_pivot_second = $ptable[2];
                                
                            }
                        }
                        else{
                            if($this->_checkTable($ptable)){
                                $this->_pivot_table = $ptable;
                                
                                $rtables = explode('_', $this->_pivot_table);
                                $this->_pivot_first = $this->_primary_key($rtables[0]);
                                $this->_pivot_second = $this->_primary_key($rtables[1]);
                                
                            }
                            else{
                                
                                App::critical_error('The pivot table ('.$ptable.') is undefined');
                            }
                        }
                    }
                    else{
                        
                        //automatically arrange tables alphabetically according to Jenga convention
                        $tables = array($this->table, $this->_secondary_table);
                        asort($tables);
                        
                        //join the tables 
                        $pivot = join('_',$tables);
                        
                        $this->_pivot_first = $this->_primary_key($rtables[0]);
                        $this->_pivot_second = $this->_primary_key($rtables[1]);
                        
                        if($this->_checkTable($pivot)){
                            $this->_pivot_table = $pivot;
                        }
                    }
                }
            }
            else{
                App::critical_error('The '.$method.' table relationship is undefined');
            }
        }
        elseif(!is_null($this->_relatedtables)){
            App::critical_error('The '.$method.' method is undefined in '. get_class($this));
        }
        
        return $this;      
    }
    
    /**
     * Returns static instance of ORM
     * 
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public static function __callStatic($name, $arguments) {
        
        self::$_instance = App::get(__CLASS__);        
        return \call_user_func_array([self::$_instance->dbobject, $name], $arguments);
    }
    
    /**
     * It resets the ORM to the initally set values typically after an operation involving related tables
     * 
     * @return \ORM
     */
    protected function _setToDefault($variablename = 'table'){
        
        foreach(get_class_vars(get_class($this)) as $name => $default){    
            if($name == $variablename){
                $this->$name = $default;
            }
        }
    }
    
    /**
     * This function checks if the sent table exists
     * 
     * @param string $table
     * @return boolean TRUE or FALSE
     */
    function _checkTable($table){
        
        if(!is_null($table)){
            
            $this->dbobject->rawQuery("SHOW TABLES LIKE '".  $this->dbobject->prefix.$table."'");

            if($this->dbobject->count >= 1){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
    }
    
    /**
     * This function assigns each of the column in the assigned table 
     * to the object as object properties
     * 
     * @return NONE
     */    
    function _assignColumnsToObject(){
        
        //$this->_resetTableCols();
        
        $this->table_name = $this->table;        
        $this->tablecols = $this->dbobject->rawQuery("SHOW COLUMNS FROM ".  $this->dbobject->prefix . $this->table);
        
        foreach ($this->tablecols as $column) {
            
            if(strpos($column['Type'],'int') == '0' ){
                $this->$column['Field'] = '';
            }
            else {
                $this->$column['Field'] = 0;
            }
        }
    }
    
    /**
     * Removes any table columns which may have been previously set
     */
    private function _resetTableCols(){
        
        if(!is_null($this->tablecols) && is_array($this->tablecols)){
            
            foreach($this->tablecols as $column){
                
                unset($this->$column['Field']);
                unset($this->data->$column['Field']);
            }
        }
    }
    
    /**
     * Create an empty object for holding data
     * 
     * @return \stdClass
     */
    public function createEmpty(){
        
        return new \stdClass();
    }
    
    public function ping() {
        return $this->dbobject->ping();
    }
    
     /**  
     * This function assigns the table to be subsequently manipulated in this ORM class
     * @param string $dbtable This is the table to be used to create the active record
     * @param string $mode NATIVE - all subsequent operations will use this table or
     *                     PROXY - used only once for a single operation
     * @return object the instantiated Record Object
     */        
    public function table($dbtable, $mode = 'PROXY') { 
        
        //check for sent prefix
        if(strpos($dbtable, $this->dbobject->prefix) !== FALSE){            
            $dbtable = str_replace($this->dbobject->prefix, '', $dbtable);
        }
        
        if($this->_checkTable($dbtable) == TRUE){
            
            if($mode == 'PROXY'){
                
                $this->_proxymode = TRUE;
                $this->_secondary_table = $this->table;
                
                //create an ORM instance
                $orm = App::make('Jenga\App\Models\ORM');
                $this->{$dbtable} = $orm->table($dbtable, 'NATIVE');
                
                $this->dbobject->reset();
                
                return $this->{$dbtable};
            }
            
            $this->table = $dbtable;            
            
            //assign the table columns to the instantiated object
            $this->_assignColumnsToObject();
            
            //assign the primary key
            $this->_set_primary_key();
        }
        
        return $this;
    }
    
    protected function _primary_key($table){
        
        if($table == null){
            $ctable = $this->table;
        }
        else{
            $ctable = $table;
        }
        
        $sql = "SHOW INDEX FROM ".$this->dbobject->prefix.$ctable." WHERE Key_name = ?";
        
        $gp = $this->dbobject->rawQuery($sql, array('PRIMARY'));
        $cgp = $this->dbobject->count($gp);
        
        if($cgp){
            
            return $gp[0]['Column_name'];
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     * This sets the primary key name into the $primarykey variable
     * 
     * @param string $table
     * @return Boolean False if not set
     */
    protected function _set_primary_key(){
        
        $this->primarykey = $this->_primary_key($this->table);
        $this->primary_key_value[$this->primarykey] = '0';
    }
    
    /*
     * This function reurns the set primary key value
     * 
     * @return string The primary key
     */
    public function get_primary_key(){        
        return $this->primarykey;
    }


    /**
     * This function changes the relations tables on event of an insert or update action
     * 
     * @return array $status variable for each of the operations on each table 
     */
    protected function _processRelations($action){
        
        //check for related tables
        if(isset($this->_relatedtables))
        {
            $this->_primary_table = $this->table;
            
            $count = 0;
            foreach($this->_relatedtables as $dtable){
                
                //set a tracker which should save all the data from the actions
                $tracker = array();
                $dtracker = $tracker[$dtable];
                
                if(isset($this->_relationship[$dtable]['on_update']) 
                        && $this->_relationship[$dtable]['on_update']=='update'){
                    
                    if($action == 'insert')
                    {
                        $dins = $this->dbobject->insert($dtable, $this->_relateddata[$dtable]);
                        
                        if(is_int($dins))
                        {
                            $dcolumn = $this->_get_primary_key($dtable);
                            $dtracker[$dcolumn] = $dins;
                        }
                    }
                    elseif($action == 'update')
                    {
                        $foreignColumn = array_keys($this->_relationship[$dtable]['foreign']);
                        $foreignValue = $this->_relationship[$dtable]['foreign'][$foreignColumn];
                        
                        $this->where($foreignColumn,$foreignValue);
                        
                        if($this->dbobject->update($dtable,$this->_relateddata[$dtable])==TRUE)
                        {
                            $dtracker[$foreignColumn] = $foreignValue;
                        }
                        else
                        {
                            $dtracker['ERROR'] = $this->getLastError();
                        }
                    }
                }
                elseif(isset($this->_relationship[$dtable]['on_delete']) 
                        && $this->_relationship[$dtable]['on_delete'] == 'delete'){            
                    
                    if($count == 0){
                        
                        $wheres = $this->_dbquery['where'];
                        $count++;
                    }
                    
                    foreach($wheres as $where){
                        
                        if(is_array($where[1])){
                            
                            $rowvalue = array_values($where[1]);
                        }
                        else {

                            $rowvalue = $where[1];
                        }
                    }
                    
                    $localcolumn = $this->_relationship[$dtable]['local']; 
                    $foreigncolumn = $this->_relationship[$dtable]['foreign'];  
                    
                    //set related deletion settings
                    $this->_relationmode = TRUE;
                    $this->table = $dtable;
                    
                    $this->find($rowvalue[0], $localcolumn)->$dtable()->where($foreigncolumn,'=',$rowvalue[0])->delete();                    
                }
            }
            
            if($this->_relationmode == TRUE){
                
                unset($this->dbobject->_where);
                
                //restore the where conditions for the original delete action
                $this->_relationmode = FALSE;                
                $this->dbobject->_where = $wheres;                
                $this->table = $this->_primary_table;
            }
            
            //set the status
            return $dtracker;
        }
    }
    
    /**
     * Add the last processed query into a session
     */
    public function store(){
        
        if(Session::has('stored_query'))
            Session::delete('stored_query');    
        
        //throw new query into session
        Session::add('stored_query', Help::encrypt($this->dbobject->getLastQuery()));
    }
    
    /**
     * Parses last sent query into an array of its individual sections
     */
    public function parseStoredQuery(){
        
        $query = Help::decrypt(Session::get('stored_query'));
        
        if($query != false || $query !=''){
            
            $parser = new PHPSQLParser();
            
            return $parser->parse($query, true);
        }
    }
    
    /**
     * Processes the search query for previous search and insert into the where condition
     */
    public function processStoredQuery(){
        
        if(Session::has('stored_query')){
            
            $query = $this->parseStoredQuery();
            
            if(is_array($query)){
                
                //check for SELECT in mysql query
                if(array_key_exists('SELECT', $query)){
                    
                    $selects = $query['SELECT'];
                    foreach($selects as $select){
                        
                        $column = $select['base_expr'];
                        
                        if(array_key_exists('alias', $select) ){ 
                            
                            if(!is_bool($select['alias']) && array_key_exists('as', $select['alias'])){
                                $column .= ' '.$select['alias']['base_expr'];
                            }
                        }
                        
                        $selectlist .= $column.', ';
                    }
                    
                    $columns = rtrim($selectlist, ', ');
                    
                    if(!is_null(Input::post('export'))||!is_null(Input::post('printer'))){
                        
                        $this->select($columns);
                    }
                }
                
                //check for JOIN in mysql query
                if(array_key_exists('FROM', $query)){
                    
                    $froms = $query['FROM'];
                    
                    if(count($froms) > 1){
                        
                        for($r=0; $r<=(count($froms)-1); $r++){
                            
                            if($r >= 1){
                                
                                if($froms[$r]['join_type'] == 'JOIN'){

                                    if(!is_null(Input::post('export'))||!is_null(Input::post('printer'))){
                                        
                                        $this->join(str_replace(TABLE_PREFIX,'', $froms[$r]['table']), 
                                            $froms[$r]['ref_clause'][0]['base_expr'].' '.$froms[$r]['ref_clause'][1]['base_expr'].' '.$froms[$r]['ref_clause'][2]['base_expr']);
                                    }
                                }
                            }
                        }
                    }
                }
                
                //check for WHERE in mysql query
                if(array_key_exists('WHERE', $query)){
                    
                    $wheres = $query['WHERE'];
                    foreach($wheres as $where){

                        $condition[] = $where['base_expr'];
                    }
                }
                
                if(!is_null(Input::post('export'))||!is_null(Input::post('printer'))){
                    
                    $this->where($condition[0],$condition[1],$condition[2]);
                    Session::delete('stored_query');
                }
            }
            else{
                
                Session::delete('stored_query');
            }
        }
    }

    /**
     * This function assigns the columns and aliases to the $_dbquery variable
     * 
     * @param mixed $columns
     */
    function select($columns = '*'){
        
        if($columns != '*' && !is_array($columns)){
            $this->_dbquery['select'] .= $columns.',';
        }
        elseif(is_array($columns)){
            $this->_dbquery['select'] .= join(',', $columns);
        }
        else {
            $this->_dbquery['select'] .= $columns;
        }
        
        return $this;
    }
    
    /**
     * This is a CONVENIENT overwriting function from the mysqli.db file  to create more fluency in writing commands
     * 
     * @example $dbrecord->where('id', 7)->orWhere('title', 'MyTitle')->show();
     * 
     * @param type $whereProp Name of the column(s)
     * @param type $operator Set to AND by default, allows for BETWEEN / NOT BETWEEN, IN / NOT IN, <=> (and its other combinations) <br/> NOTE: this can be left blank, if none is provided, as in example below
     * @param type $whereValue Value of the column(s)
     * 
     */
    public function where($whereProp, $operator = '' , $whereValue = null){
        
        //if($operator != ''){
        
            if(is_string($operator)&&array_search($operator, $this->operators))
                $operator = strtoupper ($operator);
            
            //compare the operator with the set operators
            if(array_search($operator, $this->operators) === FALSE){ 
                
                //assign the operator value to be the whereValue
                $whereValue = $operator;
                $this->_dbquery['where'] = $this->dbobject->where($whereProp, $whereValue);                
            }
            else{
                
                $this->_dbquery['where'] = $this->dbobject->where($whereProp, $whereValue, $operator); 
            }
        //}
        
        $this->fxnstatus['last_function'] = __FUNCTION__;
        return $this;
    }
    
    /**
     * This is a CONVENIENT overwriting function from the mysqli.db file  to create more fluency in writing commands
     * 
     * @uses $MySqliDb->where('id', 7)->orWhere('title', 'MyTitle')->run();
     * 
     * @param type $whereProp
     * @param type $operator this is as explained above
     * @param type $whereValue
     * 
     */
    function orWhere($whereProp, $operator = '', $whereValue = null ) {

        //compare the operator with the set operators       
        if(is_string($operator)&&array_search($operator, $this->operators))
            $operator = strtoupper ($operator);

        if(array_search($operator, $this->operators)== FALSE){

            //assign the operator value to be the whereValue
            $whereValue = $operator;
            $this->dbobject->orWhere($whereProp, $whereValue);
        }
        else{ 
            $this->dbobject->orWhere($whereProp, $whereValue, $operator);
        }

        $this->fxnstatus['last_function'] = __FUNCTION__;
        
        return $this;
    }
    
    /**
     * This method allows you to concatenate joins for the final SQL statement.
     *
     * @uses $MySqliDb->join('table1', 'field1 <> field2', 'LEFT')
     *
     * @param string $joinTable The name of the table.
     * @param string $joinCondition the condition.<br/> NOTE: please add the TABLE_PREFIX constant to any tables used in the join condition.
     * @param string $joinType 'LEFT', 'INNER' etc.
     *
     * @return Database
     */
    public function join($joinTable, $joinCondition, $joinType = '') {
        
        $this->dbobject->join($joinTable, $joinCondition, $joinType);
        
        return $this;
    }
    
    /**
     * This method allows you to specify multiple (method chaining optional) 
     * GROUP BY statements for SQL queries.
     *
     * @uses $MySqliDb->groupBy('name');     *
     * @param string $groupByField The name of the database field.
     *
     * @return Database
     */
    public function groupBy($groupByField){
        
        $this->dbobject->groupBy($groupByField);
        
        return $this;
    }
    
    /**
     * This method allows you to specify multiple (method chaining optional) ORDER BY statements for SQL queries.
     *
     * @uses $MySqliDb->orderBy('id', 'desc')->orderBy('name', 'desc');
     *
     * @param string $orderByField The name of the database field.
     * @param string $orderByDirection Order direction.
     *
     * @return Database
     */
    public function orderBy($orderByField, $orderbyDirection = "DESC"){
        
        $this->dbobject->orderBy($orderByField, $orderbyDirection);
        
        return $this;
    }

    /**
     * This is a CONVENIENT function to just retrieve the rows linked to the set table
     * 
     * @param string $numRows Number of rows you want inserted into result
     * @param mixed $column is a string  if only one column is given and an array if there are multiple columns
     * 
     * @return object table column rows
     */
    public function show($numRows = null, $column = '*'){
        
        //check the search settings just in case the records are search results
        $this->processStoredQuery();
        
        //check the selected columns
        if(isset($this->_dbquery['select']) && $column == '*'){
            $column = rtrim($this->_dbquery['select'], ',');
        }
        
        if($this->_relationmode){
            
            ///check the relations and set the Where section
            $type = $this->_checkRelations();            
            $this->table = $this->_secondary_table;
            
            if($type == 'one-to-one'){
                
                $numRows = 1;
                
                $data = $this->dbobject->get($this->table, $numRows, $column);
                $this->data = $data[0];
                
            }
            elseif($type == 'one-to-many'){
                
                //the numRows is already set to NULL 
                $this->data = $this->dbobject->get($this->table, $numRows, $column);
            }
        }
        else{
            
            $this->data = $this->dbobject->get($this->table, $numRows, $column);
        }
       
        //reset states
        $this->_ormReset();
        
       if($this->_relationmode){
           
           //reset the db table setting
            $this->_setToDefault();
            $this->_relationmode = FALSE;           
       }
       
       //check for proxy setting and restore main table
       $this->_restoreMainTable();
       $this->fxnstatus['last_function'] = __FUNCTION__;     
        
       return $this->formatOutput($this->data);
    }
    
    /**
     * Duplicate of show() without the rows or columns arguments
     * @return type
     */
    public function all(){
        return $this->show();
    }
    
    /**
     * Function synonym for show()
     */
    public function get($numRows = null, $column = '*'){
        return $this->show($numRows, $column);
    }
    
    /**
     * Clears the states after an execution
     */
    protected function _ormReset(){
        
        if(isset($this->_dbquery['select'])){
            unset($this->_dbquery['select']);
        }
        
        //if(isset($this->_dbquery['where'])){
            //unset($this->_dbquery['where']);
        //}
    }
    
    /**
     * This calculates pagination details based on sent variables
     * 
     * @param type $rows_per_page
     * @param type $page_num
     * @param boolean $from_last_query Process total rows from last query //default TRUE
     * @return type
     */
    public function paginate( $rows_per_page, $page_num='1', $from_last_query = FALSE){
        
        $total_rows = $this->getTotalCount($from_last_query);
        
        $arr = array();
        
        // calculate last page
        $last_page = @ceil($total_rows / $rows_per_page);
        
        // make sure we are within limits
        $page_num = (int) $page_num;
        if ($page_num < 1)
        {
           $page_num = 1;
        } 
        elseif ($page_num > $last_page)
        {
           $page_num = $last_page;
        }
        $upto = ($page_num - 1) * $rows_per_page;

        if($upto <= 0)
        {
                $upto = 0;
        }

        $arr['limit'] = 'LIMIT '.$upto.',' .$rows_per_page;
        $arr['offset'] = $upto;
        $arr['current'] = $page_num;
        if ($page_num == 1)
                $arr['previous'] = $page_num;
        else
                $arr['previous'] = $page_num - 1;
        if ($page_num == $last_page)
                $arr['next'] = $last_page;
        else
                $arr['next'] = $page_num + 1;
        $arr['last'] = $last_page;
        $arr['info'] = 'Page ('.$page_num.' of '.$last_page.')';
        $arr['pages'] = self::getOuterPages($page_num, $last_page, $arr['next'],$last_page);
        
        return $arr;
    }
    
    public static function getOuterPages($page_num, $last_page, $next, $show=100){
        
        $arr = array(); // how many boxes
        // at first
        if ($page_num == 1)
        {
            // case of 1 page only
            if ($next == $page_num) return array(1);
            for ($i = 0; $i < $show; $i++)
            {
                if ($i == $last_page) break;
                array_push($arr, $i + 1);
            }
            return $arr;
        }
        // at last
        if ($page_num == $last_page)
        {
            $start = $last_page - $show;
            if ($start < 1) $start = 0;
            for ($i = $start; $i < $last_page; $i++)
            {
                array_push($arr, $i + 1);
            }
            return $arr;
        }
        // at middle
        $start = $page_num - $show;
        if ($start < 1) $start = 0;
        for ($i = $start; $i < $page_num; $i++)
        {
            array_push($arr, $i + 1);
        }
        for ($i = ($page_num + 1); $i < ($page_num + $show); $i++)
        {
            if ($i == ($last_page + 1)) break;
            array_push($arr, $i);
        }
        return $arr;
    }
    
    /**
     * Overwrites the previous stored query with the latest one
     */
    private function _resetStoredQuery(){
        
        //reset the last query
        if(Session::has('stored_query')){
            
            $early_query = Help::decrypt(Session::has('stored_query'));
            $last_query = $this->dbobject->getLastQuery();
            
            if(strcmp($early_query, $last_query)!=0){
                
                //overwrite the earlier query
                Session::add('stored_query', Help::encrypt($last_query));
            }
        }
    }
    
    /**
     * Method returns last executed query
     *
     * @return string
     */
    public function getLastQuery () {
        return $this->dbobject->getLastQuery();
    }

    /**
     * Method returns mysql error
     * 
     * @return string
     */
    public function getLastError () {
        return $this->dbobject->getLastError();
    }
    
    /**
     * Check if the conditions set in the where statement have gotten any results. If found, it will also assign the first row id to the primary key value array
     * 
     * @return boolean
     */
    public function exists(){
        
        $found_row = $this->dbobject->get($this->table, 1, '*');
        
        $pkey = $this->primarykey;
        if($this->count() >= '1'){ 
            
            //set the found row's primary key and value in case the save function is used later            
            $this->primary_key_value[$pkey] = $found_row[0][$pkey];
            
            //ANGALIA Redo this section to make sure that the retrieved row is inserted correctly into the data variable
            
            //$this->data = $found_row; 
            
            $exist_result = $found_row; 
        }
        else{
            //unset so as to clear for new record insertion
            unset($this->primary_key_value[$pkey]);
            
            $exist_result = FALSE;
        }
        
        $this->fxnstatus['last_function'] = __FUNCTION__;
        return $exist_result;
    }
    
    /**
     * Returns ORM operation errors
     * @return array
     */
    public function errors() {
        return $this->errors;
    }
    
    /**
     * This function loads the first row returned for any result
     * 
     * @param string $column
     * @return object The instatiated ORM instance
     * 
     */
    public function first($column = '*') {
        
        $this->data = $this->show(1,$column);      
        $this->fxnstatus['last_function'] = __FUNCTION__;
        
        if(is_array($this->data)){
            return $this->formatOutput($this->data[0]);
        }
        else{
            return $this->formatOutput($this->data);
        }
    }

    /**
     * This function to set the distinct variable
     * 
     * @param boolean
     * @return NONE sets the variable
     */
    function distinct(){
        $this->_distinct = TRUE;        
        return $this;
    }
    
    /**
     * This function directly loads a specific row based on the id of the primary or search column
     * 
     * @param mixed $id if array the array key should be the search column to be used and its value eg ['name'=> $name],
     *                  if string, the value will be compared against the table primary key
     * @param type $search_column used
     * @param type $select_column to be returned in result
     * 
     * @return mixed
     */
    function find($id, $select_column='*'){
        
        if(!is_string($this->primarykey)){
            $this->_set_primary_key();
        }

        if(is_array($id)){
            
            if(count($id) == 1){
                
                $column = array_keys($id)[0];
                $this->where($column,$id[$column]);
                
                $this->_selectid = $id[1];
            }
            else{
                
                foreach($id as $column => $value){
                    $this->where($column, $value);
                    $selectid .= $value.',';
                }
                
                $this->_selectid .= rtrim($selectid,',');
            }
        }
        else{
            
            if($this->_relationmode){
                
                unset($this->dbobject->_where);
                $this->where($this->_relationship[$this->table]['local'], $id);
            }
            else{          
                
                $this->where($this->primarykey, $id);
            }
            
            $this->_selectid = $id;
        }
        
        if($select_column == '*'){           
            $result = $this->dbobject->get($this->table,1);
        }
        else{            
            $result = $this->dbobject->get($this->table,1,$select_column);
        }
        
        //reset table and ORM states
        $this->_ormReset();
        $this->_restoreMainTable();
        
        if(is_array($result[0])){
            
            //set the data extracted into the dbobject
            foreach($result[0] as $property => $value){
                $this->$property = $value;
            }
        }
        
        if(count($result) >= 1){
            
            $this->primary_key_value[$this->primarykey] = $result[0][$this->primarykey];
            $this->_selectcolumn = $select_column;  
            
            $this->data = $this->formatOutput($result[0]);
        }
        else{
            $this->data = NULL;
        }
        
        //$this->_resetStoredQuery();
        $this->fxnstatus['last_function'] = __FUNCTION__;
        
        return $this;
    }
    
    /**
     * This function returns the number of rows in the processed query
     * 
     * @return int query result
     */
    public function count(){        
        return count($this->data);
    }
    
    /**
     * Returns primary table columns
     * @param string $format Simple or advanced
     * @return array $cols the table columns
     */
    public function columns($format = 'simple') {
        
        if($format == 'simple'){
            
            foreach($this->tablecols as $column){
                $cols[] = $column['Field'];
            }
        }
        elseif($format == 'advanced'){
            $cols = $this->dbobject->rawQuery("SHOW COLUMNS FROM ".  $this->dbobject->prefix . $this->table);
        }
        
        return $cols;
    }
    
    public function getTotalCount($from_last_query = TRUE){
        
        if($from_last_query == FALSE){
            
            $counts = $this->dbobject->rawQuery("SELECT count(*) FROM ". $this->dbobject->prefix . $this->table);

            foreach($counts as $count){

                $final_count = $count['count(*)'];
            }
        }
        else{
            
            $final_count = $this->dbobject->count();
        }
        
        return $final_count;
    }

    /**
     * This function changes the output_format variable. 
     * Allows ONLY 'object', 'array' and 'json' values  
     *    * 
     * @param string $new_format
     * 
     * @return \ORM
     */
    function format($new_format){
        $this->_output_format = strtolower($new_format);
        
        $this->fxnstatus['last_function'] = __FUNCTION__;
        return $this;
    }
    
    /**
     * Enables the entity bypass mechanism
     * 
     * @param type $boolean
     */
    public function bypass($boolean) {
        
        $this->_bypassentity = $boolean;
        return $this;
    }
    
    /**
     * This function changes the output format of the record results
     * 
     * @param type $data
     * @return mixed The formatted data according to the set format
     */
    protected function formatOutput($data){
        
        if($this->_output_format == 'object'){
            
            if(is_null($this->entity)){             
                $formatted_data = json_decode(json_encode($data), FALSE);
            }
            else{
                if($this->_bypassentity === FALSE)
                    $formatted_data = $this->_parseDataIntoEntity($data);
                else
                    $formatted_data = json_decode(json_encode($data), FALSE);
            }            
        }
        elseif($this->_output_format == 'array'){
            $formatted_data = json_decode(json_encode($data), TRUE);
        }
        elseif ($this->_output_format == 'json') {
            $formatted_data = json_encode($data);
        }
        
        $this->fxnstatus['last_function'] = __FUNCTION__;
        return $formatted_data;
    }
    
    private function _parseDataIntoEntity($data){
        
        if(is_array($data)){
            
            if(Help::isAssoc($data) === FALSE){
                
                //assign each result row into a specific property in the entity method
                foreach ($data as $results) {

                    $count = 0;
                    $entity = App::resolve($this->entity);
                    
                    if(is_array($results)){

                        foreach($results as $property => $value){
                            
                            $entity->{$property} = $value;

                            if($count == (count($results)-1)){
                                
                                $list[] = $this->_assignEntitiesToList($entity);
                                unset($entity);
                            }
                            
                            $count++;
                        }
                    }
                }
            }
            else{
                
                $entity = App::resolve($this->entity);
                
                foreach($data as $property => $value){
                    $entity->{$property} = $value;
                }
                
                $list = $this->_assignEntitiesToList($entity);
            }
        }
        elseif(is_object($data)){
            $list = $data;
        }
        
        return $list;
    }
    
    private function _assignEntitiesToList($entity){
        
        //check for handle method in entity class
        if(method_exists($entity, 'handle')){
            $list = App::call([$entity, 'handle']);
        }
        else{
            $list = $entity;
        }
            
        return $list;
    }
    
    /**
     * This is a helper function allows the user to set the related tables 
     * for main table separately when the need arises
     * 
     * @param string $table Insert an array containing all the tables 
     * linked to the main table
     * 
     * @return boolean TRUE on setting the tables and FALSE on failure
     * 
     */
    function associate($table)
    {
        if(count($this->_relatedtables) == 0){
            
            $this->_relatedtables[0] = $table;
        }
        else{
            
            $rcount = count($this->_relatedtables) + 1;
            $this->_relatedtables[$rcount] = $table;
        }
        
        return $this;
    }
    
    /**
     * This is a helper function allows the user to set the related tables 
     * for main table separately when the need arises
     * 
     * @param array $tables Insert an array containing all the tables 
     * linked to the main table
     * 
     * @return boolean TRUE on setting the tables and FALSE on failure
     * 
     */
    function associateMany(array $tables){
        
        $this->_relatedtables = array_merge($this->_relatedtables, $tables);
        
        return $this;
    }
    
    /**
     * This is a helper function allows the user to set the reference Map for related tables separately when the need arises
     * 
     * @param array $relationshipArray should contain the fields below
     * 
     * @param mixed 'foreign' => 'column_name_of_linked_table' 
     * // use an array if there are multiple columns
     * 
     * @param mixed 'local' => 'column_name_of_parent_table' 
     * //use an array if there are multiple columns in the exact same order as the localColumns above
     * 
     * @param string 'on_update' => 'update', 
     * //or NO - when a primary table row is updated, the corresponding table row will be updated auotmatically, if set to NO, the relation row isnt updated
     * 
     * @param string 'on_delete' => 'delete' 
     * //or NO - when the primary table row is deleted, the corresponding table row will be deleted automatically
     * 
     * @return boolean TRUE on setting the tables and FALSE on failure
     */
    function using($relationshipArray){
        
        $keys = array_keys($relationshipArray);
        
        if(!in_array($keys[0], $this->_relatedattributes)){
            
            App::critical_error('Please use the usingMany() method to define relations for multiple tables');
        }
        
        //check if there is a mix up when using the using() or usingMany() methods
        if(isset($this->_relatedtables)){
            $this->_relationship[ end($this->_relatedtables) ] = $relationshipArray;            
            return $this;
        }
        else{
            return FALSE;
        }
    }
    
    /**
     * This is a helper function allows the user to set the reference Map for related tables separately when the need arises
     * 
     * @param array $relationshipArray should contain the fields below
     * 
     * @param mixed 'foreign' => 'column_name_of_linked_table' 
     * // use an array if there are multiple columns
     * 
     * @param mixed 'local' => 'column_name_of_parent_table' 
     * //use an array if there are multiple columns in the exact same order as the localColumns above
     * 
     * @param string 'on_update' => 'update', 
     * //or NO - when a primary table row is updated, the corresponding table row will be updated auotmatically, if set to NO, the relation row isnt updated
     * 
     * @param string 'on_delete' => 'delete' 
     * //or NO - when the primary table row is deleted, the corresponding 
     * table row will be deleted automatically
     * 
     * @example  $this->associateMany(['table_one','table_two'])
                ->usingMany([
                    'table_one' => ['type' => 'one-to-one',
                                    'local' => 'quoteid',
                                    'foreign' => 'quoteid',
                                    'on_update' => 'NO',
                                    'on_delete' => 'delete'],
     *              'table_two' => ['type' => 'one-to-one',
                                    'local' => 'quoteid',
                                    'foreign' => 'quoteid',
                                    'on_update' => 'NO',
                                    'on_delete' => 'delete']
                ]);
     * 
     * @return object $this on setting the tables and FALSE on failure
     */
    public function usingMany($relationshipArray){
        
        if(isset($this->_relatedtables)){
            
            $keys = array_keys($relationshipArray);

            foreach($keys as $key){

                if(in_array($key, $relationshipArray)){

                    $this->_relationship[$key] = $relationshipArray[$key];
                }
            }
            
            return $this;
        }
        else{
            
            return FALSE;
        }
    }
    
    /**
     * This helper function allows the user to set the columns and values for each of the relation table
     * 
     * @example array('table_name' => array(
     *              'column_name' => 'column_value'
     *          ));
     * 
     * @param type $dataArray
     * @return boolean
     */
    function updateWith($dataArray){
        
        if(isset($dataArray)){
            
            $this->_relateddata = $dataArray;
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    /**
     * This function compiles the data entered by the user 
     * and that which is still unchanged from the database table into the data variable
     * 
     * @return  array $this->data variable
     */
    protected function _compileRowData(){
        
        if(!is_null($this->entity)){
            return $this->_structureDataHandler(TRUE);
        }        
        
        return $this->_structureDataHandler();
    }
    
    /**
     * Assigns the data variable based on the table columns
     */
    private function _structureDataHandler($isentity = FALSE){
        
        $tablecols = $this->columns('advanced');

        //create data variable if null or clear if has previous results
        if(is_null($this->data) || is_array($this->data) || is_object($this->data)){
            $this->data = $this->createEmpty();
        }
        elseif(is_string($this->data)) {
            App::critical_error('The data variable is used within the Jenga framework, please use another variable name');
        }
        
        foreach($tablecols as $column){
                
            if(($this->$column['Field']!=0 || $this->$column['Field'] != '')){

                /**
                    if($isentity === FALSE && $column['Field'] == $this->primarykey){
                        continue;
                    }
                    else{
                        $this->data->$column['Field'] = $this->$column['Field'];
                    }
                 * 
                 */
                
                $this->data->$column['Field'] = $this->$column['Field'];
            }
        }
        
        return $this;
    }
    
    /**
     * Processes the database row deletions
     * 
     * @param type $numRows
     */
    public function delete($numRows = null){
        
        if($this->_relationmode == FALSE){
            
            $this->_processRelations('delete');
        }
        
        return $this->dbobject->delete($this->table, $numRows);
    }
    
    /* *
     * 
     * This function performs an automatic save of the information set into the DB.
     * 
     * @uses $DB_Instance->save();     * 
     * @param None.
     * 
     * @return (Column => value) array on SUCCESS, database error in array on FAILURE, also includes the details for the related tables
     */
    public function save(){
        
        //check the search settings just in case the records are search results
        $this->processStoredQuery();
        
        //compile the necessary row information and sets the sysdata variable
        $this->_compileRowData();
        $key = $this->primarykey;
        
        //performs an insert if ID isnt set
        if(!property_exists($this, $key) || $this->data->$key == NULL){
            
            $ins = $this->dbobject->insert($this->table, $this->data);
            
            if(is_int($ins)){
                
               $this->fxnstatus['last_altered_row'] = $ins;
               
               //do the relations
               $istatus = $this->_processRelations('insert');
               if($istatus != NULL)
               {
                  $this->fxnstatus = array_merge($this->fxnstatus, $istatus);
               }
            }
            else {
                $this->fxnstatus['ERROR'] = $this->getLastError();
                $this->errors[] = $this->getLastError();
            }
        }//performs an update
        else {
            
            //recreate the where condition which has been reset by previous db layer operations
            $where = $this->_dbquery['where']; 
            
            $this->restoreWhere($where);
            
            $update = $this->dbobject->update($this->table, $this->data);
            
            //do the update
            if($update == TRUE){
                
                //update the status variable     
                $this->fxnstatus['last_altered_row'] = $this->primary_key_value[ $this->primarykey ];
                
                //do the relations
                $ustatus = $this->_processRelations('update');
                
               if($ustatus != NULL){
                  $this->fxnstatus = array_merge($this->fxnstatus, $ustatus);
               }
            }
            else{
                $this->fxnstatus['ERROR'] = $this->getLastError();
                $this->errors[] = $this->getLastError();
            }
        }
        
        //add value to class property
        $this->last_altered_row = $this->fxnstatus['last_altered_row'];
        
        //check for proxy setting and restore main table
        $this->_restoreMainTable();
        
        $this->fxnstatus['last_function'] = __FUNCTION__;
        return $this->fxnstatus;
    }
    
    /**
     * Checks if last database operation has any errors
     * @return string the error messages
     */
    public function hasNoErrors() {
        
        if(array_key_exists('ERROR', $this->fxnstatus)){            
            return $this->fxnstatus['ERROR'];
        }
        else{
            return TRUE;
        }
    }
    
    /**
     * If the ORM main table has been altered proxyly, 
     * this function restores the table variable to the main table
     */
    private function _restoreMainTable(){
        
        //check for artificial setting and restore main table
       if($this->_proxymode && !is_null($this->_secondary_table)){
           
           $this->table = $this->_secondary_table;
           
           //reset the primary key back to NATIVE table
           $this->_set_primary_key();           
           $this->_proxymode = FALSE;
       }
    }
    
    /**
     * Restores the where conditions which may have been overwritten by previous db layer operations
     * 
     * @param array $where
     */
    private function restoreWhere($where){   
        
        if(is_array($where)){
            
            foreach($where as $condition){
                
                if($condition[0] == 'AND')
                    $this->dbobject->where($condition[2], $condition[1]);
                elseif($condition[0] == 'OR')
                    $this->dbobject->where($condition[2], $condition[1]);
            }
        }        
    }
    
    /**
     * This function processes the dbquery variables set by the various functions
     * 
     * @return array Contains the returned rows from the select query.
     */
    function run(){
        
        //check the search settings just in case the records are search results
        $this->processStoredQuery();
        
        //check the selected columns
        if(isset($this->_dbquery['select'])){
            $columns = rtrim($this->_dbquery['select'], ',');
        }
        
        //check any relations
        if($this->_relationmode){
            
            $type = $this->_checkRelations();
            $this->table = $this->_secondary_table;
            
            if($type == 'one-to-one'){
                
                $numRows = 1;
                
                $data = $this->dbobject->get($this->table, $numRows, $column);
                $this->data = $data[0];
                
            }
            elseif($type == 'one-to-many'){
                
                //the numRows is already set to NULL 
                $this->data = $this->dbobject->get($this->table, $numRows, $column);
            }
        }
        else{
            
            $this->data = $this->dbobject->get($this->table, NULL, $columns);
        }
        
        //unset the select columns
        $this->_ormReset();        
        //$this->_resetStoredQuery();
        
        if($this->_relationmode){
           
           //reset the db table setting
            $this->_setToDefault();
            $this->_relationmode = FALSE;
       }
       
       //check for proxy setting and restore main table
       $this->_restoreMainTable();
        
       $this->fxnstatus['last_function'] = __FUNCTION__;
       
       //$this->_searchStore();
       
       return $this->formatOutput($this->data);
    }
    
    /**
     * Assigns the related table, foreign and local keys and their values in the where section of the query
     * 
     * @return string $relationtype
     */
    protected function _checkRelations(){

        //the $this->table variable has now been changed
        $relation = $this->_relationship[$this->_secondary_table];
        
        if(isset($relation)){ 

            $this->join($this->table, 
                    TABLE_PREFIX.$this->table.'.'.$relation['local'] .'='.
                    TABLE_PREFIX.$this->_secondary_table.'.'.$relation['foreign']);
            
            if($this->primarykey != $relation['foreign']){
                
                $this->_selectid = $this->data->$relation['foreign'];
            }
            
            $this->where(TABLE_PREFIX.$this->table.'.'.$relation['local'], $this->_selectid);
            
            if($relation['type'] == strtolower('one-to-one')){
                
                $type = strtolower('one-to-one');
            }
            elseif($relation['type'] == strtolower('one-to-many')){
                
                $type = strtolower('one-to-many');
            }
        }
        
        return $type;
    }
}