<?php

namespace Jenga\MyProject\Claims\Views;

use Jenga\App\Html\Generate;
use Jenga\App\Request\Input;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\View;
use Jenga\MyProject\Elements;

/**
 * Class ClaimsView
 * @package Jenga\MyProject\Claims\Views
 */
class ClaimsView extends View
{
    /**
     * @var bool
     */
    private $dash = false;

    /**
     * @param bool $dash
     */
    public function generateTable($dash = false){
        
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
        $columns = ['Actions', 'Claim No', 'Customer', 'Policy', 'Product', 'Start Date', 'Status'];
        
        //  print_r(get_defined_vars());exit;
        $rows = [
            '{actions}',
            '{{<a href="' . Url::link('/admin/claims/edit/') . '{claim}">{claim}</a>}}',
            '{{<a href="' . $cust_url . '/show/{customer_id}">{customer}</a>}}',
            '{{<a href="' . $polurl . '/edit/{policyno}">{policy}</a>}}',
            '{product}',
            '{created}',
            '{status}'
        ];
        $dom = '<"top">rt<"bottom"p><"clear">';
        
        $claims_table = $this->_mainTable('claims_table', $count, $columns, $rows, $source, $dom, $search);
        
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
        $this->set('claims_table', $claims_table);
    }

    /**
     * Get a framework table representation
     * @param $name
     * @param $count
     * @param array $columns
     * @param array $rows
     * @param $source
     * @param $dom
     * @param string $searchform
     * @return \Jenga\App\Html\type
     */

