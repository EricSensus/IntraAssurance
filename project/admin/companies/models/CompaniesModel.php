<?php
namespace Jenga\MyProject\Companies\Models;

use Jenga\App\Models\ORM;

class CompaniesModel extends ORM {
    
    public $table = 'companies';
    
    public function getCompanies(){
        
        return $this->show();
    }
}