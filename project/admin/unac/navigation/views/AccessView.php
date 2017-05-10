<?php
namespace Jenga\MyProject\Navigation\Views;

use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Views\View;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class AccessView extends View {
    
    public function accessTable($source){
        
        $count = count($source);
        $columns = ['Hierachy',
                    'Drag to reorder',
                    'Display Name',
                    'Alias',
                    ''
                    ];
        
        $editurl = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'access']);
        $rows = ['{level}',
                '{{<div style="width:100%; text-align:center;"><img '.Notifications::tooltip('Drag and Drop to change access hierachy').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/rows.png"/></div>}}',
                '{{<a href="'.Url::base().$editurl.'/edit/{id}">{name}</a>}}',
                '{alias}',
                '{{<a class="smallicon" data-confirm="{name} will be deleted. Are you sure?" href="'.Url::base().$editurl.'/delete/{id}" >'
                    . '<img '.Notifications::tooltip('Delete {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'
                ];     
        
        $dom = '<"top">rt<"bottom"p><"clear">';
        $reorder = [
                'id' => '{id}',
                'url' => Url::base().'/ajax/admin/navigation/access/managehierachy/',
                'requesttype' => 'GET'
            ];
        
        $accesstable = $this->_mainTable('access_table', $count, $columns, $rows, $reorder,$source, $dom);
        
        //add the logins modal
        $loginmodal = Overlays::Modal(['id'=>'editlogins']);
        $this->set('loginmodal', $loginmodal);
        
        $this->set('access_table', $accesstable);
        
        //add the delete confirm modal
        $deletemodal = Overlays::confirm();
        
        $this->set('deletemodal', $deletemodal);          
        $this->set('count', $count);
        
        $this->setViewPanel('accesslist');
    }
    
    private function _mainTable($name, $count, array $columns, array $rows, $reorder,$source, $dom){
        
        $schematic = [            
            'table' => [
                'width' => '100%',
                'class' => 'display table table-striped',
                'border' => 0,
                'cellpadding' => 5
            ],
            'dom' => $dom,
            'columns' => $columns,
            'ordering' => [
                'Hierachy' => 'desc'
            ],
            'rowreorder' => $reorder,
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

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/',
            'tools' => [
                        'add' => [
                                    'path' => '/admin/navigation/access/addquote'
                                ]
                        ]
                ];

        if(Input::post('printer')){            
            $table->printOutput();
        }
        else{            
            $table->buildTools($tools); //->assignPanel('search');
            $maintable = $table->render(TRUE);
        
            return $maintable;
        }
    }
    
    public function systemPolicies(){
        
        $ekeys = $this->get('elementkeys');
        
        $count = 0;
        $elementlist = '<div class="list-group">';
        
        foreach($ekeys as $element){  
            
            $elementlist .= '<a href="#'.$element.'_tab" class="list-group-item '.($count == 0 ? 'active' : '').'">'
                                .'<p>'. ucfirst($element) .'</p>'
                            . '</a>';
            
            $content .= '<div id="'.$element.'_tab" class="bhoechie-tab-content '.($count == 0 ? 'active' : '').'">';
            
            //get heading
            $content .= '<h1 class="underline">'.ucfirst($element).' ACL Policy</h1>'
                    . '<input type="hidden" name="element[]" value="'.$element.'">';
            
            //get base acl
            $content .= '<div class="content element_content">';  
            
            $content .= '<div class="well well-sm">';
            $content .= '<div class="col-md-5">'
                        . '<h4>'.ucfirst($element).' Base ACL</h4>'
                            . '<small>This indicates the base access control level for element: '
                                . '<strong>'.  ucfirst($element).'</strong>'
                            . '</small>'
                        . '</div>';
            $content .= '<div class="col-md-7">'
                        . '<div class="col-xs-4" style="padding-top:30px;">'
                        . '<input type="text" name="'.$element.'_acl_level" id="formGroupInputLarge" class="form-control" value="'.$this->get($element.'_base_acl').'" >'
                        . '</div>';
            $content .= '</div>';
            
            //get other ACLs and actions
            $aclpermissions = $this->get('actionslist')[$element];
            
            if(!is_null($aclpermissions)){
                
                $aclactions = array_keys($aclpermissions);
                
                $element_tabs = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'
                        . '<ul id="tabs" class="nav nav-tabs nav-stacked" data-tabs="tabs">';
                
                $element_content .= '<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">'
                        . '<div class="tab-content">';
                
                $actioncount = 0;
                foreach ($aclactions as $acllevel) {
                    
                    $aclname = $acllevel;
                    $acllevel = str_replace(' ', '', strtolower($acllevel));
                    
                    $element_tabs .= '<li '.($actioncount == 0 ? 'class="active"' : '').'>'
                                        . '<a href="#'.$acllevel.'_'.$element.'" data-toggle="tab">'
                                            . ucfirst($aclname)
                                        . '</a>'
                                    . '</li>';
                    
                    $element_content .= '<div class="tab-pane '.($actioncount == 0 ? 'active' : '').'" id="'.$acllevel.'_'.$element.'">'
                                    . '<table class="table table-striped">';
                    
                    foreach($aclpermissions[$aclname] as $action => $value){
                        
                        if($this->has($element.'_'.$action.'_alias')){                            
                            $actionname = $this->get($element.'_'.$action.'_alias');
                        }
                        else{
                            $actionname = $action;
                        }
                        
                        $element_content .=  '<tr>'
                                . '<td style="padding-left: 20px; padding-top: 15px;">'
                                    . '<strong>'.ucfirst($actionname).'</strong>'
                                . '</td>'
                                . '<td>'
                                    //. ($value== TRUE ? 'HERE' : 'NOT')
                                    . '<input '
                                        .($value == TRUE ? 'checked="checked"' : '').' '
                                            . 'class="checkbox-toggle" '
                                            . 'data-toggle="toggle" '
                                            . 'id="'.$element.'_'.$action.'_id" '
                                            . 'data-width="180" '
                                            . 'data-class="fast" '
                                            . 'data-on="Action Allowed" '
                                            . 'data-off="Action Denied" '
                                            . 'type="checkbox">'
                                    ."</td>
                                    </tr>";
                    }
                    
                    $element_content .= '</table>'
                            . '</div>';
                    
                    $actioncount ++;
                }
                
                $element_content .= '</div>'
                        . '</div>';
                
                $element_tabs .= '</ul>'
                        . '</div>';   
                
                $content .= '</div>';
            }
            else{
                $content .= '</div>';                
                $content .= '<div>';
                
                $content .= Notifications::Alert('No registered actions for '.$element, 'info', TRUE, TRUE);
                $content .= '</div>';
            }
            
            $content .= $element_tabs;
            $content .= $element_content;
            
            $content .= '</div>'
                    . '</div>';
            
            $count++;
            unset($element_tabs, $element_content);
        }
        
        $elementlist .= '</div>';
        
        $this->set('content', $content);
        $this->set('elementlist', $elementlist);
        
        $this->setViewPanel('access'.DS.'policies');
    }
    
    public function systemPoliciesByLevel() {
        
        $ekeys = $this->get('elementkeys');
        $levels = $this->get('levels');
        
        $levelslist = '<div class="list-group">';
                
        //the base acl tab
        $levelslist .= '<a href="#base_acl_tab" class="list-group-item active">'
                        .'<p>Base ACL Levels</p>'
                    . '</a>';

        //acl tab pane
        $basecontent .= '<div id="base_acl_tab" class="bhoechie-tab-content active">';

        $basecontent .= '<h1 class="underline">Base ACL Levels</h1>';
        $basecontent .= '<blockquote>
                          <p>The Base ACL levels indicate the <strong>minimum</strong> access levels for each of the public
                          elements. Any user assigned a lower value than this, is automatically restricted.</p>
                          <footer><strong>Note:</strong> the base levels can also be assigned from the Jenga CLI</footer>
                        </blockquote>';

        $basecontent .= '<table class="table table-striped table-bordered">';
        $basecontent .= '<thead>
                            <tr>
                                <th>Element</th>
                                <th>ACL Level</th>
                            </tr>
                        </thead>
                        <tbody>';

        foreach($ekeys as $element){

            $basecontent .= '<tr>
                    <td>'.ucwords($element).'</td>
                    <td>
                    <div class="col-xs-4">
                        <input type="text" name="'.$element.'_acl_level" id="formGroupInputLarge" class="form-control" value="'.$this->get($element.'_base_acl').'" >
                    </div>
                    </td>
                   </tr>';
        }

        $basecontent .= '</tbody>'
                . '</table>';
        
        $basecontent .= '<div class="clearfix"></div>'
                        . '<div class="well" style="display:table; width:100%; margin-top:0px;">'
                            . '<button name="save_base_acl" value="save_base_acl" type="submit" class="btn btn-primary pull-right">'
                                . 'Save Base ACL'
                            . '</button>'
                        . '</div>';
        
        $basecontent .= '</div>';
        
        $aclcontent .= $basecontent;
        
        $count = 0;
        foreach ($levels as $level) {
            
            //acl level tabs
            $levelslist .= '<a href="#'.$level->alias.'_tab" class="list-group-item">'
                                .'<p>'. ucfirst($level->name) .'</p>'
                            . '</a>';
            
            //acl tab pane
            $aclcontent .= '<div id="'.$level->alias.'_tab" class="bhoechie-tab-content">';

            $aclcontent .= '<h1 class="underline">'.ucwords($level->name).' ACL Policy</h1>'
                    . '<input type="hidden" name="levels[]" value="'.$level->id.'">';

            $aclcontent .= '<blockquote>
                          <p>The listing of all the actions and permissions registered within each element under <strong>'.ucwords($level->name).'</strong> ACL level. </p>
                              <footer><strong>Note:</strong> change the permission by clicking the Permissions slider next to each action.</footer>
                        </blockquote>';
            
            $element_tabs = '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">'
                . '<ul id="tabs" class="nav nav-tabs nav-stacked" data-tabs="tabs">';

            $element_content .= '<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">'
                    . '<div class="tab-content">';

            //generate elements in this level
            $elmcount = 0;
            foreach($ekeys as $element){

                $element_tabs .= '<li '.($elmcount == 0 ? 'class="active"' : '').'>'
                                    . '<a href="#'.$element.'_'.$level->alias.'" data-toggle="tab">'
                                        . ucfirst($element)
                                    . '</a>'
                                . '</li>';

                $element_content .= '<div class="tab-pane '.($elmcount == 0 ? 'active' : '').'" id="'.$element.'_'.$level->alias.'">';

                //element acl permissions
                $aclpermissions = $this->get('actionslist')[$element];
                
                if(!is_null($aclpermissions)){

                    $element_content .= '<table class="table table-striped">';
                    
                    foreach ($aclpermissions[$level->alias] as $aclaction => $aclvalue) {

                        if($this->has($element.'_'.$aclaction.'_alias')){                            
                            $actionname = $this->get($element.'_'.$aclaction.'_alias');
                        }
                        else{
                            
                            $actionname = $aclaction;
                        }
                        
                        $element_content .=  '<tr>'
                            . '<td style="padding-left: 20px; padding-top: 15px;">'
                                . '<strong>'.ucfirst($actionname).'</strong>'
                            . '</td>'
                            . '<td>'
                                //. ($aclvalue== TRUE ? 'HERE' : 'NOT')
                                . '<input '
                                    .($aclvalue == TRUE ? 'checked="checked"' : '').' '
                                        . 'class="checkbox-toggle" '
                                        . 'data-toggle="toggle" '
                                        . 'name="'.$level->alias.'_'.$element.'_'.$actionname.'" '
                                        . 'data-width="180" '
                                        . 'data-class="fast" '
                                        . 'data-on="Action Allowed" '
                                        . 'data-off="Action Denied" '
                                        . 'type="checkbox">'
                                ."</td>
                                </tr>";
                    }

                    $element_content .= '</table>';  
                }
                else{

                    $element_content .= Notifications::Alert('No registered actions for '.$element, 'info', TRUE, TRUE);
                }
                
                $element_content .= '</div>';
                $elmcount++;
            }
            
            $element_tabs .= '</ul>'
                        . '</div>';

            $element_content .= '</div>'
                    . '</div>';
            
            $aclcontent .= $element_tabs;
            $aclcontent .= $element_content;
            
            $aclcontent .= '<div class="clearfix"></div>'
                        . '<div class="well" style="display:table; width:100%; margin-top:15px;">'
                            . '<button name="sent_user_level[]" value="'.$level->alias.'" type="submit" class="btn btn-primary pull-right">'
                                . 'Save '.ucwords($level->name).' Policy'
                            . '</button>'
                        . '</div>';
            
            $aclcontent .= '</div>';

            $count++;
            unset($basecontent, $element_tabs, $element_content);
        }
        
        //$content .= $basecontent;
        $content .= $aclcontent;
        
        $levelslist .= '</div>';
        
        $this->set('content', $content);
        $this->set('levellist', $levelslist);
        
        $this->setViewPanel('access'.DS.'policies_levels');
    }
}