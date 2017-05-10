<?php
/**
 * Created by PhpStorm.
 * User: DEVELOPER 1
 * Date: 10/04/2017
 * Time: 16:13
 */

namespace Jenga\MyProject\Customers\Schema;

use Jenga\App\Models\ORM;
use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;

class CustomerEntityDataSchema implements SchemaInterface
{
    protected $table = 'customer_entity_data';
    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        SchemaTasks::create($this->table)
            ->column('id', ['int', 'not null', 'auto_increment'])
            ->column('customers_id', ['int', 'not null'])
            ->column('entities_id', ['int', 'not null'])
            ->column('entity_values', ['text'])
            ->column('product_id', ['int', 'not null'])
            ->primary('id')->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed()
    {
        // TODO: Implement seed() method.
    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run()
    {
        // TODO: Implement run() method.
    }
}