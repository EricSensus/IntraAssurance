<?php
/**
 * Created by PhpStorm.
 * User: DEVELOPER 1
 * Date: 20/04/2017
 * Time: 16:49
 */

namespace Jenga\MyProject\Navigation\Schema;

use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;

class MenuGroupSchema implements SchemaInterface
{
    private $table = 'menu_groups';
    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        SchemaTasks::dropAndCreate($this->table)
            ->column('id', ['int', 'not null', 'auto_increment'])
            ->column('title', ['varchar(48)', 'not null'])
            ->column('alias', ['varchar(100)', 'not null', 'unique'])
            ->column('description', ['varchar(255)', 'not null'])
            ->column('accesslevels_id', ['int', 'not null'])
            ->column('permissions', ['text', 'null'])
            ->primary('id')
            ->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed()
    {
        $esu_menu_groups = array(
            array('id' => '1','title' => 'Admin Menu','alias' => 'admin-menu','description' => 'The admin menu for the site','accesslevels_id' => '9','permissions' => ''),
            array('id' => '2','title' => 'Frontend','alias' => 'frontend','description' => 'Frontend menu','accesslevels_id' => '17','permissions' => ''),
            array('id' => '3','title' => 'Super Admin','alias' => 'super-admin','description' => 'Super Admin menu','accesslevels_id' => '7','permissions' => ''),
            array('id' => '4','title' => 'Footer','alias' => 'footer','description' => 'Footer menu','accesslevels_id' => '17','permissions' => ''),
            array('id' => '5','title' => 'Technical Menu','alias' => 'technical-menu','description' => 'The IT Specific menu for administration of the Esurance System','accesslevels_id' => '18','permissions' => ''),
            array(
                'id' => '6',
                'title' => 'Customer Menu',
                'alias' => 'customer-menu',
                'description' => 'The customer menu when he/she logs in',
                'accesslevels_id' => '16'
            )
        );

        SchemaTasks::insert($this->table, $esu_menu_groups);
    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run()
    {
        // TODO: Implement run() method.
    }
}
