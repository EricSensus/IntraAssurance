<?php

namespace Jenga\MyProject\Api\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\View;

class ApiView extends View
{

    /**
     * @param $tokens
     */
    public function tokensDisplay($tokens)
    {
        $columns = ['Company', 'App Name', 'Token', 'Access Format', 'View Logs'];
        $rows = ['{company}',
            '{name}',
            '{token}',
            '{format}',
            '{{<a target="_blank" href="' . Url::link('/admin/api/logs/') . '{id}"><i class="fa fa-eye-slash"></i> }}'];

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
                'Company' => 'asc',
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
            'row_count' => count($tokens),
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $tokens
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

        $table = Generate::Table('tokenstable', $schematic);

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small',
            'settings' => [
                'add_tool_names' => false,
                'wrap_with' => 'div' //the default option is wrap_with => 'table'
            ],
            'tools' => [
                'add' => [
                    'path' => '/admin/api/add',
                    'tooltip' => 'Add Product',
                    'type' => 'modal',
                    'settings' => [
                        'id' => 'addtokensmodal',
                        'data-target' => '#addtokensmodal',
                        'data-backdrop' => 'static'
                    ]
                ],
                'delete' => [
                    'path' => '/admin/api/revoke',
                    'using' => ['{id}' => '{name}']
                ]
            ]
        ];

        $table->buildTools($tools);
        $tokens_table = $table->render(TRUE);

        //add the delete confirm modal
        $revoke_modal = Overlays::confirm();
        $this->set('revoke_modal', $revoke_modal);

        $this->set('tokens_setup', $tokens_table);
    }

    /**
     * @param $company
     */
    public function addNewTokenForm($company)
    {
        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/api/savetoken',
            'controls' => [
                'Insurance Company' => ['select', 'company', null, $company],
                'App Name' => ['text', 'name', '',],
                'Format' => ['select', 'format', 'json', ['json' => 'JSON', 'xml' => 'XML']]
            ],
            'validation' => [
                'name' => ['required' => 'Please enter the token name'],
                'format' => ['required' => 'Please select a format, '],
                'comapny' => ['required' => 'Select the company']
            ]
        ];

        $form = Generate::Form('addform', $schematic);
        $pform = $form->render('horizontal', true);

        $modal_settings = [
            'formid' => 'addform',
            'role' => 'dialog',
            'title' => 'Add New Access Token',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Create Token' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addform = Overlays::ModalDialog($modal_settings, $pform);

        $this->set('addform', $addform);
        $this->setViewPanel('newtoken');

    }

    public function showLogs($data)
    {
        $count = count($data->logs);
        $source = $data->logs;
        $rows = [
            '{type}',
            '{endpoint}',
            '{status}',
            '{format}',
            //'{response}',
            '{time}'];
        $schematic = [
            'table' => [
                'width' => '100%',
                'class' => 'table table-striped dataTable no-footer',
                'border' => 0,
                'cellpadding' => 5
            ],
            'dom' => '<"top">rt<"clear">',
            'columns' => ['Action', 'Section', 'Status', 'Format', /*'Response',*/ 'Time'],
            'ordering' => [
                'Time' => 'desc',
                'disable' => 0
            ],
            'row_count' => $count,
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $source
            ],
            'row_variables' => $rows
        ];
        $table = Generate::Table('api_logs', $schematic);
        $logs_table = $table->render(TRUE); //or False depending on whether you want the table to be returned as a string.
        $this->set('data', $data);
        $this->set('logs_table', $logs_table);
        $this->setViewPanel('access-logs');
    }
}

