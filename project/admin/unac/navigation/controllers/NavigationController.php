<?php

namespace Jenga\MyProject\Navigation\Controllers;

use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Redirect;
use Jenga\App\Request\Session;
use Jenga\App\Views\Notifications;
use Jenga\App\Controllers\Controller;
use Jenga\MyProject\Elements;

use Jenga\MyProject\Navigation\Views\NavigationView;
use Jenga\MyProject\Navigation\Models\NavigationModel;
use MongoDB\BSON\Type;

/**
 * Class NavigationController 
 * 
 * @property-read NavigationModel $model
 * @property-read NavigationView $view
 * 
 * @package Jenga\MyProject\Notifications\Controllers
 */
class NavigationController extends Controller
{

    public function index()
    {

        if (is_null(Input::get('action')) && is_null(Input::post('action'))) {
            $action = 'show';
        } else {

            if (!is_null(Input::get('action')))
                $action = Input::get('action');

            elseif (!is_null(Input::post('action')))
                $action = Input::post('action');
        }

        $this->$action();
    }

    public function addItem()
    {

        $group = $this->model->findGroup(Input::get('groupid'));
        $dbitems = $this->model->getMenuFromGroupAlias($group->alias);

        foreach ($dbitems as $item) {
            $allitems[$item->linkid] = $item->linkname.' ['.$group->alias.']';
        }

        if (!Input::has('id')) {

            //get last order item
            $last = end($dbitems);
            $order = $last->linkorder + 1;

            $this->view->itemsForm($group, null, null, $allitems, $order);
        } else {

            $item = $this->model->find(Input::get('id'));
            $this->view->itemsForm($group, $item->id, $item, $allitems, $item->linkorder);
        }
    }

    public function saveItem()
    {

        $this->view->disable();

        $menuitem = $this->model->find(Input::post('id'));

        $menuitem->linkname = Input::post('name');
        $menuitem->linkalias = Input::post('alias');
        $menuitem->href = (strpos(Input::post('href'), '/') === 0 ? '{base}' . Input::post('href') : Input::post('href'));
        $menuitem->linkorder = Input::post('order');
        $menuitem->menu_groups_id = Input::post('groupid');
        $menuitem->parentid = Input::post('parent');
        $menuitem->published = Input::post('published');
        $menuitem->home = Input::post('home');
        $menuitem->params = Input::post('params');

        $menuitem->save();

        if ($menuitem->hasNoErrors()) {

            Redirect::withNotice('The menu item has been saved to ' . Input::post('group'), 'success')
                ->to('/admin/navigation/showitems/' . Input::post('groupid'));
        }
    }

    /**
     * Retrieves the corrent menu name based on the User AccessLevel
     */
    public function getMenusFromAccesslevel()
    {

        $id = $this->user()->acl;
        $menus = $this->model->table('menu_groups')->where('acl',$id)->get();

        $list = '';
        if(count($menus) > 1){
            foreach($menus as $menu){
                $list .= $menu->alias.',';
            }
        }
        elseif(count($menus) == 1){
            $list = $menus[0]->alias;
        }

        return $list;
    }

