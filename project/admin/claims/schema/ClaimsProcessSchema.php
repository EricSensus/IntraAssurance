<?php

namespace Jenga\MyProject\Claims\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;

class ClaimsProcessSchema implements SchemaInterface
{
    /**
     * @var string
     */
    public $table = 'claim_process';


    /**
     * This is for creating the element table and its columns
     */
    public function build()
    {
        SchemaTasks::create($this->table)
            ->column('id', ['INT', 'NOT NULL', 'AUTO_INCREMENT'])->primary('id')
            ->column('claim_id', ['int', 'not null'])
            ->column('user_id', ['int', 'not null'])
            ->column('documents_id', ['int', 'null'])
            ->column('description', ['text', 'not null'])
            ->column('created_at', ['timestamp', 'default CURRENT_TIMESTAMP'])
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
