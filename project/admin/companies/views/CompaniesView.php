<?php
namespace Jenga\MyProject\Companies\Views;

use Jenga\App\Request\Url;
use Jenga\App\Html\Generate;
use Jenga\App\Views\View;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class CompaniesView extends View {
    
    public function generateMainTable(){
        
        $count = $this->get('count');
        $source = $this->get('source');
        
        $columns = ['Company Name',
                    'Email Address',
                    'Physical Location',
                    'Postal Address'];
        
        $rows = ['{name}',
                '{email_address}',
                '{physical_address}',
                '{postal_address}'];
        
        $dom = '<"top">rt<"bottom"p><"clear">';
        
        $substable = $this->_mainTable('companies_table', $count, $columns, $rows, $source, $dom);
        $this->set('companies_table', $substable);
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
                'Date Generated' => 'asc',
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
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/',
            'tools' => [
                        'search' => [
                            'title' => 'Policies Filter Form',
                            'path' => '/admin/companies/search',
                            'form' => [
                                'preventjQuery' => TRUE,
                                'controls' => [
                                    'Full Name' => ['text','name',''],
                                    'Reference No.' => ['text','refno',''],
                                    'Product Type' => ['select','qtype','',[
                                        '' => 'Select Product Type',
                                        'motor' => 'Motor',
                                        'medical' => 'Medical',
                                        'travel' => 'Travel',
                                        'domestic_package' => 'Domestic Package'
                                    ]]
                                ],
                                'map' => [3]
                            ]
                        ],
                        'add' => [
                            'path' => '/admin/companies/add'
                        ],
                        'export' => [
                            'path' => '/admin/companies/export'
                        ],
                        'printer' => [
                            'path' => '/admin/companies/printer'
                        ],
                        'delete' => [
                            'path' => '/admin/companies/delete',
                            'using' => ['{id}'=>'{refno},{names}']
                        ]
                ]
        ];

        $table->buildTools($tools); //->assignPanel('search');
        $maintable = $table->render(TRUE);
        
        return $maintable;
    }
    
    public function insurersTable($insurers){
        
        $columns = ['Name','Official Name','Contact Email',''];
        $rows = ['{name}',
                '{official_name}',
                '{email_address}',
                '{{<a data-toggle="modal" data-target="#addeditmodal" class="smallicon" href="'.Url::base().'/ajax/admin/companies/getinsurer/{id}" >'
                    . '<img '.Notifications::tooltip('Edit {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/edit_icon.png"/>'
                . '</a>'
                . '<a class="smallicon" data-confirm="{name} will be deleted. Are you sure?" href="'.Url::base().'/admin/companies/deleteinsurer/{id}" >'
                    . '<img '.Notifications::tooltip('Delete {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'];
        
        $schematic = [
            
            'table' => [
                'width' => '100%',
                'class' => 'display',
                'border' => 0,
                'cellpadding' => 2
            ],
            'dom' => '<"top">rt<"bottom"><"clear">',
            'columns' => $columns,
            'ordering' => [
                'Name' => 'asc',
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
            'row_count' => count($insurers),
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $insurers
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
        
        $table = Generate::Table('insurers',$schematic);
        
        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small',
            'settings' => [
                'add_tool_names' => false,
                'wrap_with' => 'div' //the default option is wrap_with => 'table'
                ],
            'tools' => [
                        'add' => [
                            'path' => '/admin/companies/getinsurer',
                            'type' => 'modal',
                            'settings' => [
                                'id' => 'addeditmodal',
                                'data-target' => '#addeditmodal',
                                'data-backdrop' => 'static'
                            ]
                        ],
                        'delete' => [
                            'path' => '/admin/companies/deleteinsurer',
                            'using' => ['{id}'=>'{name}']
                        ]
                ]
        ];
        
        $table->buildTools($tools);
        $insurertable = $table->render(TRUE);
        
        //add the add and edit modal
        $addeditmodal = Overlays::Modal(['id'=>'addeditmodal']);
        $this->set('addeditmodal', $addeditmodal);
        
        //add the delete confirm modal
        $deletemodal = Overlays::confirm();
        $this->set('deletemodal', $deletemodal);
    
        $this->set('insurertable', $insurertable);
    }
    
    public function companyForm($company){
        
        $details = json_decode($company->physical_details, true);
        
        $company_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/companies/saveowndetails',
            'controls' => [
                'id' => ['hidden','id',$company->id],
                'destination' => ['hidden','destination', Url::current()],
                'Company Names' => ['text','name',$company->name],
                'Telephone' => ['text','telephone',$company->telephone],
                'Email Address' => ['text','email_address',$company->email_address],
                'Physical Location' => ['text','location',$details['location']],
                'Postal Address' => ['text','postal_address',$company->postal_address],
                'ZIP Code' => ['text','zipcode',$details['zipcode']],
                'City/County' => ['text','citycounty',$details['citycounty']],
                'Country' => ['country','country',$details['country']],
                '{submit}' => ['submit','btnsubmit','Save Details']
            ],
            'validation' => [
                'name' => [
                    'required' => 'Please enter the company name'
                ],
                'email_address' => [
                    'required' => 'Please enter the email address'
                ],
                'telephone' => [
                    'required' => 'Please enter the company telehone number'
                ]
            ],
            'map' => [1,2,1,2,2,1]
        ];
        
        $cform = Generate::Form('companyform', $company_schematic); 
        $companyform = $cform->render('vertical',TRUE); 
        
        $this->set('company_details_form', $companyform);
    }
    
    public function insurerForm($insurer = null){
            
        $destination = Elements::call('Navigation/NavigationController')->getUrl('setup');
        
        $company_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/companies/saveinsurer',
            'controls' => [
                'id' => ['hidden','id',$insurer->id],
                'destination' => ['hidden','destination', $destination],
                'Company Name' => ['text','name',$insurer->name],
                'Official Company Name' => ['text','official_name',$insurer->official_name],
                'Email Address' => ['text','email_address',$insurer->email_address]
            ],
            'validation' => [
                'name' => [
                    'required' => 'Please enter the company name'
                ],
                'official_name' => [
                    'required' => 'Please enter the official company name'
                ],
                'email_address' => [
                    'required' => 'Please enter the company email'
                ]
            ],
            'map' => [1,2]
        ];
        
        $cform = Generate::Form('insurerform', $company_schematic); 
        $insurerform = $cform->render('vertical',TRUE);    
        
        $modal_settings = [
            'id' => 'addeditmodal',
            'formid' => 'insurerform',
            'role' => 'dialog',
            'title' => 'Insurer Company Details',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Company' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];
        
        $iform = Overlays::ModalDialog($modal_settings, $insurerform);
        
        $this->set('insurerform',$iform);        
        $this->setViewPanel('insurer-add-edit');
    }
}