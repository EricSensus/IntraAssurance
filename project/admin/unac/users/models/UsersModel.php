<?php
namespace Jenga\MyProject\Users\Models;

use Jenga\App\Models\ORM;

class UsersModel extends ORM {
    
    public $table = 'users';
    
    public function check($username, $password) {   
        
        //check the user
        $login = $this->where('username', '=', $username)
                      ->where('password', '=', md5($password))
                ->first();
        
        if($this->count() == 1){
            
            return $login;
        }
        else{
            
            return FALSE;
        }
    }
    
    public function getUsers(){
        
        $users = $this->select(TABLE_PREFIX.'users.* ')
                    //    . TABLE_PREFIX.'user_profiles.id as profileid, '
                    //    . TABLE_PREFIX.'user_profiles.* ')
                
                    //    . TABLE_PREFIX.'insurer_agents.id as agentid, '
                    //    . TABLE_PREFIX.'insurer_agents.*')
                
                    //->join('user_profiles', 
                        //TABLE_PREFIX."users.user_profiles_id = ".TABLE_PREFIX."user_profiles.id")
                
                    //->join('insurer_agents', 
                    //    TABLE_PREFIX."users.insurer_agents_id = ".TABLE_PREFIX."insurer_agents.id")
                
                    ->show();
        
        foreach($users as $user){
            
            //get if agent
            if($user->insurer_agents_id == 0){
                $user->is_agent = false;
            }
            else{
                $user->is_agent = true;
            }
            
            $userslist[] = $user;
        }
        
        return $userslist;
    }
    
    public function getUserFromProfile($id, $searchby = 'user_profiles', $return = 'name'){
        
        if($return == 'name'){
            $this->select('name');
        }
        else{
            $this->select($return);
        }

        if($searchby == 'user_profiles'){
            $this->join('user_profiles', 
                    TABLE_PREFIX."users.user_profiles_id = ".TABLE_PREFIX."user_profiles.id")
                ->where(TABLE_PREFIX.'users.user_profiles_id','=',$id);
        }
        elseif($searchby == 'agents'){
            $this->join('insurer_agents', 
                    TABLE_PREFIX."users.insurer_agents_id = ".TABLE_PREFIX."insurer_agents.id")
                ->where(TABLE_PREFIX.'users.insurer_agents_id','=',$id);
        }
        
        $result = $this->first();
        
        return $result;
    }
    
    public function getAccessLevels($accesslevels_id){
        
        $alevels = $this->table('accesslevels');
        
        if(is_null($accesslevels_id)){
            
            $data = $alevels->show();
        }
        else{
            
            $arecord = $alevels->find($accesslevels_id);
            $data =  $arecord->data;
        }   
        
        return $data;
    }
}

