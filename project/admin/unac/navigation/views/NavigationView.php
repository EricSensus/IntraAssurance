<?php
namespace Jenga\MyProject\Navigation\Views;

use Jenga\App\Views\View;
use Jenga\App\Request\Url;
use Jenga\App\Html\Generate;
use Jenga\App\Request\Input;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class NavigationView extends View {

    public function processMenu($menu, $menu_name, $template ){

        $fullmenu = '<nav class="navbar" role="navigation">';

        $fullmenu .= '<button type="button" '
                . 'class="navbar-toggle"'
                . ' data-toggle="collapse" data-target="#mainMenu">'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '<span class="icon-bar"></span>'
                . '</button>';

        $fullmenu .= '<div class="collapse navbar-collapse">';
            $fullmenu .= '<ul class="sm navbar-nav" id="'.$menu_name.'">';

            foreach($menu as $link){

                //$name = strtolower($link->linkname);
                $alias = strtolower($link->linkalias);
                $params = json_decode($link->params);
                
                if($link->parentid == 0){

                    $children = $this->processChildren($menu, $link->linkid, $menu_name, $template);

                    $fullmenu .= '<li '.($children['present'] == true ? 'class="has-child"' : '').'>';
                    $fullmenu .= '<a href="'.$this->processHref($link->href).'" class="navitem">';

                    $imgpath = TEMPLATE_URL.$template.'/images/'.$alias.'_navicon.png';

                    if($params->icopath != ''){

                        $icopath = ltrim($params->icopath, '/');

                        $fullmenu .= '<span class="img">';
                            $fullmenu .= '<img src="'.TEMPLATE_URL.$template.'/'.$icopath.'" />';
                        $fullmenu .= '</span>';
                    }
                    elseif($params->icoclass != '' && $params->icopath == ''){
                        
                        //$icopath = ltrim($params->icopath, '/');                        
                        $fullmenu .= '<span class="icon">'
                                        . '<i class="'.$params->icoclass.'"></i>'
                                    . '</span>';
                    }
                    elseif($params->icopath == '' && $this->_checkImageFile($imgpath)){

                        $fullmenu .= '<span class="img">';
                            $fullmenu .= '<img src="'.TEMPLATE_URL.$template.'/images/'.$alias.'_navicon.png" />';
                        $fullmenu .= '</span>';
                    }
                    

                    $fullmenu .= '<span class="text">'. strtoupper($link->linkname).'</span>'
                                .'</a>';
                    $fullmenu .= $children['menu'];
                    $fullmenu .= '</li>';
                }

                unset($children);
            }

            $fullmenu .= '</ul>';
            $fullmenu .= '</div>';
        $fullmenu .= '</nav>';

        if($fullmenu !== ''){
            $this->set($menu_name, $fullmenu);
        }
    }

    public function processHref($href) {

        if(strpos($href, '{base}') == 0){
            $link = str_replace('{base}', SITE_PATH, $href);
        }

        return $link;
    }

    public function processChildren($links, $parentid, $menu_name, $template){

        $submenu_present = false;

        foreach($links as $link){

            if($link->parentid == $parentid){

                $submenus[] = $link;
                $submenu_present = true;
            }
        }

        if(!is_null($submenus)){

            $submenu = '<ul class="dropdown-menu" id="'.$menu_name.'">';

            foreach($submenus as $link){

                $params = json_decode($link->params);
                $alias = strtolower($link->linkalias);

                //recursive to check children of children
                $children = $this->processChildren($submenus, $link->linkid, $menu_name, $template);

                $submenu .= '<li '.($children['present'] == true ? 'class="has-child"' : '').'>'
                        . '<a href="'.$this->processHref($link->href).'" class="navitem">';

                $imgpath = TEMPLATE_URL.$template.'/images/'.$alias.'_navicon.png';

                if($params->icopath != ''){

                    $icopath = ltrim($params->icopath, '/');

                    $submenu .= '<span class="img">';
                        $submenu .= '<img src="'.TEMPLATE_URL.$template.'/'.$icopath.'" />';
                    $submenu .= '</span>';
                }
                elseif($params->icoclass != '' && $params->icopath == ''){

                    //$icopath = ltrim($params->icopath, '/');                        
                    $submenu .= '<i class="'.$params->icoclass.'"></i>';
                }
                elseif($params->icopath == '' && $this->_checkImageFile($imgpath)){

                    $submenu .= '<span class="img">';
                        $submenu .= '<img src="'.TEMPLATE_URL.$template.'/images/'.$alias.'_navicon.png" />';
                    $submenu .= '</span>';
                }

                $submenu .= '<span class="text">'. strtoupper($link->linkname).'</span>'
                            .'</a>'
                            . $children['menu']
                        . '</li>';
            }

            $submenu .= '</ul>';
        }

        $subholder['present'] = $submenu_present;
        $subholder['menu'] = $submenu;

        return $subholder;
    }

    public function displayNavGroup($list){

        $count = count($list);
        $columns = ['Title',
                    'Alias',
                    '<a '.Notifications::tooltip('Access Control Level').'>ACL</a>',
                    '<a '.Notifications::tooltip('These are the menu links under each group').'>Items in Group</a>',
                    'Description',
                    ''];

        $rows = ['{{<a data-toggle="modal" data-backdrop="static" data-target="#editgroup" href="'.Url::base().'/ajax/admin/navigation/editgroup/{id}">{title}</a>}}',
                '{alias}',
                '{acl}',
                '{{<a href="'.Url::route('/admin/navigation/{action}/{id}',['action'=>'showitems']).'{id}">{itemcount}</a>}}',
                '{description}',
                '{{<a class="smallicon" data-confirm="{title} will be deleted. Are you sure?" href="'.Url::base().'/ajax/admin/navigation/deletegroup/{id}" >'
                    . '<img '.Notifications::tooltip('Delete {title}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'
                ];

        $dom = '<"top">rt<"bottom"p><"clear">';
        $ordering = ['Title' => 'asc'];

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/',
            'tools' => [
                        'add' => [
                                    'path' => '/admin/navigation/addgroup',
                                    'type' => 'modal',
                                    'settings' => [
                                        'id' => 'editgroup',
                                        'data-target' => '#editgroup',
                                        'data-backdrop' => 'static'
                                    ]
                                ],
                        'delete' => [
                                        'path' => '/admin/navigation/deletegroup',
                                        'using' => ['{id}'=>'{title}']
                                    ]
                        ]
                ];

        $navgrouptable = $this->_mainTable('navgroups_table', $count, $columns, $rows, $list, $ordering, $dom, $tools);

        //add the logins modal
        $groupmodal = Overlays::Modal(['id'=>'editgroup']);
        $this->set('groupmodal', $groupmodal);

        $this->set('navgroups', $navgrouptable);
        $this->set('navcount', count($list));
    }

    private function _mainTable($name, $count, array $columns, array $rows, $source, $ordering, $dom, $tools){

        $schematic = [
            'table' => [
                'width' => '100%',
                'class' => 'display table table-striped',
                'border' => 0,
                'cellpadding' => 5
            ],
            'dom' => $dom,
            'columns' => $columns,
            'ordering' => $ordering,
            'column_attributes' => [
                'default' => [
                    'align' => 'center'
                ],
                'header_row' => [
                    'class' => 'header'
                ]
            ],
            'row_count' => $count,
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $source
            ],
            'row_variables' => $rows,
            'row_attributes' => [
                'default' => [
                    'align' => 'left'
                ],
                'odd_row' => [
                    'class' => 'odd'
                ],
                'even_row' => [
                    'class' => 'even'
                ]
            ],
            'cell_attributes' => [
                'default' => [
                ]
            ]
        ];

        $table = Generate::Table($name,$schematic);

        if(Input::post('printer')){
            $table->printOutput();
        }
        else{
            $table->buildTools($tools); //->assignPanel('search');
            $maintable = $table->render(TRUE);

            return $maintable;
        }
    }

    private function _checkImageFile($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if(curl_exec($ch)!==FALSE){
            return true;
        }
        else{
            return false;
        }
    }

    public function groupAddEditView($group = null) {

        $levels = $this->_getAccessLevels();

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/navigation/savegroup',
            'controls' => [
                '{id}' => ['hidden','id',$group->id],
                'Title' => ['text','title',$group->title],
                'Alias' => ['text','alias',$group->alias],
                'Description' => ['textarea','description',$group->description],
                'Group ACL' => ['select','acl', $group->accesslevels_id, $levels]
            ],
            'validation' => [
                'title' => [
                    'required' => 'Please enter Group Title'
                ],
                'acl' => [
                    'required' => 'Enter Group Access Level'
                ]
            ]
        ];

        $groupform = Generate::Form('addgroupform', $schematic)->render('horizontal', TRUE);

        $modalsettings = [
            'id' => 'addtaskmodal',
            'formid' => 'addgroupform',
            'role' => 'dialog',
            'title' => 'Add Edit Menu Group',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Menu Group' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addform = Overlays::ModalDialog($modalsettings, $groupform);
        $this->set('addgroupform',$addform);

        $this->setViewPanel('navigation'.DS.'groupform');
    }

    private function _getAccessLevels(){

        $levels = Elements::call('Navigation/AccessController')->model->format('array')->show();

        foreach($levels as $level){
            $list[$level['id']] = $level['name'];
        }

        return $list;
    }

    public function itemsTable($items, $destination, $menuid = null){

        $count = count($items);
        $columns = ['No','','Name','Alias','Link','Order','Published','Default',''];

        $rows = ['{count}',
                '{icon}',
                '{{<a data-toggle="modal" data-backdrop="static" data-target="#editgroup" '
                    . 'href="'.Url::route('/admin/{element}/{action}',
                            ['element'=>'navigation','action'=>'additem'])
                            .'/{menu_groups_id}/{id}">{linkname}</a>}}',
                '{linkalias}',
                '{href}',
                '{linkorder}',
                '{published}',
                '{home}',
                '{{<a class="smallicon" data-confirm="{linkname} will be deleted. Are you sure?" href="'.Url::base().'/ajax/admin/navigation/deleteitem/{id}/{menu_groups_id}" >'
                    . '<img '.Notifications::tooltip('Delete {linkname}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'
                ];

        $dom = '<"top">rt<"bottom"p><"clear">';
        $ordering =['No' => 'asc'];

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/',
            'tools' => [
                        'add' => [
                                    'path' => '/admin/navigation/additem/'.$menuid,
                                    'type' => 'modal',
                                    'settings' => [
                                        'id' => 'editgroup',
                                        'data-target' => '#editgroup',
                                        'data-backdrop' => 'static'
                                    ]
                                ],
                        'cancel' => [
                                        'path' => $destination
                                    ]
                        ]
                ];

        $navgrouptable = $this->_mainTable('navgroups_table', $count, $columns, $rows, $items, $ordering, $dom, $tools);

        //add the logins modal
        $groupmodal = Overlays::Modal(['id'=>'editgroup']);
        $groupmodal .= Overlays::confirm();

        $this->set('groupmodal', $groupmodal);

        $this->set('navgroups', $navgrouptable);
        $this->set('navcount', count($items));

        $this->setViewPanel('navigation'.DS.'menuitems');
    }

    public function itemsForm($group, $id, $item = null, array $allitems = [], $order = null) {

        //parse href
        if(!is_null($item->href)){

            if(strpos($item->href,'/') === 0 || strpos($item->href,'{base}') === 0){
                $href = '/'.ltrim(str_replace('{base}', '', $item->href),'/');
            }
            else{
                $href = $item->href;
            }
        }

        $schematic = [
            'preventjQuery' => TRUE,
            'css' => FALSE,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'method' => 'POST',
            'action' => '/ajax/admin/navigation/saveitem/',
            'controls' => [
                'id' => ['hidden','id',$id],
                'groupid' => ['hidden','groupid', $group->id],
                'Menu Item Name *' => ['text','name',$item->linkname,['class'=>'form-control','required'=>'']],
                'Link URL *' => ['text','href',$href,['class'=>'form-control','required'=>'']],
                'Menu Item Alias *' => ['text','alias',$item->linkalias,['class'=>'form-control','required'=>'']],
                'Parent Item' => ['select','parent',$item->parentid, $allitems,['class'=>'form-control']],
                'Published' => ['select','published',(is_null($item->published) ?: '1'), ['1'=>'Yes','0'=>'No'], ['class'=>'form-control']],
                'Default' => ['select','home',(!is_null($item->home) ? $item->home : 'no'), ['yes'=>'Yes','no'=>'No'], ['class'=>'form-control']],
                'Menu Group' => ['text','group',$group->title,['class'=>'form-control','readonly'=>'readonly']],
                'Menu Order' => ['text','order',$order,['class'=>'form-control']],
                'Extra Parameters' => ['textarea','params',$item->params,['class'=>'form-control']]
            ],
            'map' => [1,2,1,2,2,1]
        ];

        $eform = Generate::Form('customereditform', $schematic)
                            ->render(['orientation'=>'vertical','columns'=>'col-md-4,col-md-8'],TRUE);

        $modal_settings = [
            'id' => 'customermodal',
            'formid' => 'customereditform',
            'role' => 'dialog',
            'title' => 'Add / Edit Menu Item in '.$group->title,
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Menu Item' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'savebutton'
                ]
            ]
        ];

        $editform = Overlays::ModalDialog($modal_settings, $eform);

        $this->set('editform',$editform);
        $this->setViewPanel('navigation'. DS .'menuitem-edit');
    }

    public function showFrontMenu($menu_items = array()){
        $li = '';

        if(count($menu_items)){
            foreach ($menu_items as $item){
                $class = (count($item['children'])) ? 'dropdown-submenu' : '';
                $li .= '<li class="'. $class .'"><a href="' . $this->processHref($item['href']) . '">' . $item['linkname'] . '</a>';

                $li .= $this->getChildMenuItems($item['children']);

                $li .= '</li>';
            }
        }
        return $li;
    }

    public function getChildMenuItems($children = array()){
        if(count($children)){
            $li = '<ul class="dropdown-menu">';

            foreach ($children as $child) {
                $li .= '<li><a href="'. $this->processHref($child->href) .'">' . $child->linkname . '</a> </li>';
            }

            $li .= '</ul>';

            return $li;
        }
    }
}
