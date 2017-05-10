<?php
namespace Jenga\App\Models\Repositories;

/**
 * This allows the user to sent function which should be handled by the ORM 
 * before the object is returned back to the element model
 *
 * @author sngumo
 */

interface EntityInterface {
    public function handle();
}
