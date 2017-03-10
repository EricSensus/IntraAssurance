<?php

/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */

namespace Jenga\MyProject\Motor\Models;

use Jenga\App\Models\ORM;

/**
 * Class MotorModel
 */
class MotorModel extends ORM
{
    /*

        public function createUsersTable()
        {
            $schema = $this->schema;
            if (!$schema->hasTable('users')) {
                $schema->table('users');
                $schema->column('userid', ['int', 'not null', 'auto_increment'])->primary('userid');
                $schema->column('username', ['varchar(200)', 'not null']);
                $schema->column('password', ['text', 'not null']);
                $schema->column('usertype', ['text', 'not null']);
                $schema->column('sourceid', ['int', 'not null']);
                $schema->column('enabled', ['boolean', 'default 0']);
                $schema->column('emailconfirmed', ['boolean', 'default 0']);
                $schema->build();
            }
        }*/


}
