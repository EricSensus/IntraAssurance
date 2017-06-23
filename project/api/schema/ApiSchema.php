<?php

namespace Jenga\MyProject\Api\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;

class ApiSchema implements SchemaInterface
{
    /**
     * @var string
     */
    public $table = 'api_tokens';

    /**
     * This is for creating the element table and its columns
     */
    public function build()
    {
        $schema = SchemaTasks::create($this->table);
        $schema->column('id', ['INT', 'NOT NULL', 'AUTO_INCREMENT'])->primary('id');
        $schema->column('name', ['varchar(50)', 'NOT NULL']);
        $schema->column('token', ['text', 'NOT NULL']);
        $schema->column('company', ['varchar(100)', 'NOT NULL']);
        $schema->column('format', ['varchar(100)', 'NOT NULL']);
        $schema->column('last_access', ['timestamp', 'NOT NULL']);
        return $schema->build();
    }

    /**
     * Sometimes we need to log
     *
     * @return mixed
     */
    public function log()
    {
        $schema = SchemaTasks::create('api_logs');
        $schema->column('id', ['INT', 'NOT NULL', 'AUTO_INCREMENT'])->primary('id');
        $schema->column('app_id', ['int(10)', 'NOT NULL']);
        $schema->column('endpoint', ['varchar(10)', 'NOT NULL']);
        $schema->column('type', ['varchar(10)', 'NOT NULL']);
        $schema->column('response', ['text', 'NOT NULL']);
        $schema->column('status', ['text', 'NULL']);
        $schema->column('format', ['varchar(10)', 'NOT NULL']);
        $schema->column('time', ['timestamp', 'NOT NULL', 'default CURRENT_TIMESTAMP']);
        return $schema->build();
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
