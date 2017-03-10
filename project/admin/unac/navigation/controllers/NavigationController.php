<?php
namespace Jenga\MyProject\Navigation\Controllers;

use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Request\Session;
use Jenga\App\Views\Notifications;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Elements;

class NavigationController extends Controller {
    
    public function index(){
        
        if(is_null(Input::get('action')) && is_null(Input::post('action'))){            
            $action = 'show';
        }
        else{
            
            if(!is_null(Input::get('action')))                
                $action = Input::get('action');
            
            elseif(!is_null(Input::post('action')))                
                $action = Input::post ('action');
        }
        
        $this->$action();
    }
    
    public function display($params){
        
        $names = explode(',', $params['name']);
        
        foreach($names as $name){
            
            $menu = $this->model->getMenuFromGroupAlias($name);    
            
            if(strpos($name, '-') != false){
                $name = str_replace('-', '_', $name);
            }
            
            $this->view->processMenu($menu, $name, $params['template']);
        }
        
        $this->view->setViewPanel('menus');
    }    
    
    public function getCrumbs($url){
        
        $crumbs = explode("/",$_SERVER["REQUEST_URI"]);
        $count = 0;
        
        $breadcrumbs = '<ol class="breadcrumb">';
        
        for($s=0; $s <= count($crumbs); $s++){
            
            $breadcrumbs .= '<li>'.ucfirst(str_replace(array(".php","_"),array(""," "),$crumbs[$count]) . ' ').'</li>';
            $count++;
        }
        
        $breadcrumbs .= '</ol>';
        
        $this->view->set('breadcrumbs', trim($breadcrumbs, ' / '));
        $this->view->setViewPanel('breadcrumbs');
    }
    
    public function getUrl($alias){
        
        $results = $this->model->getUrlByAlias($alias);        
        return $results[0]->href;
    }
    
    public function show(){
        
        //get menu groups
        $groups = $this->model->menuGroups();
        
        foreach($groups as $group){
            
            //get the ACL name
            $level = Elements::call('Navigation/AccessController')->model->find($group->accesslevels_id);
            $group->acl = $level->name;
            
            //get items under group
            $items = $this->model->where('menu_groups_id',$group->id)->show();
            $group->itemcount = count($items);
            
            $grouplist[] = $group;
        }
        
        $this->view->displayNavGroup($grouplist);
    }
    
    public function showItems(){
        
        $id = Input::get('id');
        
        $links = $this->model->where('menu_groups_id',$id)->where('parentid','0')->orderBy('linkorder','asc')->show();
        $group = $this->model->findGroup($id);
        
        $count = 1;
        foreach($links as $link){
            
            if($link->parentid == 0){
                
                $link->count = $count;
                $link->icon = '<strong>+</strong>';
                $link = $this->processLink($link);
                
                $linklist[] = $link;
                
                //process the children
                $childlinks = $this->model->where('parentid',$link->id)->orderBy('linkorder','asc')->show();
                
                if(count($childlinks) > 0){
                    
                    //add the children
                    foreach ($childlinks as $clink) {

                        $count ++;
                        
                        $clink->icon = '&nbsp;&nbsp;&nbsp;&nbsp; |_';
                        $clink->count = $count;
                        $childlink = $this->processLink($clink);
                        
                        $linklist[] = $childlink;
                    }

                    //$linklist = array_merge($linklist, $childlink);
                    unset($childlink);
                }
                
                $count++;
            }
        }
        
        $url = $this->getUrl('navigation');
        
        $this->view->set('navcount', $count);
        $this->view->set('groupname', $group->title);
        
        $this->view->itemsTable($linklist, $url, $id); 
    }
    
