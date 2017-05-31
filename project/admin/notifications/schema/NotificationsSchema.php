<?php
namespace Jenga\MyProject\Notifications\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;

class NotificationsSchema implements SchemaInterface {
    
    public $table = 'notifications';

    /**
    * This is for creating the element table and its columns
    */
    public function build() {
        
        return SchemaTasks::dropAndCreate($this->table)
                        ->column('id',['INT','NOT NULL','AUTO_INCREMENT'])->primary('id')
                        ->column('message',['TEXT','NOT NULL'])
                        ->column('acl',['VARCHAR(100)','NOT NULL'])
                        ->column('directto',['VARCHAR(200)','NOT NULL'])
                        ->column('userid',['VARCHAR(100)','NOT NULL'])
                        ->column('viewed',['INT(11)','NOT NULL'])
                        ->column('created_at',['INT(11)','NOT NULL'])
                    ->build();
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
