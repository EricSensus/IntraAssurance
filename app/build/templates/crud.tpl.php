<?php
namespace Jenga\App\Controllers;

interface CrudInterface{
    
    public function create();
    public function index();
    public function update();
    public function delete();
}
