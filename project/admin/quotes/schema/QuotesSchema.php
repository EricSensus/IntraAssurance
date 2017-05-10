<?php

namespace Jenga\MyProject\Quotes\Schema;

use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;

/**
 * Created by PhpStorm.
 * User: developer
 * Date: 01/03/2017
 * Time: 13:07
 */
class QuotesSchema implements SchemaInterface
{

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        SchemaTasks::create('customer_quotes')
            ->column('id', ['int', 'not null', 'auto_increment'])->primary('id')
            ->column('customers_id', ['int', 'not null'])
            ->column('products_id', ['varchar(100)', 'not null'])
            ->column('datetime', ['int', 'not null'])
            ->column('introtext', ['text', 'null'])
            ->column('customer_info', ['text'])
            ->column('product_info', ['text', 'null'])
            ->column('customer_entity_data_id', ['text', 'null'])
            ->column('amount', ['text', 'not null'])
            ->column('recommendation', ['int', ' null'])
            ->column('status', ['varchar(100)', 'not null'])
            ->column('acceptedoffer', ['varchar(100)', 'not null', 'default \'Pending Response\''])
            ->column('source', ['varchar(50)', 'not null', 'default \'Internal\''])
            ->build();
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
