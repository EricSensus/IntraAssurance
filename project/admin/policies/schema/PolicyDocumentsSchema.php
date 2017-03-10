<?php
namespace Jenga\MyProject\Policies\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 09/03/2017
 * Time: 14:44
 */
class PolicyDocumentsSchema implements SchemaInterface
{

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        return SchemaTasks::create('policies_documents')
            ->column('id',['INT','NOT NULL','AUTO_INCREMENT'])->primary('id')
            ->column('policies_id', [
                'int', 'not null'
            ])
            ->column('documents_id', [
                'int', 'not null'
            ])
//            ->foreign('policies_id')->references('id')->on('policies')
//            ->onDelete('cascade')->onUpdate('cascade')
//            ->foreign('documents_id')->references('id')->on('documents')
//            ->onDelete('cascade')->onUpdate('cascade')
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