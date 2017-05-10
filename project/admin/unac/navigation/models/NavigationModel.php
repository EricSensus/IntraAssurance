<?php
namespace Jenga\MyProject\Navigation\Models;

use Jenga\App\Models\ORM;
use Jenga\MyProject\Elements;

class NavigationModel extends ORM{
    
    public $table = 'menus';
    
    /**
     * Returns the full menu based on the sent menu group alias
     * @param type $alias
     */
    public function getMenuFromGroupAlias($alias){
            
        $menus = $this->select(TABLE_PREFIX.'menus.*, '
                        . TABLE_PREFIX.'menus.id as linkid, '
                        . TABLE_PREFIX.'menu_groups.id as groupid, '
                        . TABLE_PREFIX.'menu_groups.*')
                ->join('menu_groups', 
                        TABLE_PREFIX.'menu_groups.id = '.TABLE_PREFIX.'menus.menu_groups_id')
                ->where(TABLE_PREFIX.'menu_groups.alias', '=', $alias)
                ->where(TABLE_PREFIX.'menus.published', '=', '1')
                ->orderBy('linkorder', 'ASC')
                ->show();
        
        return $menus;
    }
    
    public function getMenuFromAccessID($user_access){
        
        $levels = Elements::call('Navigation/AccessController')->getHierachyFromAccessID($user_access);
        
        foreach($levels as $level){
            
            $menu = $this->join('menu_groups', 
                            TABLE_PREFIX.'menu_groups.id = '.TABLE_PREFIX.'menus.menu_groups_id')
                    ->where(TABLE_PREFIX.'menus.published', '=', '1')
                    ->where(TABLE_PREFIX.'menu_groups.accesslevels_id', '=', $level->id)
                    ->orderBy('linkorder', 'ASC')
                    ->show();
            
            if(count($menu)>=1){                
                foreach($menu as $link){                    
                    $menus[] = $link;
                }
            }
        }
        
        return $menus;
    }
    
    public function getUrlByAlias($alias){
        
        $results = $this->where('linkalias', '=', $alias)->show();
        return $results;
    }
    
    /**
     * Return menu groups
     */
    public function menuGroups($id = null){       
        
        if(is_null($id))
            return $this->table('menu_groups')->show();
        else
            return $this->table ('menu_groups')->where ('id', $id)->show();
    }
    
    public function findGroup($id) {        
        return $this->table('menu_groups')->find($id);
    }
}