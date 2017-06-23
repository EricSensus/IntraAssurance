<?php

/**
 * Created by PhpStorm.
 * User: developer
 * Date: 16/02/2017
 * Time: 13:00
 */

namespace Jenga\MyProject\Travel\Schema;

use Jenga\App\Models\SchemaTasks;
use Jenga\App\Models\SchemaInterface;

class TravelPlanDetailsSchema implements SchemaInterface {

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build() {
        SchemaTasks::create('travel_plan_details')
                ->column('details_id', [
                    'int',
                    'not null',
                    'auto_increment'
                ])->primary('details_id')
                ->column('days', [
                    'varchar(50)',
                    'not null'
                ])
                ->column('premium', [
                    'double',
                    'not null'
                ])
                ->column('plan_id', [
                    'integer',
                    'not null'
                ])->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed() {
        $bm_travel_plan_details = [
            ['details_id' => '1', 'days' => '7', 'premium' => '20', 'plan_id' => '1'],
            ['details_id' => '2', 'days' => '10', 'premium' => '26', 'plan_id' => '1'],
            ['details_id' => '3', 'days' => '15', 'premium' => '32', 'plan_id' => '1'],
            ['details_id' => '4', 'days' => '21', 'premium' => '37', 'plan_id' => '1'],
            ['details_id' => '5', 'days' => '30', 'premium' => '58', 'plan_id' => '1'],
            ['details_id' => '6', 'days' => '60', 'premium' => '77', 'plan_id' => '1'],
            ['details_id' => '7', 'days' => '92', 'premium' => '99', 'plan_id' => '1'],
            ['details_id' => '8', 'days' => '183', 'premium' => '155', 'plan_id' => '1'],
            ['details_id' => '9', 'days' => '365', 'premium' => '191', 'plan_id' => '1'],
            ['details_id' => '10', 'days' => '7', 'premium' => '29', 'plan_id' => '2'],
            ['details_id' => '11', 'days' => '10', 'premium' => '38', 'plan_id' => '2'],
            ['details_id' => '12', 'days' => '15', 'premium' => '45', 'plan_id' => '2'],
            ['details_id' => '13', 'days' => '21', 'premium' => '58', 'plan_id' => '2'],
            ['details_id' => '14', 'days' => '30', 'premium' => '74', 'plan_id' => '2'],
            ['details_id' => '15', 'days' => '60', 'premium' => '122', 'plan_id' => '2'],
            ['details_id' => '16', 'days' => '92', 'premium' => '170', 'plan_id' => '2'],
            ['details_id' => '17', 'days' => '183', 'premium' => '245', 'plan_id' => '2'],
            ['details_id' => '18', 'days' => '365', 'premium' => '356', 'plan_id' => '2'],
            ['details_id' => '19', 'days' => '7', 'premium' => '29', 'plan_id' => '3'],
            ['details_id' => '20', 'days' => '10', 'premium' => '38', 'plan_id' => '3'],
            ['details_id' => '21', 'days' => '15', 'premium' => '45', 'plan_id' => '3'],
            ['details_id' => '22', 'days' => '21', 'premium' => '58', 'plan_id' => '3'],
            ['details_id' => '23', 'days' => '30', 'premium' => '74', 'plan_id' => '3'],
            ['details_id' => '24', 'days' => '60', 'premium' => '122', 'plan_id' => '3'],
            ['details_id' => '25', 'days' => '92', 'premium' => '170', 'plan_id' => '3'],
            ['details_id' => '26', 'days' => '183', 'premium' => '245', 'plan_id' => '3'],
            ['details_id' => '27', 'days' => '365', 'premium' => '356', 'plan_id' => '3'],
            ['details_id' => '28', 'days' => '7', 'premium' => '40', 'plan_id' => '4'],
            ['details_id' => '29', 'days' => '10', 'premium' => '54', 'plan_id' => '4'],
            ['details_id' => '30', 'days' => '15', 'premium' => '60', 'plan_id' => '4'],
            ['details_id' => '31', 'days' => '21', 'premium' => '66', 'plan_id' => '4'],
            ['details_id' => '32', 'days' => '30', 'premium' => '89', 'plan_id' => '4'],
            ['details_id' => '33', 'days' => '60', 'premium' => '133', 'plan_id' => '4'],
            ['details_id' => '34', 'days' => '92', 'premium' => '180', 'plan_id' => '4'],
            ['details_id' => '35', 'days' => '183', 'premium' => '324', 'plan_id' => '4'],
            ['details_id' => '36', 'days' => '365', 'premium' => '414', 'plan_id' => '4'],
            ['details_id' => '37', 'days' => '7', 'premium' => '51', 'plan_id' => '5'],
            ['details_id' => '38', 'days' => '10', 'premium' => '80', 'plan_id' => '5'],
            ['details_id' => '39', 'days' => '15', 'premium' => '98', 'plan_id' => '5'],
            ['details_id' => '40', 'days' => '21', 'premium' => '103', 'plan_id' => '5'],
            ['details_id' => '41', 'days' => '30', 'premium' => '156', 'plan_id' => '5'],
            ['details_id' => '42', 'days' => '60', 'premium' => '178', 'plan_id' => '5'],
            ['details_id' => '43', 'days' => '92', 'premium' => '235', 'plan_id' => '5'],
            ['details_id' => '44', 'days' => '183', 'premium' => '444', 'plan_id' => '5'],
            ['details_id' => '45', 'days' => '365', 'premium' => '588', 'plan_id' => '5'],
            ['details_id' => '46', 'days' => '16-25', 'premium' => '40', 'plan_id' => '6'],
            ['details_id' => '47', 'days' => '26-45', 'premium' => '50', 'plan_id' => '6'],
            ['details_id' => '57', 'days' => '1-15', 'premium' => '30', 'plan_id' => '6'],
            ['details_id' => '58', 'days' => '1-15', 'premium' => '35', 'plan_id' => '7'],
            ['details_id' => '59', 'days' => '16-25', 'premium' => '50', 'plan_id' => '7'],
            ['details_id' => '60', 'days' => '26-45', 'premium' => '60', 'plan_id' => '7'],
            ['details_id' => '61', 'days' => '1-15', 'premium' => '45', 'plan_id' => '8'],
            ['details_id' => '62', 'days' => '16-25', 'premium' => '60', 'plan_id' => '8'],
            ['details_id' => '63', 'days' => '26-45', 'premium' => '75', 'plan_id' => '8']
        ];

        foreach ($bm_travel_plan_details as $plan_detail) {
            SchemaTasks::insert('travel_plan_details', [
                'days' => $plan_detail['days'],
                'premium' => $plan_detail['premium'],
                'plan_id' => $plan_detail['plan_id']
            ]);
        }
    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run() {
        // TODO: Implement run() method.
    }

}
