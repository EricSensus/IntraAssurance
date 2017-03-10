<?php
namespace Jenga\MyProject\Agents\Views;

use Jenga\App\Request\Url;
use Jenga\App\Html\Generate;
use Jenga\App\Views\View;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class AgentsView extends View {
    
    public function generateMainTable(){
        
        $count = $this->get('count');
        $source = $this->get('source');
        
        $columns = ['Names',
                    'Email Address',
                    'Telephone',
                    'Location',
                    'Login',
                    ''];
        
        $rows = ['{names}',
                '{email_address}',
                '{telephone_number}',
                '{physical_location}',
                '{login}',
                '{{<a data-toggle="modal" data-backdrop="static" data-target="#editformfield2" class="smallicon" '
                . '     href="'.Url::base().'/ajax/admin/agents/edit/{id}" >'
                    . '<img '.Notifications::tooltip('Edit agent').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/edit_icon.png"/>'
                . '</a>'
                . '<a class="smallicon" data-confirm="{names} will be deleted. Are you sure?" href="'.Url::base().'/admin/agents/delete/{id}" >'
                    . '<img '.Notifications::tooltip('Delete {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'
                ];
        
        $dom = '<"top">rt<"bottom"p><"clear">';
        
        $substable = $this->_mainTable('agents_table', $count, $columns, $rows, $source, $dom);
        
        //add the add and edit modal
        $editmodal = Overlays::Modal(['id'=>'editformfield2']);
        $this->set('editmodal', $editmodal);
        
        //add the delete confirm modal
        $deletemodal = Overlays::confirm();
        
        $this->set('deletemodal', $deletemodal);        
        $this->set('agents_table', $substable);
    }
    
    private function _mainTable($name, $count, array $columns, array $rows, $source, $dom){
        
        $schematic = [
            
            'table' => [
                'width' => '100%',
                'class' => 'display',
                'border' => 0,
                'cellpadding' => 5
            ],
            'dom' => $dom,
            'columns' => $columns,
            'ordering' => [
                'Names' => 'asc',
                'disable' => 0
            ],
            'column_attributes' => [
                'default' => [
                    'align' => 'center'
                ],
                'header_row' => [
                    'class' => 'header'
                ],
                '4' => [
                    'align' => 'left'
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
                    'align' => 'center'
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
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small',
            'settings' => [
                        'add_tool_names' => false,
                        'wrap_with' => 'div' //the default option is wrap_with => 'table'
                    ],
            'tools' => [
                        'add' => [
                            'path' => '/admin/agents/add',
                            'type' => 'modal',
                            'settings' => [
                                'id' => 'addagentmodal',
                                'data-target' => '#addagentmodal',
                                'data-backdrop' => 'static'
                            ]
                        ],
                        'delete' => [
                            'path' => '/admin/agents/delete',
                            'using' => ['{id}'=>'{names}']
                        ]
                ]
        ];

        $table->buildTools($tools); //->assignPanel('search');
        $maintable = $table->render(TRUE);
        
        return $maintable;
    }
    
    public function agentForm($agent = null){
            
        $destination = Elements::call('Navigation/NavigationController')->getUrl('setup');
        
        $company_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/agents/saveagent',
            'controls' => [
                'id' => ['hidden','id',$agent->id],
                'destination' => ['hidden','destination', $destination],
                'Agent Names' => ['text','names',$agent->names],
                'Telephone Number' => ['text','telno',$agent->telephone_number],
                'Email Address' => ['text','email_address',$agent->email_address],
                'Physical Location' => ['text','location',$agent->physical_location]
            ],
            'validation' => [
                'name' => [
                    'required' => 'Please enter the agent name'
                ],
                'telno' => [
                    'required' => 'Please enter the telephone number'
                ],
                'email_address' => [
                    'required' => 'Please enter the email address'
                ],
                'location' => [
                    'required' => 'Please enter the physical location'
                ]
            ],
            'map' => [1,2,1]
        ];
        
        $aform = Generate::Form('agentform', $company_schematic); 
        $agentform = $aform->render('vertical',TRUE);    
        
        $modal_settings = [
            'id' => 'addeditmodal',
            'formid' => 'agentform',
            'role' => 'dialog',
            'title' => 'Insurance Agent / Broker Details',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Agent / Broker' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];
        
        $iform = Overlays::ModalDialog($modal_settings, $agentform);
        
        $this->set('agentsform',$iform);        
        $this->setViewPanel('setup'.DS.'agents-add-edit');
    }
    
    public function agentLoginForm($loginform, $return = false){
        
        $modal_settings = [
            'id' => 'editloginmodal',
            'formid' => 'loginform',
            'role' => 'dialog',
            'title' => 'Agent / Broker Login Details',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Agent / Broker' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];
        
        $iform = Overlays::ModalDialog($modal_settings, $loginform);
        
        if($return == false){
            
            $this->set('agentsform',$iform);        
            $this->setViewPanel('setup'.DS.'agents-add-edit');
        }
        else{
            
            return $iform;
        }
    }
}