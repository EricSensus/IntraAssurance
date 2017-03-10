<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 16/02/2017
 * Time: 13:13
 */

namespace Jenga\MyProject\Medical\Schema;

use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;

class MedicalPricingSchema implements SchemaInterface
{

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        SchemaTasks::create('medical_pricing')
            ->column('id', [
                'int',
                'not null',
                'auto_increment'
            ])->primary('id')
            ->column('agerange_benefits', ['varchar(50)'])
            ->column('P1', ['int'])
            ->column('P2', ['int'])
            ->column('P3', ['int'])
            ->column('P4', ['int'])
            ->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed()
    {
        $bm_medical_pricing = [
            array('id' => '37','agerange_benefits' => 'Ac','P1' => '11250','P2' => '13750','P3' => '16500','P4' => '19000')
        ];

        $pricing_count = count($bm_medical_pricing);
        foreach ($bm_medical_pricing as $pricing) {
            SchemaTasks::insert('medical_pricing', $pricing);
        }
    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run()
    {
        // TODO: Implement run() method.
    }
}