    public function saveProductItemRemotely(array $data)
    {

        $this->view->disable();

        $menu = $this->model;

        //get the store link
        $store = $this->model->where('linkalias', 'store')->first();

        //get products
        $products = $this->getStoreProducts($store);
        $last = end($products);

        $menu->linkname = $data['name'];
        $menu->linkalias = $data['alias'];
        $menu->href = (strpos($data['href'], '/') === 0 ? '{base}' . $data['href'] : $data['href']);
        $menu->linkorder = $last->order + 1;
        $menu->menu_groups_id = $store->menu_groups_id;
        $menu->parentid = $store->id;
        $menu->published = $data['published'];
        $menu->home = $data['home'];
        $menu->params = $last->params;

        $menu->save();

        if ($menu->hasNoErrors())
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Gets all products in store in link order
     * @param type $store
     */
    public function getStoreProducts($store)
    {

        $products = $this->model->where('parentid', $store->id)->orderBy('linkorder', 'DESC')->show();
        return $products;
    }

    /**
     * 
     * @acl\role customer
     */
    public function display($name, $template = null, $as_array = false)
    {
        $names = explode(',', $name);

        $namelist = [];
        foreach ($names as $name) {

            $menu = $this->model->getMenuFromGroupAlias($name);
            if($as_array)
                return $menu;

            if (strpos($name, '-') != false) {
                $name = str_replace('-', '_', $name);
                $namelist[] = $name;
            }

            $this->view->processMenu($menu, $name, $template);
        }

        $this->set('names', $namelist);
        $this->view->setViewPanel('menus');
    }

    public function getCrumbs($url)
    {

        $crumbs = explode("/", $_SERVER["REQUEST_URI"]);
        $count = 0;

        $breadcrumbs = '<ol class="breadcrumb">';

        for ($s = 0; $s <= count($crumbs); $s++) {

            $breadcrumbs .= '<li>' . ucfirst(str_replace([".php", "_"], ["", " "], $crumbs[$count]) . ' ') . '</li>';
            $count++;
        }

        $breadcrumbs .= '</ol>';

        $this->view->set('breadcrumbs', trim($breadcrumbs, ' / '));
        $this->view->setViewPanel('breadcrumbs');
    }

    /**
     * Returns URL based on sent alias
     *
     * @param type $alias
     * @param type $replacebase - If the site root path should be included in the URL
     * @param type $addajax - If the ajax keyword will be added to the URL
     * @return type
     */
    public function getUrl($alias, $replacebase = TRUE, $addajax = FALSE)
    {

        $results = $this->model->getUrlByAlias($alias);

        //add the ajax keyword
        if ($addajax !== FALSE) {
            $ajax = '/ajax';
        } else {
            $ajax = '';
        }

        if (strpos($results[0]->href, '{base}') !== FALSE) {

            //add ajax keyword after {base}
            $spliturl = explode('{base}', $results[0]->href);
            $ajaxurl = $ajax . $spliturl[1];
            $baseurl = '{base}' . $ajaxurl;

            //replace the {base} paceholder
            if ($replacebase == TRUE) {
                $url = str_replace('{base}', Url::base(), $baseurl);
            } else {
                $url = str_replace('{base}', '', $baseurl);
            }
        } else {
        print_r($menu_items);exit;
            $url = $ajax . $results[0]->href;
        }

        return $url;
    }

    public function show()
    {

        //get menu groups
        $groups = $this->model->menuGroups();

        foreach ($groups as $group) {
            
            //get items under group
            $items = $this->model->where('menu_groups_id', $group->id)->show();
            $group->itemcount = count($items);

            $grouplist[] = $group;
        }

        $this->view->displayNavGroup($grouplist);
    }

    public function showItems()
    {

        $id = Input::get('id');

        $links = $this->model->where('menu_groups_id', $id)->where('parentid', '0')->orderBy('linkorder', 'asc')->show();
        $group = $this->model->findGroup($id);

        $count = 1;
        foreach ($links as $link) {

            if ($link->parentid == 0) {

                $link->count = $count;
                $link->icon = '<strong>+</strong>';
                $link = $this->processLink($link);

                $linklist[] = $link;

                //process the children
                $childlinks = $this->model->where('menu_groups_id', $id)->where('parentid', $link->id)->orderBy('linkorder', 'asc')->show();

                if (count($childlinks) > 0) {

                    //add the children
                    foreach ($childlinks as $clink) {

                        $count++;

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

    public function processLink($link)
    {

        //process url
        if (strpos($link->href, '{base}') === (int)0) {
            $link->href = str_replace('{base}', '', $link->href);
        }

        //published
        if ($link->published == 1) {
            $link->published = '<div align="center">'
                . '<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/tick.png" width="25" />'
                . '</div>';
        } else {
            $link->published = '<div align="center">'
                . '<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/cross.png" width="25" />'
                . '</div>';
        }

        //home
        if ($link->home == 'yes') {
            $link->home = '<div align="center">'
                . '<a ' . Notifications::tooltip('This is the default homepage for the Group') . '>'
                . '<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/tick.png" width="25" />'
                . '</a>'
                . '</div>';
        } elseif ($link->home == 'no' || $link->home == '' || $link->home == ' ') {
            $link->home = '<div align="center">'
                . '<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/cross.png" width="25" />'
                . '</div>';
        }

        return $link;
    }

    public function editGroup()
    {
        
        $id = Input::get('id');
        $group = $this->model->findGroup($id);

        $this->view->groupAddEditView($group->data);
    }

    public function addGroup()
    {
        $this->view->groupAddEditView();
    }

    public function deleteItem($alias = null)
    {

        $this->view->disable();

        if (is_null($alias)) {

            $id = Input::get('id');
            $this->model->where('id', $id)->delete();

            Redirect::withNotice('The menu item(s) have been deleted', 'success')
                ->to(Url::route('/admin/navigation/{action}/{id}', ['action' => 'showitems', 'id' => Input::get('groupid')]));
        } else {
            $this->model->where('linkalias', $alias)->delete();
        }
    }

    public function getMenuGroupIdByAlias($alias)
    {
        $menu_group = $this->model->table('menu_groups')->where('alias', $alias)->first();
        return $menu_group->id;
    }

    public function frontMenu(){
        $menuname = $this->getMenusFromAccesslevel();
        $menu_items = $this->display($menuname, null, true);

        $front_menu = array();

        if(count($menu_items)){
            foreach ($menu_items as $item){
                if($item->parentid == 0){
                    $child_items = $this->model->getChildMenusByParentId($item->linkid);
                    $front_menu[] = [
                        'linkname' => $item->linkname,
                        'href' => $item->href,
                        'children' => $child_items
                    ];
                }
            }
        }

        return $this->view->showFrontMenu($front_menu);
    }

    /**
     * Gets the default link for each menu group by sent acl
     * @param type $acl
     * @return mixed
     */
    public function getDefaultLinkByAcl($acl) {
        
        $url = $this->model->getDefaultLinkByAcl($acl);
        return $this->view->processHref($url->href);
    }

    /**
     * Get link by alias and acl
     *
     * @param type $alias
     * @param type $acl
     * @return mixed
     */
    public function getLinkByAliasAcl($alias, $acl){
        
        $url = $this->model->getLinkByAliasAcl($alias, $acl);
        return $this->view->processHref($url->href);
    }
}
