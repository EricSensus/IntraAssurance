<?php
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 16/02/2017
 * Time: 10:40
 */

namespace Jenga\MyProject\Travel\Schema;

use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;

class TravelSchema implements SchemaInterface
{
    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build()
    {
        return SchemaTasks::create('travel_pricing')
            ->column('id', [
                'int',
                'not null',
                'auto_increment'
            ])->primary('id')
            ->column('plan', [
                'varchar(50)',
                'not null'
            ])
            ->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed()
    {
        $travelpricing = array(
            array('id' => '1','plan' => 'Africa Basic Plan'),
            array('id' => '2','plan' => 'Europe Plus Plan'),
            array('id' => '3','plan' => 'Worldwide Basic Plan'),
            array('id' => '4','plan' => 'Worldwide Plus Plan'),
            array('id' => '5','plan' => 'Worldwide Extra'),
            array('id' => '6','plan' => 'Haj and Umrah Plan Basic '),
            array('id' => '7','plan' => 'Haj and Umrah Plan Plus'),
            array('id' => '8','plan' => 'Haj and Umrah Plan Extra')
        );

        foreach($travelpricing as $pricing){
            SchemaTasks::insert('travel_pricing', [
                'plan' => $pricing['plan']
            ]);
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