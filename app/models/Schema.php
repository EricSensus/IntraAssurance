<?php
namespace Jenga\App\Models;

/**
 * Schema class is designed to create, manage and delete database tables and columns in MYSQL
 * @author Stanley Ngumo
 */

use Jenga\App\Database\Mysqli\Database;
use Jenga\App\Helpers\Help;

class Schema {

    public $database;
    public $table = null;

    private $_sqlquery;
    private $_sqlcols = [];
    private $_primary = null;
    private $_unique = [];
    private $_fk = [];
    private $_dropifexists = false;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    /**
     * Assigns the table to be created
     * @param type $table
     */
    public function table($table) {

        if(strstr($table, $this->database->prefix))
            $this->table  = $table;
        else
            $this->table = $this->database->prefix.$table;

        return $this;
    }
    
    /**
     * Sets the dropifexists marker to drop the existing table on table creation
     */
    public function dropifExists($boolean = true) {
        
        $this->_dropifexists = $boolean;
        return $this;
    }

    /**
     * Drops an existing table
     * 
     * @param type $table
     * @return boolean
     */
    public function dropTable($table) {

        $this->database->rawQuery("DROP TABLE ".$this->database->prefix.$table);

        if($this->database->count >= 1)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Assign the columns to created along with their attributes
     * @param type $name
     * @param type $attributes
     */
    public function column($name, $attributes = []){

        $attrs = $this->_processAttributes($attributes);

        $this->_sqlcols[$name] = $attrs;
        return $this;
    }

    /**
     * Converts array of attributes into SQL variables
     * @param type $attributes
     * @return type
     */
    private function _processAttributes($attributes){

        if(Help::isAssoc($attributes)){

            foreach($attributes as $key => $value){
                $attrs[] = $key.'('.$value.')';
            }
        }
        else{
            $attrs = $attributes;
        }

        return $attrs;
    }

    /**
     * Designates the table primary column
     * @param type $column
     */
    public function primary($column) {

        $this->_primary = $column;
        return $this;
    }

    /**
     * Designates columns as UNIQUE
     * @param type $column
     */
    public function unique($column){

        if(is_array($column)){
            $this->_unique[] = join(',', $column);
        }
        else{
            $this->_unique[] = $column;
        }
    }

    /**
     * Assigns foreign key
     * @param string $name
     */
    public function foreign($name) {

        $this->_fk['name'] = $name;
        return $this;
    }

    /**
     * Assigns referenced column
     * @param string $refcol
     */
    public function references($refcol) {
        $this->_fk['refcolumn'] = $refcol;
        return $this;
    }

    /**
     * Assigns reference table
     * @param type $table
     */
    public function on($table){
        $this->_fk['reftable'] = $table;
       return $this;
    }

    /**
     * Sets the ON DELETE options namely: NO ACTION,RESTRICT,SET NULL, CASCADE, SET DEFAULT
     * @param type $options
     */
    public function onDelete($options) {
        $this->_fk['ondelete'] = strtoupper($options);
        return $this;
    }

    /**
     * Sets the ON UPDATE options namely: NO ACTION,RESTRICT,SET NULL, CASCADE, SET DEFAULT
     * @param type $options
     */
    public function onUpdate($options){
        $this->_fk['onupdate'] = strtoupper($options);
        return $this;
    }

    /**
     * Process the foreign key constraints
     * @param type $fk
     */
    private function _parseFKConstraints($fk){

        $fk = 'FOREIGN KEY fk_'.$fk['reftable'].'_'.$this->table.'('.$fk['refcolumn'].')';
        $fk .= 'REFERENCES '.$fk['reftable'].'('.$fk['refcolumn'].')';

        //on delete
        if(array_key_exists('ondelete', $fk))
            $fk .= 'ON DELETE '.$fk['ondelete'];

        //on update
        if(array_key_exists('onupdate', $fk))
            $fk .= 'ON UPDATE '.$fk['onupdate'];

        return $fk;
    }

    /**
     * Checks if sent table already exists
     * @param type $table
     */
    public function hasTable($table) {

        $this->database->rawQuery("SHOW TABLES LIKE '".  $this->database->prefix.$table."'");

        if($this->database->count >= 1)
            return TRUE;
        else
            return FALSE;
    }

    public function hasColumn($column){

        $this->database->rawQuery("SHOW COLUMNS FROM ".$this->database->prefix.$this->table." LIKE '".$column."'");

        if($this->database->count >= 1)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Adds the Unique constraint to an existing table column
     *
     * @param string $column
     * @return boolean TRUE or FLASE id column not present
     */
    public function addUniqueConstraint($column) {

        if(!$this->hasColumn($column)){
            $this->database->rawQuery('ALTER TABLE `'.$this->table.'` ADD UNIQUE '.$column);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Adds foreign key constraint to table
     *
     * @param type $referredcolumn
     * @param type $referredtable
     * @param type $ondelete
     * @param type $onupdate
     *
     * @return boolean
     */
    public function addForeignKey($referredcolumn, $referredtable, $ondelete = NULL, $onupdate = NULL) {

        $action = $this->database->rawQuery('ALTER TABLE '
                . '`'.$this->table.'` '
                . 'ADD FOREIGN KEY fk_'.$referredtable.'_'.$this->table.'('.$referredcolumn.')'
                . 'REFERENCES '.$referredtable.'('.$referredcolumn.')'
                . (!is_null($ondelete) ? 'ON DELETE '.$ondelete : '')
                . (!is_null($onupdate) ? 'ON UPDATE '.$onupdate : ''));

        if($action)
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Adds a new column
     *
     * @param type $column
     * @param type $attrs
     */
    public function addColumn($column, $attrs){

        $attributes = $this->_processAttributes($attrs);

        if(!$this->hasColumn($column)){
            $this->database->rawQuery('ALTER TABLE `'.$this->table.'` ADD '.$column.' '.join(' ', $attributes));
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Removes an existing table column
     * @param type $column
     */
    public function removeColumn($column){

        if(!$this->hasColumn($column)){
            $this->database->rawQuery('ALTER TABLE `'.$this->table.'` DROP '.$column);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Renames table column
     *
     * @param string $before
     * @param string $after
     * @param array $attrs
     *
     * @return boolean
     */
    public function renameColumn($before, $after, $attrs){

        $attributes = $this->_processAttributes($attrs);

        if(!$this->hasColumn($before)){
            $this->database->rawQuery('ALTER TABLE '.$this->table.' CHANGE '.$before.' '.$after.' '.join(' ',$attributes));
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Compiles the tables and sent coulmns and runs the respective database table creation query
     */
    public function build(){

        $columns = '';

        if(!is_null($this->table)){

            //run the drop table query before creating the new one
            if($this->_dropifexists){
                $this->database->rawQuery('DROP TABLE IF EXISTS '.$this->table.';');
            }
            
            $this->_sqlquery .= 'CREATE TABLE '.$this->table.' ';

            //process columns
            foreach($this->_sqlcols as $column => $attributes){
                $columns .= $column.' '.join(' ', $attributes).',';
            }

            //process primary column
            if(!is_null($this->_primary)){
                $columns .= 'primary key ('.$this->_primary.')';
            }

            //process unique columns
            if(count($this->_unique) !== 0){
                $columns .= 'UNIQUE ('.join(',', $this->_unique).')';
            }

            //foreign key
            if(count($this->_fk) !== 0){
                $columns .= $this->_parseFKConstraints($this->_fk);
            }

            //trim commas
            $columns = rtrim($columns, ',');

            $this->_sqlquery .= '('.$columns.')';
        }

        return $this->database->rawQuery($this->_sqlquery);
    }
}
