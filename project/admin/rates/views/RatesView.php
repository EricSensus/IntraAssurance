<?php
namespace Jenga\MyProject\Rates\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Request\Input;
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\View;
/**
 * Created by PhpStorm.
 * User: developer
 * Date: 27/02/2017
 * Time: 13:05
 */
class RatesView extends View
{
    public function generateTable(){

        $count = $this->get('count');
        $source = $this->get('source');
        $rate_types = $this->get('rate_types');

        $columns = [
            'Rate Name',
            'Rate Value',
            'Rate Type',
            'Rate Category'
        ];

        $row_vars = [
            '{{<a data-toggle="modal" data-target="#edit_rate_modal" href="'.Url::link('/admin/editrates/{id}').'">{rate_name}</a>}}',
            '{rate_value}',
            '{rate_type}',
            '{rate_category}'
        ];

        $this->showtable('rates_table', $columns, $count, $source, $row_vars, $rate_types, $this->get('rate_cats'));

        $modal_settings = [
            'id' => 'edit_rate_modal',
            'role' => 'dialog',
            'title' => 'Edit Rate Details'
        ];

        $editform = Overlays::Modal($modal_settings);
        $this->set('editmodal',$editform);
    }

    public function showtable($name, $columns, $count, $source, $row_vars, $rate_types, $rate_cats){
        $schematic = [
            'table' => [
                'width' => '100%',
                'class' => 'table table-striped dataTable no-footer',
                'border' => 0,
                'cellpadding' => 5
            ],
            'dom' => '<"top"f>rt<"bottom"p><"clear">',
            'columns' => $columns,
            'row_count' => $count,
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $source
            ],
//            'ordering' => [
//                'Rate Name' => 'desc'
//            ],
            'format' => 'dataTable',
            'row_variables' => $row_vars,
        ];

        $table = Generate::Table($name,$schematic);

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/',
            'tools' => [
                'search' => [
                    'title' => 'Rates Filter Form',
                    'form' => [
                        'preventjQuery' => TRUE,
                        'action' => '/admin/rates/search',
                        'controls' => [
                            'destination' => ['hidden', 'destination', Url::current()],
                            'Rate Name' => ['text','rate_name',''],
                            'Rate Value' => ['text','rate_value',''],
                            'Rate Type' => ['select','rate_type', '', $rate_types],
                            'Rate Category' => ['select','rate_category','', $rate_cats]
                        ],
                        'map' => [2,2]
                    ]
                ],
                'add' => [
                    'path' => '/admin/rates/add',
                    'type' => 'modal',
                    'settings' => [
                        'id' => 'addmodal',
                        'data-target' => '#addmodal',
                        'data-backdrop' => 'static'
                    ]
                ],
                'import' => [
                    'path' => Url::base().'/admin/rates/import',
                    'upload_folder' => ABSOLUTE_PATH .DS. 'tmp',
                    'allowed_file_extensions' => ['csv','xls','xlsx','xlsm','xlsb'],
                    'file_preview' => FALSE
                ],
                'export' => [
                    'path' => '/admin/rates/export'
                ],
                'printer' => [
                    'path' => '/admin/rates/printer',
                    'settings' => [
                        'title' => 'Rates Management'
                    ]
                ],
                'delete' => [
                    'path' => '/admin/rates/delete',
                    'using' => ['{id}'=>'{rate_name},{rate_value}']
                ]
            ]
        ];

        if(!is_null(Input::post('printer'))){
            $table->printOutput();
        }else {
            $table->buildTools($tools);

            $rates_table = $table->render(TRUE);
            $this->set('rates_table', $rates_table);
        }
    }

    public function showAddRateForm($rate_types, $rate_cats, $insurer){
        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/rates/store',
            'controls' => [
                'destination' => ['hidden', 'destination', '/admin/rates'],
                'Rate Name' => ['text', 'rate_name', ''],
                'Rate Value' => ['text', 'rate_value', ''],
                'Rate Type' => ['select', 'rate_type', '', $rate_types],
                'Rate Category' => ['select', 'rate_category', '', $rate_cats],
                'Insurance Company' => ['select', 'insurer_id', $insurer->id, [
                    $insurer->id => $insurer->name
                ], [
                    'disabled' => 'disabled'
                ]]
            ],
            'validation' => [
                'rate_name' => [
                    'required' => 'Please enter the rate\'s name'
                ],
                'rate_value' => [
                    'required' => "Please enter the rate's value"
                ],
                'rate_type' => [
                    'required' => "Please enter the rate's type"
                ],
                'rate_category' => [
                    'required' => 'Please enter the rates\'s category',
                ]
            ]
        ];

        $modal_settings = [
            'formid' => 'addform',
            'role' => 'dialog',
            'title' => 'Rate Details',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Edits' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $form = Generate::Form('addform', $schematic);
        $aform = $form->render('horizontal', TRUE);

        $addform = Overlays::ModalDialog($modal_settings, $aform);

        $this->set('addform', $addform);
        $this->setViewPanel('add-rates');
    }

    public function showEditRateForm($rate, $rate_types, $rate_cats, $insurer){
        $this->disable();

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/rates/update',
            'controls' => [
                'destination' => ['hidden', 'destination', '/admin/rates'],
                'edit' => ['hidden', 'edit', $rate->id],
                'Rate Name' => ['text', 'rate_name', $rate->rate_name],
                'Rate Value' => ['text', 'rate_value', $rate->rate_value],
                'Rate Type' => ['select', 'rate_type', $rate->rate_type, $rate_types],
                'Rate Category' => ['select', 'rate_category', $rate->rate_category, $rate_cats],
                'Insurance Company' => ['select', 'insurer_id', $insurer->id, [
                    $insurer->id => $insurer->name
                ], [
                    'disabled' => 'disabled'
                ]]
            ],
            'validation' => [
                'rate_name' => [
                    'required' => 'Please enter the rate\'s name'
                ],
                'rate_value' => [
                    'required' => "Please enter the rate's value"
                ],
                'rate_type' => [
                    'required' => "Please enter the rate's type"
                ],
                'rate_category' => [
                    'required' => 'Please enter the rates\'s category',
                ]
            ]
        ];

        $modal_settings = [
            'formid' => 'editform',
            'id' => 'edit_rate_modal',
            'role' => 'dialog',
            'title' => 'Edit Rate Details',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Edits' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $form = Generate::Form('editform', $schematic);
        $aform = $form->render('horizontal', TRUE);

        echo $editform = Overlays::ModalDialog($modal_settings, $aform);

//        $this->set('editform', $editform);
//        $this->setViewPanel('edit-rates');
    }
}