<?php

namespace Jenga\MyProject\Customers\Views;

use Jenga\App\Views\View;
use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class CustomersView extends View
{

    public function generateTable()
    {

        $count = $this->get('count');
        $source = $this->get('source');
        $url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'customers']);

        $columns = ['Full Name',
            'Email Address',
            'Phone',
            'Policies',
            'Quotes',
            'Claims',
            'Date of Registration',
            'Actions'
        ];

        $rows = ['{{<a href="' . $url . '/show/{id}">{name}</a>}}',
            '{email}',
            '{phone}',
            '{policies}',
            '{qcount}',
            '{claims}',
            '{regdate}',
            '{actions}'];

        $dom = '<"top">rt<"bottom"p><"clear">';

        $substable = $this->_table('customers_table', $count, $columns, $rows, $source, $dom);

        $this->set('customers_table', $substable['table']);
        $this->set('customers_tools', $substable['tools']);
    }

    public function getLoginModal($customer)
    {
        $email_schema = [
            'preventjQuery' => TRUE,
            'preventZebraJs' => TRUE,
            'method' => 'POST',
            'action' => '/admin/customers/makelogincredentials/' . $customer->id,
            'controls' => [
                '{id}' => ['hidden', 'id', $customer->id],
                'Customer' => ['text', 'customer_name', $customer->name, ['readonly' => 'readonly', 'class' => 'form-control']],
                'Email Address*' => ['text', 'email', $customer->email, ['class' => 'modal_required form-control', 'readonly' => 'readonly',]],
                'Old Password' => ['password', 'old_password', 'old-pass', ['class' => 'modal_required form-control', 'readonly' => 'readonly']],
                'New Password' => ['password', 'new_password', null, ['class' => 'modal_required form-control']],
                'Confirm Password' => ['password', 'confirm_password', null, ['class' => 'modal_required form-control']],
                'Enabled' => ['radios', 'enabled', ['yes' => 'Yes', 'no' => 'No'], 'yes'],
                'Send credentials to user' => ['checkbox', 'send_email', 'yes'],
            ]
        ];
        $emailform = Generate::Form('credentialsform', $email_schema)->render('vertical', TRUE);

        $modal_settings = [
            'formid' => 'credentialsform',
            'role' => 'dialog',
            'size' => 'large',
            'title' => 'Update or create user credentials',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Confirm' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary mail',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addform = Overlays::ModalDialog($modal_settings, $emailform, true);

        $this->set('addform', $addform);
        $this->setViewPanel('credentials');
    }

    public function generateClaims()
    {
        $_navigation = Elements::call('Navigation/NavigationController');
        $count = $this->get('claims_count');
        $source = $this->get('claims');
        $customer = $this->get('customer');
        $cust_url = $_navigation->getUrl('customers');
        $polurl = $_navigation->getUrl('policies');
        $columns = ['Claim No', 'Customer', 'Policy', 'Product', 'Start Date', 'Status', 'Actions'];
        $rows = [
            '{{<a href="' . Url::link('/admin/claims/edit/') . '{claim}">{claim}</a>}}',
            '{{<a href="' . $cust_url . '/show/{customer_id}">{customer}</a>}}',
            '{{<a href="' . $polurl . '/edit/{policyno}">{policy}</a>}}',
            '{product}',
            '{created}',
            '{status}',
            '{actions}'
        ];
        $dom = '<"top"f>rt<"bottom"p><"clear">';

        $tools = ['images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small',
            'settings' => [
                'add_tool_names' => false,
                'wrap_with' => 'div' //the default option is wrap_with => 'table'
            ],
            'tools' => [
                'add' => [
                    'path' => '/admin/claims/add/' . $customer->id
                ],
            ]
        ];

        $claims_table = $this->_minitable('claimstable', $count, $columns, $rows, $source, $dom, $tools);
        $this->set('claimstable', $claims_table);
    }

    public function generatePolicies()
    {

        $count = $this->get('policycount');
        $source = $this->get('policies');
        $customer = $this->get('customer');

        $policyurl = Elements::call('Navigation/NavigationController')->getUrl('policies');

        $columns = ['Policy No.', 'Validity', 'Issue Date', 'Product', 'Status', 'Issue'];
        $rows = ['{{<a href="' . SITE_PATH . $policyurl . '/edit/{id}">{policyno}</a>}}',
            '{validity}',
            '{issuedate}',
            '{product}',
            '{status}',
            '{{<a href="' . SITE_PATH . '/admin/policies/processissue/{id}" ><img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/issue_policy_icon.png" width="20" /></a>}}'];

        $dom = '<"top"f>rt<"bottom"p><"clear">';

        $tools = ['images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small',
            'settings' => [
                'add_tool_names' => false,
                'wrap_with' => 'div' //the default option is wrap_with => 'table'
            ],
            'tools' => [
                'add' => [
                    'path' => '/admin/policies/add/' . $customer->id
                ],
                'delete' => [
                    'path' => '/admin/customers/deletepolicies',
                    'using' => ['{id}' => '{policyno},{validity}'],
                    'confirm' => true
                ]
            ]
        ];

        $policiestable = $this->_minitable('policiestable', $count, $columns, $rows, $source, $dom, $tools);
        $this->set('policiestable', $policiestable);
    }

    public function generateQuotesTable()
    {

        $count = $this->get('quote_count');
        $source = $this->get('quotes');
        $customer = $this->get('customer');

        $quoteurl = Elements::call('Navigation/NavigationController')->getUrl('quotes');

        $columns = ['Date Generated',
            'Quote No',
            'Product',
            'Insured Entity',
            'Linked Agent',
            'Preview'
        ];
        $quote_preview_url = Url::link('/admin/myquote/view/');
        $rows = ['{date}',
            '{{<div style="width:100%;text-align:center"><a href="' . $quoteurl . '/edit/{id}">{id}</a></div>}}',
            '{product}',
            '{entity}',
            '{agent}',
            '{{<a href="' . $quote_preview_url . '{id}" data-toggle="modal" data-target="#quoteModal"><i class="fa fa-eye"></i> Preview</a>}}'
        ];

        $dom = '<"top"f>rt<"bottom"p><"clear">';

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small',
            'settings' => [
                'add_tool_names' => false,
                'wrap_with' => 'div' //the default option is wrap_with => 'table'
            ],
            'tools' => [
                'add' => [
                    'path' => '/admin/quotes/add/' . $customer->id
                ],
                'delete' => [
                    'path' => '/admin/customers/deletequotes',
                    'using' => ['{id}' => '{customer},{product}']
                ]
            ]
        ];

        $subquotes = $this->_minitable('quotestable', $count, $columns, $rows, $source, $dom, $tools);
        $this->set('quotes', $subquotes);

        $modal_settings = [
            'id' => 'quoteModal',
            'formid' => 'quote-preview-form',
            'size' => 'large',
            'role' => 'dialog',
            'title' => 'Quote Preview',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];
        $this->set('quoteModal', Overlays::Modal($modal_settings));
    }

    public function generateEntitiesTable($entities)
    {

        $count = $this->get('entitycount');
        $customerurl = Elements::call('Navigation/NavigationController')->getUrl('customers', TRUE, TRUE);

        $columns = ['No.', 'Type', 'Summary', ''];
        $rows = ['{id}',
            '{type}',
            '{{<a href="' . $customerurl . '/editfullentity/{id}/{customerid}" '
            . 'data-target="#addnewentity" data-backdrop="static" data-toggle="modal" >'
            . '<div style="text-align:left">{summary}</div>'
            . '</a>}}',
            '{{<a class="smallicon" data-confirm="{type} will be deleted. Are you sure?" href="' . Url::base() . '/admin/customers/deleteentity/{id}/{customerid}" >'
            . '<img ' . Notifications::tooltip('Delete {type}') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
            . '</a>}}'];

        $dom = '<"top"f>rt<"bottom"p><"clear">';

        $entitiestable = $this->_minitable('entitiestable', $count, $columns, $rows, $entities, $dom);
        $this->set('entitiestable', $entitiestable);
    }

    private function _table($name, $count, array $columns, array $rows, $source, $dom)
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
                'Full Name' => 'asc',
                'disable' => 0
            ],
            'column_attributes' => [
                'default' => [
                    'align' => 'left'
                ],
                'header_row' => [
                    'class' => 'header'
                ],
                '3' => [
                    'align' => 'center'
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
                'search' => [
                    'title' => 'Customers Filter Form',
                    'form' => [
                        'preventjQuery' => TRUE,
                        'action' => '/admin/customers/search',
                        'controls' => [
                            'destination' => ['hidden', 'destination', Url::current()],
                            'Customer Name' => ['text', 'name', ''],
                            'Email Address' => ['text', 'email', '']
                        ],
                        'map' => [2]
                    ]
                ],
                'add' => [
                    'path' => '/admin/customers/add',
                    'type' => 'modal',
                    'settings' => [
                        'id' => 'addmodal',
                        'data-target' => '#addmodal',
                        'data-backdrop' => 'static'
                    ]
                ],
                'import' => [
                    'path' => Url::base() . '/admin/customers/import',
                    'upload_folder' => ABSOLUTE_PATH . DS . 'tmp',
                    'allowed_file_extensions' => ['csv', 'xls', 'xlsx', 'xlsm', 'xlsb'],
                    'file_preview' => FALSE
                ],
                'export' => [
                    'path' => '/admin/customers/export'
                ],
                'printer' => [
                    'path' => '/admin/customers/printer',
                    'settings' => [
                        'title' => 'Customers Management'
                    ]
                ],
                'delete' => [
                    'path' => '/admin/customers/delete',
                    'using' => ['{id}' => '{name},{email}']
                ]
            ],
        ];

        if (!is_null(Input::post('printer'))) {
            $table->printOutput();
        } else {

            $customertable['tools'] = $table->buildTools($tools, TRUE); //->assignPanel('search');
            $customertable['table'] = $table->render(TRUE);

            return $customertable;
        }
    }

    private function _minitable($name, $count, array $columns, array $rows, $source, $dom, $tools = null)
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
                    'align' => 'center'
                ],
                'header_row' => [
                    'class' => 'header'
                ]
            ],
            'row_count' => $count,
            'rows_per_page' => 7,
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

        $table = Generate::Table($name, $schematic);

        if (!is_null($tools))
            $table->buildTools($tools);

        $minitable = $table->render(TRUE);

        return $minitable;
    }

    public function makeEntityForm($form, $etype, $params)
    {

        $customerurl = Elements::call('Navigation/NavigationController')->getUrl('customers');
        $entform = '<form id="entityform" class="Zebra_Form" '
            . 'action="' . Url::base() . '/ajax/admin/customers/savenewentity" '
            . 'method="post">'
            . '<input type="hidden" name="destination" value="' . $customerurl . '/show/' . $params['customerid'] . '">'
            . '<input type="hidden" name="customerid" value="' . $params['customerid'] . '">'
            . '<input type="hidden" name="formid" value="' . $params['formid'] . '">'
            . '<input type="hidden" name="entityid" value="' . $params['entityid'] . '">'
            . '<table>'
            . $form
            . '</table>'
            . '</form>';

        $modal_settings = [
            'id' => 'addeditmodal',
            'formid' => 'entityform',
            'role' => 'dialog',
            'title' => 'New ' . ucfirst($etype),
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save New Entity' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $eform = Overlays::ModalDialog($modal_settings, $entform, true);

        $this->set('eform', $eform);
        $this->setViewPanel('customer-entity');
    }

    public function displayEditEntity($id, $script, $form, $params)
    {

        $customerurl = Elements::call('Navigation/NavigationController')->getUrl('customers');
        $entform = '<form id="entityform" class="Zebra_Form" '
            . 'action="' . Url::base() . '/ajax/admin/customers/saveentityedit" '
            . 'method="post">'
            . '<input type="hidden" name="destination" value="' . $customerurl . '/show/' . $params['customerid'] . '">'
            . '<input type="hidden" name="customerentityid" value="' . $id . '">'
            . '<input type="hidden" name="entityid" value="' . $params['entityid'] . '">'
            . '<table>'
            . $form
            . '</table>'
            . '</form>';

        $modal_settings = [
            'id' => 'addeditmodal',
            'formid' => 'entityform',
            'role' => 'dialog',
            'title' => 'Edit Customer Entity',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save New Entity' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $eform = Overlays::ModalDialog($modal_settings, $entform, true);

        $this->set('eform', $eform);
        $this->set('script', $script);

        $this->setViewPanel('customer-entity');
    }

    public function matchImportColumns($doc)
    {

        $alert = Notifications::Alert('The <strong>' . $doc->worksheet->name . '</strong> has been imported. '
            . 'Now align matching customer table columns with the imported document columns', 'success', TRUE);

        $columns =
            ['name' => 'Full Name',
                'email' => 'Email Address',
                'mobile_no' => 'Phone',
                'postal_address' => 'Postal Address',
                'postal_code' => 'Postal Code',
                'date_of_birth' => 'Date of Birth',
                'id_number' => 'ID Number',
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
        $this->set('customers_match_columns', $table);

        $this->setViewPanel('customers-match-columns');
    }

    public function customerLoginModal()
    {
        $modal_settings = [
            'id' => 'customer_login_modal',
            'role' => 'dialog',
            'title' => 'Customer Login'
        ];

        return Overlays::Modal($modal_settings);
    }

    public function customerLoginForm($element = null, $destination = null)
    {
        if (is_null($destination) && !is_null($element))
            $dest = '/' . $element . '/step/2';
        else
            $dest = $destination;

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/customer/login',
            'css' => false,
            'controls' => [
                'destination' => ['hidden', 'destination', $dest],
                'Email Address' => ['text', 'username', '', ['class' => 'form-control']],
                'Password' => ['password', 'password', '', ['class' => 'form-control']],
                '{note}' => ['note', 'note1', '', '<a id="forgot_pass">Forgot Password?</a>']
            ],
            'validation' => [
                'username' => [
                    'required' => 'Please enter your email address'
                ],
                'password' => [
                    'required' => "Please enter your password"
                ]
            ]
        ];

        $modal_settings_again = [
            'formid' => 'customerloginform',
            'id' => 'customer_login_modal',
            'role' => 'dialog',
            'title' => 'Customer Login',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Log In' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'login_button'
                ]
            ]
        ];

        $form = Generate::Form('customerloginform', $schematic);
        $aform = $form->render('horizontal', TRUE);

        $modal_form = Overlays::ModalDialog($modal_settings_again, $aform);

        if (!is_null($destination))
            return $modal_form;

        $this->set('login_form', $modal_form);
        $this->setViewPanel('customer_login');
    }

    public function customerForgotPassForm(){
        return '
            <div id="forgot_passform" style="display: none;">
                <div class="modal-header">
                    <h4 class="modal-title">Forgot Password?</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="'.Url::link('/profile/reset-password').'" method="post" role="form">
                        <div class="form-group">
                            <label  class="col-sm-3 control-label" for="inputEmail3">Email Address:</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" placeholder="Email" required/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" id="cancel-forgot" onclick="toggleLoginForm();">Cancel</button>
                    <button type="button" id="send-reset" class="btn btn-primary">Send Reset Link</button>
                </div>
            </div>
        ';
    }
}