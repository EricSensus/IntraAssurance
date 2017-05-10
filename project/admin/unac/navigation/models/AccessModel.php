<?php
namespace Jenga\MyProject\Navigation\Models;

use Jenga\App\Models\ORM;

class AccessModel extends ORM{
    
    public $table = 'accesslevels';
    
    public function getHierachyFromAccessID($id,$get_subordinates = true){
        
        $level = $this->select('level')->where('id','=',$id)->first();
        
        if($get_subordinates == true)
            $operator = '>=';
        else
            $operator = '=';
        
        return $this->where('level',$operator,$level->level)->show();
    }
}