    public function processLink($link) {
        
        //process url
        if(strpos($link->href, '{base}') === (int)0){
            $link->href = str_replace('{base}', '', $link->href);
        }
        
        //published
        if($link->published == 1){
            $link->published = '<div align="center">'
                    . '<img src="'.RELATIVE_PROJECT_PATH .'/'. $this->attrs()['path']. '/assets/images/tick.png" width="25" />'
                    . '</div>';
        }
        else{
            $link->published = '<div align="center">'
                    . '<img src="'.RELATIVE_PROJECT_PATH .'/'. $this->attrs()['path']. '/assets/images/cross.png" width="25" />'
                    . '</div>';
        }

        //home
        if($link->home == 'yes'){
            $link->home = '<div align="center">'
                    . '<a '.Notifications::tooltip('This is the default homepage for the Group').'>'
                    . '<img src="'.RELATIVE_PROJECT_PATH .'/'. $this->attrs()['path']. '/assets/images/tick.png" width="25" />'
                    . '</a>'
                    . '</div>';
        }
        elseif($link->home == 'no' || $link->home == '' || $link->home == ' '){
            $link->home = '<div align="center">'
                    . '<img src="'.RELATIVE_PROJECT_PATH .'/'. $this->attrs()['path']. '/assets/images/cross.png" width="25" />'
                    . '</div>';
        }
        
        return $link;
    }
    
    public function editGroup(){
        
        $id = Input::get('id');
        $group = $this->model->findGroup($id);
        
        $this->view->groupAddEditView($group->data);
    }
    
    public function addGroup(){        
        $this->view->groupAddEditView();
    }
    
    public function saveGroup(){
        
        if(Input::has('id'))
            $group = $this->model->table('menu_groups')->find(Input::post('id'));
        else
            $group = $this->model->table('menu_groups');
        
        $group->title = Input::post('title');
        $group->alias = Input::post('alias');
        $group->description = Input::post('description');
        $group->accesslevels_id = Input::post('acl');
        $group->permissions = '';
        
        $group->save();
        
        if($group->hasNoErrors()){
            
            Redirect::withNotice('Group saved')
                    ->to(Url::base().'/admin/navigation/show');
        }
    }
    
    public function deleteGroup($id){
        
        $this->model->table('menu_groups')->where('id', $id)->delete();
        
        Redirect::withNotice('The menu group has been deleted', 'success')
                ->to(Url::base().'/admin/navigation/show');
    }
    
    public function addItem() {
        
        $group = $this->model->findGroup(Input::get('groupid'));
        $dbitems = $this->model->getMenuFromGroupAlias($group->alias);
        
        foreach ($dbitems as $item) {
            $allitems[$item->linkid] = $item->linkname;
        }
        
        if(!Input::has('id')){
            
            //get last order item
            $last = end($dbitems);
            $order = $last->linkorder+1;

            $this->view->itemsForm($group, null, null, $allitems, $order);
        }
        else{
            
            $item = $this->model->find(Input::get('id'));
            $this->view->itemsForm($group, $item->id, $item, $allitems, $item->linkorder);
        }
    }
    
    public function saveItem(){
        
        $this->view->disable();
        
        $menuitem = $this->model->find(Input::post('id'));
        
        $menuitem->linkname = Input::post('name');
        $menuitem->linkalias = Input::post('alias');
        $menuitem->href = (strpos(Input::post('href'), '/') === 0 ? '{base}'.Input::post('href') : Input::post('href'));
        $menuitem->linkorder = Input::post('order');
        $menuitem->menu_groups_id = Input::post('groupid');
        $menuitem->parentid = Input::post('parent');
        $menuitem->published = Input::post('published');
        $menuitem->home = Input::post('home');
        $menuitem->params = Input::post('params');
        
        $menuitem->save();
        
        if($menuitem->hasNoErrors()){
            
            Redirect::withNotice('The menu item has been saved to '.Input::post('group'),'success')
                    ->to('/admin/navigation/showitems/'.Input::post('groupid'));
        }
    }
    
    public function deleteItem($alias = null) {
        
        $this->view->disable();
        
        if(is_null($alias)){
            
            $id = Input::get('id');
            $this->model->where('id',$id)->delete();

            Redirect::withNotice('The menu item(s) have been deleted', 'success')
                    ->to(Url::route('/admin/{element}/{action}', ['element'=>'navigation','action'=>'showitems','id'=>Input::get('groupid')]));
        }
        else{
            $this->model->where('linkalias',$alias)->delete();
        }
    }
}