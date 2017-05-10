<?php

namespace Jenga\MyProject\Rates\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Request\Input;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;
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
    public function generateTable($setup = false)
    {

        $count = $this->get('count');
        $source = $this->get('source');
        $rate_types = $this->get('rate_types');

        $columns = [
            'Company',
            'Rate Name',
            'Rate Value',
            'Rate Type',
            'Rate Category'
        ];

        $row_vars = [
            '{company}',
            '{{<a data-toggle="modal" data-target="#edit_rate_modal" href="' . Url::link('/admin/editrates/{id}') . '">{rate_name}</a>}}',
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
        $this->set('editmodal', $editform);
        $this->setViewPanel('rates');
        if ($setup) {

            $this->setViewPanel('rates-s');

        }
    }

    public function insurersTable($insurers)
    {
        $columns = ['Name', 'Rates Added', 'View Rates'];
        $rows = [
            '{official_name}',
            '{rates}',
            '{{<a class="smallicon raters" href="' . Url::current() . '?__company={link}" ><i class="fa fa-eye"></i> </a>}}'];

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

        $table = Generate::Table('companies', $schematic);

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

        $this->set('insurertable', $insurertable);
    }

    public function showtable($name, $columns, $count, $source, $row_vars, $rate_types, $rate_cats)
    {

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

        $table = Generate::Table($name, $schematic);

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/',
            'tools' => [
                'add' => [
                    'path' => '/admin/rates/add',
                    'type' => 'modal',
                    'settings' => [
                        'id' => 'addmodal',
                        'data-target' => '#addmodal',
                        'data-backdrop' => 'static'
                    ]
                ],
                'delete' => [
                    'path' => '/admin/rates/delete',
                    'using' => ['{id}' => '{rate_name},{rate_value}']
                ]
            ]
        ];

        $tools = $table->buildTools($tools, TRUE);
        $rates_table = $table->render(TRUE);

        $this->set('rates_table', $rates_table);
        $this->setViewPanel('rates-setup');
    }

    public function showAddRateForm($rate_types = [], $rate_cats, $insurer, $companies)
    {
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
                'Insurance Company' => ['select', 'insurer_id', $insurer->id, $companies,]
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

    public function showEditRateForm($rate, $rate_types, $rate_cats, $insurer)
    {
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