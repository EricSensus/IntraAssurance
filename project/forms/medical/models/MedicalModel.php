<?php
namespace Jenga\MyProject\Medical\Models;

use Jenga\App\Models\ORM;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MedicalModel
 *
 * @author developer
 */
class MedicalModel extends ORM{
    
    public function createMedicalPricingTable(){
        $schema = $this->schema;

        if(!$schema->hasTable('medical_pricing')){
            $schema->table('medical_pricing');

            $schema->column('id', [
                'int',
                'not null',
                'auto_increment'
            ])->primary('id');
            $schema->column('agerange_benefits', ['varchar(50)']);
            $schema->column('P1', ['int']);
            $schema->column('P2', ['int']);
            $schema->column('P3', ['int']);
            $schema->column('P4', ['int']);
            $schema->build();
        }
    }
}
