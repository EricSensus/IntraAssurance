<?php

namespace Jenga\MyProject\Claims\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Request\Input;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\View;
use Jenga\MyProject\Elements;

class ClaimsView extends View
{
    private $dash = false;
    public function generateTable($dash = false)
    {
        $this->dash = $dash;

        $_navigation = Elements::call('Navigation/NavigationController');
        $count = $this->get('count');
        $source = $this->get('source');
        $search = $this->get('search');
        $condition = $this->get('condition');
        $url = $_navigation->getUrl('claims');

        if (!empty($condition)) {
            //create the return url from the Navigation element
            $this->set('alerts', Notifications::Alert($condition
                . '<a data-dismiss="alert" class="close" href="' . $url . '">×</a>', 'info', TRUE, TRUE));
        }
        $cust_url = $_navigation->getUrl('customers');
        $polurl = $_navigation->getUrl('policies');
        $columns = ['Claim No', 'Customer', 'Policy', 'Product', 'Start Date', 'Status', 'Actions'];
        //  print_r(get_defined_vars());exit;
        $rows = [
            '{{<a href="' . Url::link('/admin/claims/edit/') . '{claim}">{claim}</a>}}',
            '{{<a href="' . $cust_url . '/show/{customer_id}">{customer}</a>}}',
            '{{<a href="' . $polurl . '/edit/{policyno}">{policy}</a>}}',
            '{product}',
            '{created}',
            '{status}',
            '{actions}'
        ];
        $dom = '<"top">rt<"bottom"p><"clear">';
        $policiestable = $this->_mainTable('claims_table', $count, $columns, $rows, $source, $dom, $search);
        $modal_settings = [
            'id' => 'emailmodal',
            'formid' => 'email-claim-form',
            'role' => 'dialog',
            'title' => 'Email Claim',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];
        $form = Overlays::Modal($modal_settings);
        $this->set('mailmodal', $form);
        $this->set('claims_table', $policiestable);
    }

    private function _mainTable($name, $count, array $columns, array $rows, $source, $dom, $searchform = '')
    {

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
                'Created' => 'desc',
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

        $table = Generate::Table($name, $schematic);

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/',
            'tools' => [
//                'issue policy' => [
//                    'path' => Url::link('/admin/policies/issuebatch'),
//                    'using' => ['{id}', '{customer}', '{status}'],
//                    'confirm' => true
//                ],
//                'Renew' => [
//                    'path' => Url::link('/admin/policies/renewbatch'),
//                    'using' => ['{id}', '{customer}', '{status}'],
//                    'confirm' => true
//                ],
                'search' => $searchform,
                'add' => [
                    'path' => '/admin/claims/add'
                ],
                'import' => [
                    'path' => Url::base() . '/admin/policies/import',
                    'upload_folder' => ABSOLUTE_PATH . '/tmp',
                    'allowed_file_extensions' => ['csv', 'xls', 'xlsx', 'xlsm', 'xlsb'],
                    'file_preview' => FALSE
                ],
                'export' => [
                    'path' => '/admin/policies/export'
                ],
                'printer' => [
                    'path' => '/admin/policies/printer',
                    'settings' => [
                        'title' => 'Quotes Management'
                    ]
                ],
                'delete' => [
                    'path' => '/admin/policies/delete',
                    'using' => ['{id}' => '{insurer},{customer}'],
                    'confirm' => true
                ],
            ]
        ];

