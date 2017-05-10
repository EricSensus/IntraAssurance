<?php

/*
 * This interface sets the mandatory functions to be used in every Jenga seeder
 */

namespace Jenga\App\Models;

interface SchemaInterface {
    
    /**
     * This would be used for creating the table and inserting the new columns
     */
    public function build();
    
    /**
     * This would be for inserting the initial data for the column
     */
    public function seed();
    
    /**
     * This would be for running more complex operations on the table
     */
    public function run();
}
