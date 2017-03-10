<?php
namespace Jenga\MyProject\Users\Views;

use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Views\View;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class UsersView extends View {
    
    public function userTable($source){
        
        $count = count($source);
        $columns = ['ID',
                    'Full Names',
                    'Username',
                    'Access Level',
                    'Type',
                    'Enabled',
                    'Last Login',
                    ''
                    ];
        
        $editurl = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'users']);
        $rows = ['{{<div style="width:100%;text-align:center">{id}</div>}}',
                '{{<a href="'.Url::base().$editurl.'/edit/{id}">{fullname}</a>}}',
                '{{<a data-toggle="modal" data-backdrop="static" data-target="#editlogins" href="'.Url::base().'/ajax'.$editurl.'/loginsedit/{id}">'
                    . '<span '.Notifications::tooltip('Directly edit user login details').'>{username}</span>'
                . '</a>}}',
                '{access}',
                '{type}',
                '{enabled}',
                '{login}',
                '{{<a class="smallicon" data-confirm="{fullname} will be deleted. Are you sure?" href="'.Url::base().$editurl.'/delete/{id}" >'
                    . '<img '.Notifications::tooltip('Delete {fullname}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'
                ];     
        
        $dom = '<"top">rt<"bottom"p><"clear">';
        $userstable = $this->_mainTable('users_table', $count, $columns, $rows, $source, $dom);
        
        //add the logins modal
        $loginmodal = Overlays::Modal(['id'=>'editlogins']);
        $this->set('loginmodal', $loginmodal);
        
        $this->set('users_table', $userstable);
        
        //add the delete confirm modal
        $deletemodal = Overlays::confirm();
        
        $this->set('deletemodal', $deletemodal);          
        $this->set('count', $count);
        
        $this->setViewPanel('usersmanagement');
    }
    
    private function _mainTable($name, $count, array $columns, array $rows, $source, $dom){
        
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
                'ID' => 'desc',
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
                        'search' => [
                            'title' => 'Quotes Filter Form',
                            'form' => [
                                'preventjQuery' => TRUE,
                                'method' => 'post',
                                'action' => '/admin/quotes/search',
                                'controls' => [
                                    'Full Name' => ['text','name',''],
                                    'Product Type' => ['select','qtype','',[
                                        '' => 'Select Product Type',
                                        'motor' => 'Motor',
                                        'medical' => 'Medical',
                                        'travel' => 'Travel',
                                        'domestic_package' => 'Domestic Package'
                                    ]]
                                ],
                                'map' => [2]
                            ]
                        ],
                        'add' => [
                            'path' => '/admin/quotes/addquote'
                        ],
                        'export' => [
                            'path' => '/admin/quotes/export'
                        ],
                        'printer' => [
                            'path' => '/admin/quotes/printer',
                            'settings' => [
                                'title' => 'Quotes Management'
                            ]
                        ],
                        'delete' => [
                            'path' => '/admin/quotes/delete',
                            'using' => ['{id}'=>'{customer}']
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
    
    public function createLogin($user = null, $return = false, $from = '') {
        
        $navigation = Elements::call ('Navigation/NavigationController');
        $access = Elements::call('Navigation/AccessController');
        
        //process accesslevels
        $alevels = $access->model->where('level','>=','0')->orderBy('level','DESC')->show();
        foreach($alevels as $alevel){
            $levels[$alevel->id] = $alevel->name;
        }
        
        if($from == 'agents'){
            
            $destination = $navigation->getUrl('setup').'#setup';
        }
        elseif($from == 'users'){
            
            $destination = $navigation->getUrl('users');
        }
        
        $schematic = [
            'preventjQuery' => FALSE,
            'method' => 'POST',
            'action' => '/admin/users/savelogincredentials',
            'controls' => [        
                '{id}' => ['hidden','id',(is_string($user) ? $user : $user->id)],
                '{destination}' => ['hidden','destination',$destination],
                'Username or Email Address' => ['text','username',$user->username],
                'Password' => ['password', 'apassword', $user->password],
                'Confirm Password' => ['password', 'cpassword', $user->password],
                'Access Level' => ['select','accesslevel',$user->accesslevels_id,$levels],
                'Enabled' => ['checkbox','enabled','yes',($user->enabled == 'yes' ? ['checked'=>'checked'] : [])]
            ],
            'validation' =>[
                'username' => [
                    'required' => 'Please enter the username'
                ],
                'apassword' => [
                    'required' => 'Please enter your password'
                ],
                'cpassword' => [
                    'required' => 'Confirm your password'
                ],
                'accesslevel' => [
                    'required' => 'Please enter the access level'
                ]
            ]
        ];

        $login = Generate::Form('loginform', $schematic);
        
        if($return == false){
            echo $login->render('vertical');
        }
        else{
            return $login->render('horizontal',true);
        }
    }
    
    public function userLoginForm($loginform){
        
        $modal_settings = [
            'id' => 'editloginmodal',
            'formid' => 'loginform',
            'role' => 'dialog',
            'title' => 'User Login Details',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save User' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];
        
        $iform = Overlays::ModalDialog($modal_settings, $loginform);        
        return $iform;
    }
}

