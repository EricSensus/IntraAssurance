<?php

namespace Jenga\MyProject\Tracker\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;

class TrackerSchema implements SchemaInterface
{

    public $table = 'quotes_tracker';

    /**
     * This is for creating the element table and its columns
     */
    public function build()
    {

        return SchemaTasks::dropAndCreate($this->table)
            ->column('id', ['INT', 'NOT NULL', 'AUTO_INCREMENT'])->primary('id')
            ->column('customer_id', ['INT', 'NOT NULL'])
            ->column('product_id', ['INT', 'NOT NULL'])
            ->column('quote_id', ['INT', 'NOT NULL'])
            ->column('step', ['INT(5)', 'NOT NULL'])
            ->column('created_at', ['INT(15)', 'NOT NULL'])
            ->column('modified_at', ['INT(15)', 'NOT NULL'])
            ->column('status', ['varchar(50)', 'NOT NULL'])
            ->build();
    }

    /**
     * This is for populating the table with its initial scaffolding data
     */
    public function seed()
    {

    }

    /**
     * This is for running advanced operations on the table
     */
    public function run()
    {

    }
}
