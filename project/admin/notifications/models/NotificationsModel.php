<?php
namespace Jenga\MyProject\Notifications\Models;

use Jenga\App\Models\ORM;

class NotificationsModel extends ORM {

    public $table = 'notifications';

    /**
     * Get the notices based on acl and user id
     * 
     * @param type $acl
     * @param type $userid
     */
    public function getNotices($acl, $userid = ''){ 
        
        //get the specific user notices
        if($userid != 0){
            $specific =  $this->where('acl','LIKE','%'.$acl.'%')
                                ->where('userid', $userid)
                                ->orderBy('created_at', 'DESC')
                                ->get();
        }
        
        //get also the general notices
        $general =  $this->where('acl','LIKE','%'.$acl.'%')
                            ->where('userid', '')
                            ->orderBy('created_at', 'DESC')
                            ->get();
        
        $notices = array_merge($general, $specific);
        
        return $notices;
    }
}