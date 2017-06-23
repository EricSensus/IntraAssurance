<?php
namespace Jenga\MyProject\Financials\Views;

use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\View;

use Jenga\MyProject\Elements;

class FinancialsView extends View {
    
    public function generateTable(){
        
        $count = $this->get('count');
        $source = $this->get('source');
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'customers']);
        
        $columns = ['Date Generated',
                    'Payee Name',
                    'Ref No.',
                    'Tracking ID',
                    'Merchant Reference',
                    'Amount'];
        
        $rows = ['{quotetime}',
                '{{<a href="'.SITE_PATH.$url.'/show/{_id}">{name}</a>}}',
                '{refno}',
                '{trackingid}',
                '{merchant}',
                '{amount}'];
        
        $dom = '<"top">rt<"bottom"p><"clear">';
        
        $financestable = $this->_table('finances_table', $count, $columns, $rows, $source, $dom);
        
        $this->set('finances_table', $financestable);
    }
    
    private function _table($name, $count, array $columns, array $rows, $source, $dom){
        
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
                'Date Generated' => 'desc',
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
                            'title' => 'Financials Filter Form',
                            'form' => [
                                'preventjQuery' => TRUE,
                                'method' => 'post',
                                'action' => '/admin/financials/search',
                                'controls' => [
                                    'Payee Name' => ['text','name',''],
                                    'Reference No.' => ['text','refno',''],
                                    'Tracking Id.' => ['text','trackingid','']
                                ],
                                'map' => [3]
                            ]
                        ],
                        'export' => [
                            'path' => '/admin/financials/export'
                        ],
                        'printer' => [
                            'path' => '/admin/financials/printer',
                            'settings' => [
                                'title' => 'Financials Management'
                            ]
                        ],
                        'delete' => [
                            'path' => '/admin/financials/delete',
                            'using' => ['{id}'=>'{refno};{trackingid}']
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
}