        if (Input::post('printer')) {

            $table->printOutput();
        } else {
            if(!$this->dash)
                $table->buildTools($tools); //->assignPanel('search');
            $maintable = $table->render(TRUE);

            return $maintable;
        }
    }

    /**
     * Add Claim Form
     * @param \stdClass|null $customer
     */
    public function addClaimForm($customer = null)
    {
        $controls = ['Customer Name (Type Name Below)' =>
            ['select', 'customer_id', $customer->name, [], [
                'class' => 'form-control chosen-select',
                'overwrite' => true]]];
        $claim_start_schematic = [
            'preventjQuery' => true,
            'method' => 'POST',
            'action' => '/admin/claims/previewclaim',
            'controls' => $controls,
            'validation' => [
                'customer' => [
                    'required' => 'Please enter the customer names'
                ]
            ]
        ];
        $pform = Generate::Form('claim-form', $claim_start_schematic);
        $start_form = $pform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'claims' . DS . 'views' . DS . 'panels' . DS . 'claimform.php', TRUE, $claims);

        $claim_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'css' => false,
            'action' => '/admin/claims/saveclaim',
            'controls' => [
                '{policy_id}' => ['hidden', 'policy_id', null, []],
                '{customer_id}' => ['hidden', 'customer_id', null, []],
                'Subject' => ['text', 'subject', null, ['class' => 'form-control', 'required' => '']],
                'Description' => ['textarea', 'description', null, ['class' => 'form-control', 'rows' => 4]],
            ],
        ];
        $sform = Generate::Form('file-claim-form', $claim_schematic);
        $clam_details = $sform->render(__DIR__ . '/panels/claim-details.php', TRUE);
        $this->set('claim_add', $start_form);
        $this->set('claim_form', $clam_details);
        $this->setViewPanel('claimaddpanel');
    }

    public function getPolicyList($list)
    {
        $select = '<select class="control" id="policies" name="policy_id">';
        $select .= "<option value=''>Select Policy</option>";
        foreach ($list as $item) {
            $select .= '<option value="' . $item['id'] . '">Policy #'
                . $item['policy'] . ' '
                . ' generated on ' . date('d-M-Y', $item['datetime']) . ', '
                . 'for ksh ' . number_format($item['amount'], 2) . ', '
                . 'covered by ' . $item['insurer']
                . '</option>';
        }

        $select .= '</select>';

        return $select;
    }

    public function closeClaim($claim, $policy)
    {
        $update_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/claims/updateclaim',
            'controls' => [
                '{id}' => ['hidden', 'claim', $claim->id],
                '{status}' => ['select', 'status', $claim->status, ['Open' => 'Open', 'Processing' => 'Processing', 'Closed' => 'Closed']],
                '{sendemail}' => ['checkbox', 'sendemail', 'yes'],
                '{submit}' => ['submit', 'btnsubmit', 'Save Claim Information']
            ]
        ];

        $data['label'] = ['label' => 'Send email notification to <strong>' . $policy->customer->email . '</strong>'];
        $data['id'] = $claim->id;

        $iform = Generate::Form('issueform', $update_schematic);
        $issueform = $iform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'claims' . DS . 'views' . DS . 'panels' . DS . 'claimclose.php', TRUE, $data);
        $this->set('show_timeline', 'claimclose.php');
        $this->set('claim_add', $issueform);

        $this->setViewPanel('claimaddpanel');
    }

    public function uploadClaimAdditionalDocs()
    {

        $modal_settings = [
            'id' => 'adddocument',
            'formid' => 'importform',
            'role' => 'dialog',
            'title' => 'Upload Claim Documents',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];
        $form = Overlays::Modal($modal_settings);

        $this->set('uploadform', $form);
        $this->set('show_timeline', 'timeline.php');
        $this->set('current_step', 'three');
        $this->set('claim_add', 'the form');
        $this->setViewPanel('claimaddpanel');
    }

    public function previewPolicy($data)
    {
        $this->set('meta', $data);
        $this->setViewPanel('preview-policy');
    }

    public function addClaim($data)
    {

        $confirm_schematic = [
            'preventjQuery' => true,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/claims/saveclaim',
            'controls' => [
                '{policy_id}' => ['hidden', 'policy_id', $data->policy->id],
                '{submit}' => ['submit', 'btnsubmit', 'Continue']
            ]
        ];
        $confirmvars = [
            'policyid' => $data->policy->id,
            'quoteno' => $data->quote->id,
            'dategen' => $data->policy->datetime,
            'customername' => $data->customer->name,
            'policyemail' => $data->customer->email,
            'product' => ucfirst($data->product->name),
            'insurer' => ucfirst($data->insurer->name),
            'quotestatus' => ucfirst($data->policy->quotestatus),
            'currencycode' => $data->policy->currency_code,
            'amount' => $data->policy->amount,
            'premium' => $data->policy->amount,
            'policy_status' => ucfirst($data->policy->status),
            'startdate' => date('jS F Y', $data->policy->start_date),
            'enddate' => date('jS F Y', $data->policy->end_date)
        ];
        $cform = Generate::Form('confirmform', $confirm_schematic);
        $confirmform = $cform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'claims' . DS . 'views' . DS . 'panels' . DS . 'policy-add-form.php', TRUE, $confirmvars);

        $this->set('claim_add', $confirmform);
        $this->setViewPanel('claimaddpanel');
    }

    public function uploadForm(array $settings)
    {

        $uploadform = '<form id="importform" enctype="multipart/form-data" action="' . Url::route('/admin/claims/processupload') . '" method="POST" >'
            . '<input type="hidden" name="id" value="' . $settings['id'] . '">'
            . '<input id="input-id" name="file_import" type="file" class="file" >'
            . '<br/><textarea type="text" name="details" placeholder="Description of upload" class="form-control"></textarea>'
            . '</form>';

        $modal_settings = [
            'id' => 'adddocument',
            'formid' => 'importform',
            'role' => 'dialog',
            'title' => $settings['title'],
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];
        $form = Overlays::ModalDialog($modal_settings, $uploadform);
        $this->setViewPanel('documents');
        $this->set('uploadform', $form);
    }

    private function _minitable($name, $count, array $columns, array $rows, $source, $dom)
    {

        $schematic = [
            'table' => [
                'width' => '100%',
                'class' => 'display',
                'border' => 0,
                'cellpadding' => 5
            ],
            'dom' => $dom,
            'columns' => $columns,
            'column_attributes' => [
                'default' => [
                    'align' => 'left'
                ],
                'header_row' => [
                    'class' => 'header'
                ]
            ],
            'row_count' => $count,
            'rows_per_page' => 10,
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

        $table = Generate::Table($name, $schematic);
        $minitable = $table->render(TRUE);

        return $minitable;
    }

    public function editClaim($claim, $meta)
    {
        $update_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/claims/updateclaim',
            'controls' => [
                '{id}' => ['hidden', 'claim', $claim->id],
                '{status}' => ['select', 'status', $claim->status, ['Open' => 'Open', 'Processing' => 'Processing', 'Closed' => 'Closed']],
                '{sendemail}' => ['checkbox', 'sendemail', 'yes'],
                '{message}' => ['textarea', 'message', null, ['class' => 'form-control', 'rows' => 4]],
                '{submit}' => ['submit', 'btnsubmit', 'Save Claim Information']
            ]
        ];
        $data['label'] = ['label' => 'Send email notification to <strong>' . $meta->customer->email . '</strong>'];
        $data['id'] = $claim->id;
        $eform = Generate::Form('confirmform', $update_schematic);
        $editform = $eform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'claims' . DS . 'views' . DS . 'panels' . DS . 'claim_edit_form.php', TRUE, $data);

        // linked document/upload
        $modal_settings = [
            'id' => 'adddocument',
            'formid' => 'importform',
            'role' => 'dialog',
            'title' => 'Upload Quote Documents',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];

        $form = Overlays::Modal($modal_settings);

        // show the documents
        $columns = ['Date Generated', 'Description', 'Type', 'File Name', ''];
        $rows = ['{time}',
            '{description}',
            '{doctype}',
            '{filename}',
            '{{<a class="smallicon" data-confirm="{filename} will be deleted. Are you sure?" href="' . Url::base() . '/admin/policies/deletedoc/{policy_id}/{id}" >'
            . '<img ' . Notifications::tooltip('Delete {type}') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
            . '</a>}}'];

        $dom = '<"top">rt<"bottom"p><"clear">';

//        print_r($this->get('source'));exit;
        $docstable = $this->_minitable('docstable', $this->get('docs_count'), $columns, $rows, $this->get('source'), $dom);

        $this->set('uploadform', $form);
        $this->set('linked_docs', $docstable);
        $this->set('claimedit', $editform);
        $this->set('claim', $claim);
        $this->setViewPanel('claims_edit_panel');
    }
}
