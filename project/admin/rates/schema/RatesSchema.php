<?php
namespace Jenga\MyProject\Rates\Schema;

use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 01/03/2017
 * Time: 13:34
 */
class RatesSchema implements SchemaInterface
{

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        SchemaTasks::create('rates')
            ->column('id', [
                'int',
                'not null',
                'auto_increment'
            ])->primary('id')
            ->column('rate_name', ['text', 'not null'])
            ->column('rate_value', ['text', 'not null'])
            ->column('rate_type', ['text', 'not null'])
            ->column('rate_category', ['text', 'not null'])
            ->column('insurer_id', ['integer', 'not null', 'default 14'])
            ->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed()
    {
        $esu_rates = array(
            array('id' => '1','rate_name' => 'Third Party Only','rate_value' => '7500','rate_type' => 'Fixed','rate_category' => 'Motor'),
            array('id' => '2','rate_name' => 'Third Party Fire and Theft','rate_value' => '4.8','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '3','rate_name' => 'Comprehensive','rate_value' => '7.5','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '4','rate_name' => 'Riots and Strikes','rate_value' => '2.5','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '5','rate_name' => 'Motor Policy Levy','rate_value' => '0.25','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '6','rate_name' => 'Section A','rate_value' => '1.5','rate_type' => 'Percentage','rate_category' => 'Property'),
            array('id' => '7','rate_name' => 'Section B','rate_value' => '8.0','rate_type' => 'Percentage','rate_category' => 'Property'),
            array('id' => '8','rate_name' => 'Section C','rate_value' => '14','rate_type' => 'Percentage','rate_category' => 'Property'),
            array('id' => '9','rate_name' => 'Workmen Compensation','rate_value' => '500','rate_type' => 'Fixed','rate_category' => 'Property'),
            array('id' => '10','rate_name' => 'Property Policy Levy','rate_value' => '0.25','rate_type' => 'Percentage','rate_category' => 'Property'),
            array('id' => '12','rate_name' => 'Owners Liability','rate_value' => '1000','rate_type' => 'For any extra 1M','rate_category' => 'Property'),
            array('id' => '13','rate_name' => 'Occupier Liability','rate_value' => '1000','rate_type' => 'For any extra 1M','rate_category' => 'Property'),
            array('id' => '14','rate_name' => 'Terrorism','rate_value' => '0.25','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '15','rate_name' => 'Training Levy','rate_value' => '0.2','rate_type' => 'Percentage','rate_category' => 'Travel'),
            array('id' => '16','rate_name' => 'P.H.C.F Fund','rate_value' => '0.25','rate_type' => 'Percentage','rate_category' => 'Travel'),
            array('id' => '17','rate_name' => 'Stamp Duty','rate_value' => '40','rate_type' => 'Fixed','rate_category' => 'Travel'),
            array('id' => '18','rate_name' => 'Windscreen','rate_value' => '10','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '19','rate_name' => 'Audio System','rate_value' => '10','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '20','rate_name' => 'Passenger Liability','rate_value' => '10','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '21','rate_name' => 'Medical Levy','rate_value' => '0.20','rate_type' => 'Percentage','rate_category' => 'Medical'),
            array('id' => '22','rate_name' => 'P.H.C.F Fund','rate_value' => '0.25','rate_type' => 'Percentage','rate_category' => 'Medical'),
            array('id' => '23','rate_name' => 'Stamp Duty','rate_value' => '40.00','rate_type' => 'Fixed','rate_category' => 'Medical'),
            array('id' => '29','rate_name' => 'Test Rate','rate_value' => '98.9','rate_type' => 'Percentage','rate_category' => 'Motor'),
            array('id' => '34','rate_name' => 'Test Rate3','rate_value' => '34','rate_type' => 'Percentage','rate_category' => 'Motor')
        );

        SchemaTasks::insert('rates', $esu_rates);
    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run()
    {
        // TODO: Implement run() method.
    }
}