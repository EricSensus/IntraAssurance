<?php

namespace Jenga\MyProject\Accident\Schema;

use Jenga\App\Models\SchemaInterface;
use Jenga\App\Models\SchemaTasks;

class AccidentPricing implements SchemaInterface {

    public $table = 'personal_cover_pricing';

    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build() {
        
        $schema = SchemaTasks::create($this->table);
        $schema->table('personal_cover_pricing');
        
        $schema->column('id', ['INT', 'NOT NULL', 'AUTO_INCREMENT'])->primary('id');
        $schema->column('age_bracket', ['VARCHAR(50)', 'NOT NULL']);
        $schema->column('class', ['VARCHAR(50)', 'NOT NULL']);
        $schema->column('band', ['VARCHAR(50)', 'NOT NULL']);
        $schema->column('premium', ['double(10,2)', 'NOT NULL']);
        
        return $schema->build();
    }

    /**
     * This would be for inserting the initial data for the column
     */
    public function seed() {

        $bm_personal_cover_pricing = [
            ['id' => '2', 'age_bracket' => 'A1', 'class' => 'C1', 'band' => 'B1', 'premium' => '2500.00'],
            ['id' => '3', 'age_bracket' => 'A1', 'class' => 'C1', 'band' => 'B2', 'premium' => '3600.00'],
            ['id' => '4', 'age_bracket' => 'A1', 'class' => 'C1', 'band' => 'B3', 'premium' => '4300.00'],
            ['id' => '5', 'age_bracket' => 'A1', 'class' => 'C1', 'band' => 'B4', 'premium' => '7530.00'],
            ['id' => '6', 'age_bracket' => 'A1', 'class' => 'C1', 'band' => 'B5', 'premium' => '14100.00'],
            ['id' => '7', 'age_bracket' => 'A1', 'class' => 'C1', 'band' => 'B6', 'premium' => '28230.00'],
            ['id' => '8', 'age_bracket' => 'A1', 'class' => 'C1', 'band' => 'B1', 'premium' => '37300.00'],
            ['id' => '9', 'age_bracket' => 'A1', 'class' => 'C2', 'band' => 'B1', 'premium' => '2500.00'],
            ['id' => '10', 'age_bracket' => 'A1', 'class' => 'C2', 'band' => 'B2', 'premium' => '3600.00'],
            ['id' => '11', 'age_bracket' => 'A1', 'class' => 'C2', 'band' => 'B3', 'premium' => '4300.00'],
            ['id' => '12', 'age_bracket' => 'A1', 'class' => 'C2', 'band' => 'B4', 'premium' => '7530.00'],
            ['id' => '13', 'age_bracket' => 'A1', 'class' => 'C2', 'band' => 'B5', 'premium' => '14100.00'],
            ['id' => '14', 'age_bracket' => 'A1', 'class' => 'C2', 'band' => 'B6', 'premium' => '28230.00'],
            ['id' => '15', 'age_bracket' => 'A1', 'class' => 'C2', 'band' => 'B7', 'premium' => '37300.00'],
            ['id' => '16', 'age_bracket' => 'A2', 'class' => 'C1', 'band' => 'B1', 'premium' => '3300.00'],
            ['id' => '17', 'age_bracket' => 'A2', 'class' => 'C1', 'band' => 'B2', 'premium' => '5100.00'],
            ['id' => '18', 'age_bracket' => 'A2', 'class' => 'C1', 'band' => 'B3', 'premium' => '6300.00'],
            ['id' => '19', 'age_bracket' => 'A2', 'class' => 'C1', 'band' => 'B4', 'premium' => '10530.00'],
            ['id' => '20', 'age_bracket' => 'A2', 'class' => 'C1', 'band' => 'B5', 'premium' => '18100.00'],
            ['id' => '21', 'age_bracket' => 'A2', 'class' => 'C1', 'band' => 'B6', 'premium' => '33230.00'],
            ['id' => '22', 'age_bracket' => 'A2', 'class' => 'C1', 'band' => 'B7', 'premium' => '43300.00'],
            ['id' => '23', 'age_bracket' => 'A2', 'class' => 'C2', 'band' => 'B1', 'premium' => '3750.00'],
            ['id' => '24', 'age_bracket' => 'A2', 'class' => 'C2', 'band' => 'B2', 'premium' => '5900.00'],
            ['id' => '25', 'age_bracket' => 'A2', 'class' => 'C2', 'band' => 'B3', 'premium' => '7130.00'],
            ['id' => '26', 'age_bracket' => 'A2', 'class' => 'C2', 'band' => 'B4', 'premium' => '11880.00'],
            ['id' => '27', 'age_bracket' => 'A2', 'class' => 'C2', 'band' => 'B5', 'premium' => '20310.00'],
            ['id' => '28', 'age_bracket' => 'A2', 'class' => 'C2', 'band' => 'B6', 'premium' => '37050.00'],
            ['id' => '29', 'age_bracket' => 'A2', 'class' => 'C2', 'band' => 'B7', 'premium' => '48230.00'],
            ['id' => '30', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B1', 'premium' => '3650.00'],
            ['id' => '31', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B2', 'premium' => '5650.00'],
            ['id' => '32', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B3', 'premium' => '6930.00'],
            ['id' => '33', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B4', 'premium' => '11580.00'],
            ['id' => '34', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B5', 'premium' => '19910.00'],
            ['id' => '35', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B6', 'premium' => '36550.00'],
            ['id' => '36', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B7', 'premium' => '47630.00'],
            ['id' => '37', 'age_bracket' => 'A3', 'class' => 'C2', 'band' => 'B1', 'premium' => '4300.00'],
            ['id' => '38', 'age_bracket' => 'A3', 'class' => 'C2', 'band' => 'B2', 'premium' => '6500.00'],
            ['id' => '39', 'age_bracket' => 'A3', 'class' => 'C2', 'band' => 'B3', 'premium' => '7840.00'],
            ['id' => '40', 'age_bracket' => 'A3', 'class' => 'C2', 'band' => 'B4', 'premium' => '13070.00'],
            ['id' => '41', 'age_bracket' => 'A3', 'class' => 'C2', 'band' => 'B5', 'premium' => '22340.00'],
            ['id' => '42', 'age_bracket' => 'A3', 'class' => 'C1', 'band' => 'B6', 'premium' => '40750.00'],
            ['id' => '43', 'age_bracket' => 'A3', 'class' => 'C2', 'band' => 'B7', 'premium' => '53050.00']
        ];
        
        SchemaTasks::insert($this->table, $bm_personal_cover_pricing);
    }

    /**
     * This would be for running more complex operations on the table
     */
    public function run() {

    }

}
