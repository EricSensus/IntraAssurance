<?php
namespace Jenga\App\Models\Repositories;

/**
 * Converts the element model into a repository
 * @author Stanley Ngumo
 */

interface RepositoryInterface {
    
    /**
     * Register the object to be returned
     * @param string $entity
     */
    public function register();
}
