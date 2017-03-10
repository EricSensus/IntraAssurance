<?php
namespace Jenga\MyProject\Travel\Models;

use Jenga\App\Models\Repositories\EntityInterface;
use Jenga\MyProject\Elements;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Travel
 *
 * @author developer
 */
class Travel implements EntityInterface{
    public $motor;

    public function __construct(){
        // attach the motor element
        $this->motor = Elements::call('Motor/MotorController');
    }

    public function checkIfRecordExists($table, $column, $value){
        return $this->motor->checkIfRecordExists($table, $column, $value);
    }

    public function handle() {
        return $this;
    }
}
