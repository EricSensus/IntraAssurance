<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2/15/2017
 * Time: 9:59 AM
 */

namespace Jenga\MyProject\Customers\Schema;

use Jenga\App\Models\ORM;
use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;

class CustomerSchema implements SchemaInterface
{
    protected $table = 'customers';

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        SchemaTasks::dropAndCreate($this->table)
            ->column('id', ['int', 'not null', 'AUTO_INCREMENT'])
            ->column('name', ['text', 'not null'])
            ->column('mobile_no', ['text', 'not null'])
            ->column('email', ['text', 'not null'])
            ->column('id_number', ['text', 'not null'])
            ->column('date_of_birth', ['int', 'null'])
            ->column('enabled', ['varchar(20)'])
            ->column('postal_address', ['varchar(50)', 'null'])
            ->column('postal_code', ['varchar(20)', 'null'])
            ->column('regdate', ['int', 'not null'])
            ->column('insurer_agents_id', [
                'int',
                'default null'
            ])
            ->column('additional_info', ['text', 'default null'])
            ->primary('id')->build();
        
            ORM::rawQuery('ALTER TABLE '.TABLE_PREFIX.'customers
ADD CONSTRAINT '.TABLE_PREFIX.'customers_'.TABLE_PREFIX.'insurer_agents_id_fk
FOREIGN KEY (insurer_agents_id) REFERENCES '.TABLE_PREFIX.'insurer_agents (id) ON UPDATE CASCADE;');
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed()
    {

    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run()
    {

    }

}
