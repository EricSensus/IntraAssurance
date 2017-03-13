<?php
namespace Jenga\App\Models;

use Jenga\App\Core\App;

/**
 * This class handles migration for the Jenga Mysqli Database
 *
 * @author Samuel Okoth
 */
class Migration {
    
    protected $db_host;
    protected $db_name;
    protected $db_user;
    protected $db_pass;
    protected $db_port;
    protected $db_prefix;


    /**
     * @var mysqli
     */
    protected $mysqli;


    public function __construct(){
        
        $this->loadConfigurations();
        
        $this->mysqli =@ new \mysqli($this->db_host, $this->db_user, $this->db_pass);
        
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit;
        }
        elseif(!$this->mysqli->select_db($this->db_name)){
            
            //create the database
            $this->createDatabase();
        }
        
        $this->mysqli->select_db($this->db_name);
    }
    
    /**
     * Load database configurations
     */
    protected function loadConfigurations(){
        
        $configs = App::get('_config');
        
        $this->db_host = $configs->host;
        $this->db_name = $configs->db;
        $this->db_user = $configs->username;
        $this->db_pass = $configs->password;
        $this->db_port = $configs->port;
        $this->db_prefix = $configs->dbprefix;
    }
    
    protected function createDatabase() {
        
        $sql = 'CREATE DATABASE '.$this->db_name;
        
        if($this->mysqli->query($sql) === TRUE){
            echo 'Database: '.$this->db_name.' created \n';
            return TRUE;
        }
        else{
            echo 'Database of '.$this->db_name.' has failed';
            exit;
        }
    }

    /**
     * Export database.
     * 
     * @param array $tables Select some tables only to dump. Leave null for every table
     * @param null $filename Which filename to write to
     * @param string $exporttype This can be either of both - for stucture, structure - for structure only, data - for data only
     */
    public function export(array $tables = [], $filename = null, $exporttype = 'both')
    {
        $structure = true;
        $data = true;
        switch ($exporttype) {
            case 'structure':
                $data = false;
                break;
            case 'data':
                $structure = false;
                break;
        }
        //Some databases might be large. Might take too long for some servers
        // set_time_limit(3000);
        $this->mysqli->query("SET NAMES 'utf8'");
        $queryTables = $this->mysqli->query('SHOW TABLES');
        $target_tables = [];
        
        while ($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }
        
        if (!empty($tables)) {
            
            //add table prefix
            foreach($tables as $table){
                $list[] = $this->db_prefix.$table;
            }
            
            $target_tables = array_intersect($target_tables, $list);
        }
        
        $content = '';
        // $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $this->db_name . "`\r\n--\r\n\r\n\r\n";
        foreach ($target_tables as $table) {
            if (empty($table)) {
                continue;
            }
            $result = $this->mysqli->query('SELECT * FROM `' . $table . '`');
            $fields_amount = $result->field_count;
            $rows_num = $this->mysqli->affected_rows;
            $res = $this->mysqli->query('SHOW CREATE TABLE ' . $table);
            $TableMLine = $res->fetch_row();
            if ($structure) {
                $content .= "\n\n" . $TableMLine[1] . ";\n\n";
            }
            $TableMLine[1] = str_ireplace('CREATE TABLE `', 'CREATE TABLE IF NOT EXISTS `', $TableMLine[1]);
            if ($data) {
                for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
                    while ($row = $result->fetch_row()) { //when started (and every after 100 command cycle):
                        if ($st_counter % 100 == 0 || $st_counter == 0) {
                            $content .= "\nINSERT INTO " . $table . " VALUES";
                        }
                        $content .= "\n(";
                        for ($j = 0; $j < $fields_amount; $j++) {
                            $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                            if (isset($row[$j])) {
                                $content .= '"' . $row[$j] . '"';
                            } else {
                                $content .= '""';
                            }
                            if ($j < ($fields_amount - 1)) {
                                $content .= ',';
                            }
                        }
                        $content .= ")";
                        //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                        if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                            $content .= ";";
                        } else {
                            $content .= ",";
                        }
                        $st_counter = $st_counter + 1;
                    }
                }
                $content .= "\n\n\n";
            }
        }
        //  $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
        $backup_name = $filename ? $filename : $this->db_name . '.sql';
        
        //create the file
        $export = fopen($backup_name, 'w');
        fwrite($export, $content);
        return fclose($export);
        
        //exit;
    }

    /**
     * Import to the database
     * @param $script
     * @param bool $drop Drop existing tables
     * @return string
     */
    function import($script, $drop = false)
    {

        $SQL_CONTENT = (strlen($script) > 300 ? $script : file_get_contents($script));
        $allLines = explode("\n", $SQL_CONTENT);
        $this->mysqli->query('SET foreign_key_checks = 0'); //some keys might be pain full to work with
        preg_match_all("/\nCREATE TABLE(.*?)\`(.*?)\`/si", "\n" . $SQL_CONTENT, $target_tables);
        if ($drop) {
            foreach ($target_tables[2] as $table) {
                $this->mysqli->query('DROP TABLE IF EXISTS ' . $table);
            }
        }
        
        $this->mysqli->query('SET foreign_key_checks = 1');
        $this->mysqli->query("SET NAMES 'utf8'");
        $templine = '';    // Temporary variable, used to store current query
        
        foreach ($allLines as $line) {                                            // Loop through each line
            if (substr($line, 0, 2) != '--' && $line != '') {
                $templine .= $line;    // (if it is not a comment..) Add this line to the current segment
                if (substr(trim($line), -1, 1) == ';') {        // If it has a semicolon at the end, it's the end of the query
                    if (!$this->mysqli->query($templine)) {
                        print('Error performing query \'<strong>' . $templine . '\': ' . $this->mysqli->error . '<br /><br />');
                    }
                    $templine = ''; // set variable to empty, to start picking up the lines after ";"
                }
            }
        }
        
        return true;
    }
}
