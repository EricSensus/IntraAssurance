<?php
namespace Jenga\MyProject\Profile\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;

class ProfileSchema implements SchemaInterface {
    
    public $table = '';

    /**
    * This is for creating the element table and its columns
    */
    public function build() {
        
    }
    
    /**
    * This is for populating the table with its initial scaffolding data
    */
    public function seed() {
        
    }

    /**
    * This is for running advanced operations on the table
    */
    public function run() {
        
    }
}