    private function _mainTable($name, $count, array $columns, array $rows, $source, $dom, $searchform = '')
    {

        $schematic = [

            'table' => [
                'width' => '100%',
                'class' => 'display table table-striped',
                'border' => 0,
                'cellpadding' => 5
            ],
            'format' => 'dataTable',
            'dom' => $dom,
            'columns' => $columns,
            'ordering' => [
                'Customer' => 'desc',
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
        
        $table->buildShortcutMenu('{actions}', 'mouseover', [
            function ($id) {
                return '<li><a  class="dropdown-item"  href="' . SITE_PATH . '/admin/claims/edit/' . $id . '">'
                    . '<i class="fa fa-check-circle"></i> Open Claim'
                    . '</a></li>';
            },
            function ($id) {
                return '<li><a  class="dropdown-item"  href="' . SITE_PATH . '/admin/policies/previewpolicy/' . $id . '">'
                    . '<i class="fa fa-eye-slash"></i> View Linked Policy'
                    . '</a></li>';
            },
            '<li class="divider"></li>',
            function ($id) {
                return '<li><a  class="dropdown-item"  href="' . SITE_PATH . '/admin/claims/edit/' . $id . '#claim-timeline">'
                    . '<i class="fa fa-eye-slash"></i> View Claim Timeline'
                    . '</a></li>';
            },
            function ($id) {
                return '<li><a  class="dropdown-item"  href="' . SITE_PATH . '/admin/claims/edit/' . $id . '#edit-claim">'
                    . '<i class="fa fa-check-circle"></i> Update Claim'
                    . '</a></li>';
            },
            function ($id) {
                
                if(!$this->user()->is('customer')){
                    
                    return '<li class="divider"></li>'
                        . '<li><a  class="dropdown-item"  href="' . SITE_PATH . '/admin/claims/edit/' . $id . '#edit-claim">'
                        . '<i class="fa fa-ban"></i> Close Claim'
                        . '</a></li>';
                }
            },
            function ($id) {
                if(!$this->user()->is('customer')){
                    
                    return '<li><a data-confirm="Claim No.'.$id.' will be deleted. Are you sure?" title="Delete Claims" class="dropdown-item" href="' . SITE_PATH . '/admin/claims/delete/' . $id . '">'
                        . '<i class="fa fa-trash-o"></i> Delete Claim'
                        . '</a></li>';
                }
            }
        ]);
        
        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/',
            'tools' => [
                'search' => $searchform,
                'add' => [
                    'path' => '/admin/claims/add'
                ],
                'import' => [
                    'path' => Url::base() . '/admin/claims/import',
                    'upload_folder' => ABSOLUTE_PATH . '/tmp',
                    'allowed_file_extensions' => ['csv', 'xls', 'xlsx', 'xlsm', 'xlsb'],
                    'file_preview' => FALSE
                ],
                'export' => [
                    'path' => '/admin/claims/export'
                ],
                'printer' => [
                    'path' => '/admin/claims/printer',
                    'settings' => [
                        'title' => 'Quotes Management'
                    ]
                ],
                'delete' => [
                    'path' => '/admin/claims/delete',
                    'using' => ['{id}' => '{insurer},{customer}'],
                    'confirm' => true
                ],
            ]
        ];

        if (Input::post('printer')) {

            $table->printOutput();
        } else {

            if (!$this->dash) {

                //remove tools if user is customer
                if($this->user()->is('customer')){
                    
                    unset($tools['tools']['import'],$tools['tools']['delete']);
                }
                
                $claims_tools = $table->buildTools($tools, true); //->assignPanel('search');
                $this->set('claims_tools', $claims_tools);
            }

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
        if (!empty($customer)) {
            $controls = [
                'Name' =>
                    ['text', 'customer_name', $customer->name, ['class' => 'form-control', 'readonly' => 'readonly']],
                'Customer' => ['hidden', 'customer_id', $customer->id]
            ];
        } else {
            $controls = ['Customer Name (Type Name Below)' =>
                ['select', 'customer_id', $customer->name, [], [
                    'class' => 'form-control chosen-select',
                    'overwrite' => true]]];
        }
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
            'attributes' => ['enctype' => 'multipart/form-data'],
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

    /**
     * Get policy list
     * @param $list
     * @return string
     */
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

    /**
     * Close claim
     * @param $claim
     * @param $policy
     */
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

    /**
     * Upload a claim document
     */

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

    /**
     * Get a policy preview
     * @param $data
     */

    public function previewPolicy($data)
    {
        $this->set('meta', $data);
        $this->setViewPanel('preview-policy');
    }

    /**
     * Add a claim
     * @param $data
     */
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

    /**
     * Upload a document form
     * @param array $settings
     */
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

    /**
     * Minimal features table
     * @param $name
     * @param $count
     * @param array $columns
     * @param array $rows
     * @param $source
     * @param $dom
     * @return \Jenga\App\Html\type
     */
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

    /**
     * Edit claim
     * @param $claim
     * @param $meta
     */
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

    public function matchImportColumns($doc)
    {

        $alert = Notifications::Alert('The <strong>' . $doc->worksheet->name . '</strong> has been imported. '
            . 'Now align matching policies table columns with the imported document columns', 'success', TRUE);

        $columns = [
            'customer' => 'Customer',
            'policy' => 'Policy No',
            'created' => 'Date Created',
            'status' => 'Status',
            'subject' => 'Subject',
            'description' => 'Description',
            'closed' => 'Closed',
            'agent_name' => 'Agent'
        ];

        $importcolumns = $doc->worksheet->columns;

        //get full table
        $count = 1;
        $table = '<div class="row">';

        foreach ($columns as $dbcol => $column) {
            //create select tag
            $scount = -1;
            $select = '<select class="form-control" name="importselect_' . $count . '">';
            foreach ($importcolumns as $imcolumn) {

                if ($scount == -1) {
                    $select .= '<option selected="selected" value="">Skip Column</option>';
                    $scount++;
                }

                $select .= '<option value="' . $scount . '">' . $imcolumn . '</option>';
                $scount++;
            }
            $select .= '</select>';

            $table .= '<div class="col-md-3">'
                . '<table class="table table-striped table-bordered">'
                . '<tr>'
                . '<td><p><strong>Column ' . $count . ': ' . $column . '</strong></p></td>'
                . '</tr>'
                . '<tr height="50">'
                . '<td>'
                . '<input type="hidden" name="columns[]" value="' . $count . ',' . $dbcol . '" >'
                . 'Select the import column which matches with <strong>' . $column . '</strong>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td>' . $select . '</td>'
                . '</tr>'
                . '</table>'
                . '</div>';
            $count++;
        }
        $table .= '</div>';

        $this->set('filepath', $doc->worksheet->filename);
        $this->set('alert', $alert);
        $this->set('claims_match_columns', $table);

        $this->setViewPanel('claims-match-columns');
    }

    public function showImportErrors($errorlog)
    {

        $dberrors = $errorlog['database'];

        $table = '<table class="table table-striped">'
            . '<thead>'
            . '<tr>'
            . '<th>Column Row Coordinates</th>'
            . '<th>Error Category</th>'
            . '<th>Error</th>'
            . '</tr>'
            . '</thead>';


        $table .= '</tbody>';

        //database errors
        foreach ($dberrors as $coords => $error) {

            $table .= '<tr>'
                . '<td>' . $coords . '</td>'
                . '<td>Database</td>'
                . '<td>' . $error . '</td>'
                . '</tr>';
        }

        //product errors
        $pderrors = $errorlog['policy'];
        foreach ($pderrors as $coords => $error) {

            $table .= '<tr>'
                . '<td>' . $coords . '</td>'
                . '<td>Products</td>'
                . '<td>' . $error . '</td>'
                . '</tr>';
        }

        //product errors
        $inserrors = $errorlog['agent_name'];
        foreach ($inserrors as $coords => $error) {

            $table .= '<tr>'
                . '<td>' . $coords . '</td>'
                . '<td>Insurers</td>'
                . '<td>' . $error . '</td>'
                . '</tr>';
        }

        $table .= '</tbody>'
            . '</table>';
    }
}
