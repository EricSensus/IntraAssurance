<?php
namespace Jenga\MyProject\Users\Views;

use Jenga\App\Views\HTML;
use Jenga\App\Views\View;
use Jenga\App\Request\Url;
use Jenga\App\Html\Generate;
use Jenga\App\Request\Input;
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
                    'Enabled',
                    'Last Login',
                    ''
                    ];
        
        $editurl = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'users']);
        $rows = ['{{<div style="width:100%;text-align:center">{id}</div>}}',
                '{{<a href="'.Url::base().'/admin/user/profile/{acl}/{id}">{fullname}</a>}}',
                '{{<a data-toggle="modal" data-backdrop="static" data-target="#editlogins" href="'.Url::base().'/ajax'.$editurl.'/loginsedit/{id}">'
                    . '<span '.Notifications::tooltip('Directly edit user login details').'>{username}</span>'
                . '</a>}}',
                '{acl}',
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
                'Enabled' => ['checkbox','enabled','yes',($user->enabled == 'yes' ? ['checked'=>'checked'] : [])],
                'ACL' => ['select', 'acl', 'Agent', [
                    'Agent' => 'Agent'
                ], [
                    'disabled' => 'disabled'
                ]]
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

    /**
     * Shows the full user profile form with the profile and login detail forms
     * @param type $user
     */
    public function showProfileForm($user){
        
        $profileform = $this->getUserProfileForm($user);
        $loginform = $this->getUserLoginForm($user);
        
        $this->set('login', $loginform);
        $this->set('profile', $profileform);
        
        $this->set('placeholder', HTML::AddPreloader('center', 80, 80, TRUE, 'Saving Profile Data'));
        $this->set('userfullname', $user->name);
        $this->setViewPanel('profileform');
    }
    
    /**
     * Return the user profile form
     * 
     * @param type $user
     * @return type
     */
    public function getUserProfileForm($user){
        
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'css' => FALSE,
            'method' => 'post',
            'map' => [1,2,1,2],
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                '{acl}' => ['hidden','acl',$user->acl],
                '{id}' => ['hidden','id',$user->id],
                'Full Names' => ['text','names',$user->name, ['class' => 'form-control']],
                'Mobile Number' => ['text', 'mobileno', $user->mobile_no, ['class' => 'form-control']],
                'Email Address' => ['text', 'email', $user->email, ['class' => 'form-control']],
                'Date of Birth' => ['text', 'dob', ($user->date_of_birth != 0 ? $user->date_of_birth : '') , ['class' => 'form-control', 'placeholder' => 'Date of Birth']],
                'Postal Code' => ['text', 'postcode', ($user->postal_code != 0 ? $user->postal_code : ''), ['class' => 'form-control', 'placeholder' => 'Postal Code']],
                'Postal Address' => ['text', 'post', ($user->postal_address != 0 ? $user->postal_address : ''), ['class' => 'form-control', 'placeholder' => 'Postal Address']]
            ]
        ];
        
         $form = Generate::Form('profile_form', $schematic)
            ->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        
        return $form;
    }
    
    /**
     * Returns the user login details form
     * 
     * @param type $user
     * @return type
     */
    public function getUserLoginForm($user){
        
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => 'none',
            'method' => 'post',
            'map' => [1,1,1],
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                '{acl}' => ['hidden','acl',$user->acl],
                '{id}' => ['hidden','id',$user->id],
                'User Name' => ['text','username',$user->username, ['class' => 'form-control']],
                'New Password <small class="pull-right" style="color: grey">(This will reset your existing password)</small>' => ['password', 'new_pass', '', ['class' => 'form-control', 'placeholder' => 'New Password']],
                'Confirm New Password' => ['password', 'confirm_pass', '', ['class' => 'form-control','placeholder' => 'Confirm New Password','data-parsley-equalto'=>'#new_pass']]
            ]
        ];
        
        $form = Generate::Form('login_form', $schematic)
            ->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);
        
        return $form;
    }
    
    public function showResetPasswordForm($user_id){
        
        $schematic = [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => 'none',
            'method' => 'post',
            'map' => [1,1,1,1],
            'action' => '/profile/save-password',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => [
                '{note}' => ['note', 'note1', '', 'Reset your Password:'],
                'New Password' => ['password', 'new_pass', '', ['class' => 'form-control']],
                'Repeat Password' => ['password', 'rep_pass', '', ['class' => 'form-control']],
                '{user_id}' => ['hidden', 'user_id', $user_id, ['class' => 'form-control']],
                '{submit}' => ['submit', 'btnsubmit', 'Reset Password', ['class' => 'btn btn-success']]
            ]
        ];

        if ($this->want_schematic) {
            return $schematic;
        }
        $form = Generate::Form('reset_password_form', $schematic)
            ->render(['orientation' => 'horizontal', 'columns' => 'col-sm-4,col-sm-8'], TRUE);

        $this->set('reset_form', $form);
        $this->setViewPanel('reset_password');
    }
}

