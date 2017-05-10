<?php
namespace Jenga\MyProject\Users\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;
/**
 * Created by PhpStorm.
 * User: DEVELOPER 1
 * Date: 13/04/2017
 * Time: 16:54
 */
class UsersSchema implements SchemaInterface{
    protected $table = 'users';

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        SchemaTasks::create($this->table)
            ->column('id', [
                'int',
                'not null',
                'auto_increment'
            ])
            ->column('username', [
                'varchar(200)',
                'not null',
                'unique'
            ])
            ->column('password', [
                'varchar(300)',
                'not null'
            ])
            ->column('accesslevels_id', [
                'int',
                'null'
            ])
            ->column('user_profiles_id', [
                'int',
                'null'
            ])
            ->column('insurer_agents_id', [
                'int',
                'null'
            ])
            ->column('customers_id', [
                'int',
                'null'
            ])
            ->column('enabled', [
                'text',
                'not null'
            ])
            ->column('last_login', [
                'int',
                'null'
            ])
            ->column('permissions', [
                'text',
                'null'
            ])
            ->column('verified', [
                'boolean',
                'default false'
            ])
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