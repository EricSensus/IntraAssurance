<?php

namespace Jenga\MyProject\Claims\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;
use Jenga\MyProject\Elements;

class ClaimsSchema implements SchemaInterface
{
    /**
     * @var string
     */
    public $table = 'claims';

    /**
     * This is for creating the element table and its columns
     */
    public function build()
    {
        SchemaTasks::create($this->table)
            ->column('id', ['INT', 'NOT NULL', 'AUTO_INCREMENT'])->primary('id')
            ->column('policy_id', ['INT', 'NOT NULL'])
            ->column('customer_id', ['INT', 'NOT NULL'])
            ->column('status', ['varchar(50)', 'default "Open"'])
            ->column('subject', ['varchar(50)', 'not null'])
            ->column('description', ['text'])
            ->column('closed', ['boolean', 'default 0'])
            ->column('created_at', ['timestamp', 'default CURRENT_TIMESTAMP'])
            ->column('agent_id', ['INT', 'NULL'])
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
