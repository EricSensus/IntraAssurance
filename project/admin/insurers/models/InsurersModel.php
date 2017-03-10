<?php
namespace Jenga\MyProject\Insurers\Models;

use Jenga\App\Models\ORM;

class InsurersModel extends ORM {
    
    public $table = 'insurers';
    
    public function getInsurer($id){
        
        $data = $this->select('name')
                ->where('id','=',$id)
                ->first();
        
        return ['id'=>$id, 'name' => $data->name];
    }
    
    public function getCommissions(){
        
        return $this->model->table('commissions')->show();
    }
}