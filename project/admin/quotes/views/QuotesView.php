<?php
namespace Jenga\MyProject\Quotes\Views;

use Jenga\App\Views\View;
use Jenga\App\Request\Url;
use Jenga\App\Helpers\Help;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;
use Jenga\App\Request\Facade\Sanitize;

use Jenga\MyProject\Elements;

class QuotesView extends View
{

    public $url;

    public function generateMiniTable()
    {
        $count = $this->get('count');
        $source = $this->get('source');
        $quoteurl = Elements::call('Navigation/NavigationController')->getUrl('quotes');

        $columns = ['No', 'Full Names', 'Insured Entity', 'Status'];
        $rows = ['{{<div style="width:100%;text-align:center">'
            . '<a href="' . Url::base() . $quoteurl . '/edit/{id}">{id}</a>'
            . '</div>}}',
            '{customer}',
            '{entity}',
            '{status}',
            ];

        $dom = '<"top">rt<"bottom"p><"clear">';

        $quotestable = $this->_table('active_quotes_table', $count, $columns, $rows, $source, $dom);
        $this->set('activequotes', $quotestable);
    }

    public function showLeads(){
        $count = $this->get('count');
        $source = $this->get('source');
//        print_r($source);exit;
        $quoteurl = Elements::call('Navigation/NavigationController')->getUrl('quotes');

        $columns = ['No', 'Date Created', 'Full Names', 'Product', 'Status', 'Actions'];
        $rows = ['{{
                <div style="width:100%;text-align:center">
                    <a href="' . Url::base() . $quoteurl . '/edit/{quote_no}">{quote_no}</a>
                </div>
            }}',
            '{datetime}',
            '{name}',
            '{products_id}',
            '{status}',
            '{actions}'
        ];
        $dom = '<"top">rt<"clear">';

        $leads = $this->leadsTable('leads', $count, $columns, $rows, $source, $dom);
        $this->set('leads', $leads);

        $modal_settings = [
            'formid' => 'assign-agent',
            'id' => 'assign-agent-modal',
            'role' => 'dialog',
            'title' => 'Link an Agent',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Attach' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        echo $assign = Overlays::Modal($modal_settings);
    }

    private function leadsTable($name, $count, array $columns, array $rows, $source, $dom)
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
            'ordering' => [
                'No' => 'desc'
            ],
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

    public function generateIncompleteTable()
    {

        $this->url = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'customers']);

        $count = $this->get('count');
        $source = $this->get('source');

        $columns = ['Full Name', 'Last Step', 'Data', 'Date'];
        $rows = [
            '{{<a href="' . Url::base() . $this->url . '/show/{_id}">{fullname}</a>}}',
            '{laststep}',
            '{{<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/data_icon.png" '
            . Notifications::tooltip('View quotation data entered by user') . '>}}',
            '{datetime}'
        ];
        $dom = '<"top"f>rt<"bottom"p><"clear">';

        $minitable = $this->_table('incompletetable', $count, $columns, $rows, $source, $dom);
        $this->set('incomplete_table', $minitable);
    }

    /**
     * Processes the quotes display section
     *
     * @depends Quotes/QuotesController->showQuotes();
     */
    public function generateTable()
    {

        $count = $this->get('count');
        $source = $this->get('source');

        $quoteurl = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'quotes']);

        $columns = ['Quote No',
            'Date Generated',
            'Full Names',
            'Insured Entity',
            'Product',
            'Linked Agent',
            'Status',
            'Source',
            'Actions'
        ];

        $rows = ['{{<a href="' . Url::base() . $quoteurl . '/edit/{id}">'
            . '<div style="width:100%;text-align:center">'
            . '{id}'
            . '</div></a>}}',
            '{date}',
            //'{{<a href="'.Url::route('/admin/customers/{action}/{id}', ['action'=>'show']).'/{customers_id}">{customer}</a>}}',
            '{customer}',
            '{entity}',
            '{product}',
            '{agent}',
            '{status}',
            '{source}',
            '{actions}'
        ];

        $dom = '<"top">rt<"bottom"p><"clear">';

        $quotestable = $this->_mainTable('quotes_table', $count, $columns, $rows, $source, $dom);
        $this->set('quotes_table', $quotestable);
    }

    private function _table($name, $count, array $columns, array $rows, $source, $dom)
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
            'ordering' => [
                'No' => 'desc'
            ],
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

    private function _mainTable($name, $count, array $columns, array $rows, $source, $dom)
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
                'Quote No' => 'desc',
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

        $table = Generate::Table($name, $schematic);

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/',
            'tools' => [
                'Archived Quotes' => [
                    'path' => '/admin/quotes/showarchivedquotes'
                ],
                'search' => [
                    'title' => 'Quotes Filter Form',
                    'form' => [
                        'preventjQuery' => TRUE,
                        'method' => 'post',
                        'action' => '/admin/quotes/search',
                        'controls' => [
                            'Full Name' => ['text', 'name', ''],
                            'Product Type' => ['select', 'qtype', '', [
                                '' => 'Select Product Type',
                                'motor' => 'Motor',
                                'medical' => 'Medical',
                                'travel' => 'Travel',
                                'domestic_package' => 'Domestic Package'
                            ]],
                            'Quote Status' => ['select', 'status', '', [
                                '' => 'Select Quote Status',
                                'new' => 'New',
                                'pending' => 'Response Pending',
                                'policy_pending' => 'Policy Pending',
                                'policy_created' => 'Complete',
                                'rejected' => 'Rejected'
                            ]]
                        ],
                        'map' => [2, 1]
                    ]
                ],
                'add' => [
                    'path' => '/admin/quotes/add'
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
                    'using' => ['{id}' => '{customer}']
                ]
            ]
        ];

        if (Input::post('printer')) {
            $table->printOutput();
        } else {
            $table->buildTools($tools); //->assignPanel('search');
            $maintable = $table->render(TRUE);

            return $maintable;
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

    public function getQuoteList($list)
    {

        $select = '<select class="control" id="quotes" name="quotes">';

        foreach ($list as $item) {
            $select .= '<option value="' . $item['id'] . '">' . ucfirst($item['type']) . ' Quote Ref No: ' . $item['refno'] . ' dated ' . date('d-M-Y', $item['datetime']) . ', for ksh ' . number_format($item['amount'], 2) . '</option>';
        }

        $select .= '</select>';

        echo $select;
    }

    /**
     * Creates the full quotation form
     *
     * @param type $agents
     * @param type $products
     * @param type $insurers
     */
    public function addQuote($agents, $products, $insurers, $customer = null)
    {

        if (!is_null($customer)) {

            $controls = [
                '{id}' => ['hidden', 'customerid', $customer->id],
                '{status}' => ['hidden', 'status', 'new'],
                'Date Generated' => ['date', 'dategen', date('d F Y', time()), ['format' => 'd F Y']],
                'Linked Agent' => ['select', 'agent', '', $agents],
                'Customer Name' => ['text', 'customer', $customer->name, ['class' => 'form-control', 'overwrite' => TRUE]],
                'Email Address' => ['text', 'email', $customer->email],
                'Telephone' => ['text', 'phone', $customer->mobile_no],
                'Select Product' => ['select', 'product', '', $products],
                '{insurers}' => ['select', 'insurers', '', $insurers],
                '{submit}' => ['submit', 'btnsubmit', 'Create Quote']
            ];
        } else {

            $controls = [
                '{id}' => ['hidden', 'customerid', ''],
                '{status}' => ['hidden', 'status', 'new'],
                'Date Generated' => ['date', 'dategen', date('d F Y', time()), ['format' => 'd F Y']],
                'Linked Agent' => ['select', 'agent', '', $agents],
                'Customer Name' => ['text', 'customer', '', ['class' => 'form-control', 'overwrite' => TRUE]],
                'Email Address' => ['text', 'email', ''],
                'Telephone' => ['text', 'phone', ''],
                'Select Product' => ['select', 'product', '', $products],
                '{insurers}' => ['select', 'insurers', '', $insurers],
                '{submit}' => ['submit', 'btnsubmit', 'Save Quote']
            ];
        }

        $quote_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/quotes/save',
            'controls' => $controls,
            'validation' => [
                'agent' => [
                    'required' => 'Please select the agent to be linked with the quote'
                ],
                'customer' => [
                    'required' => 'Please enter the customer names'
                ],
                'product' => [
                    'required' => 'Please select insurance product'
                ]
            ]
        ];

        $qform = Generate::Form('quoteform', $quote_schematic);

        $vars = [
            'status' => 'New',
            'default_currency' => 'ksh'
        ];

        $quoteform = $qform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'quotes' . DS . 'views' . DS . 'panels' . DS . 'add' . DS . 'fancyform.php', TRUE, $vars);

        $this->set('quoteadd', $quoteform);
        $this->setViewPanel('add' . DS . 'addquotemine');
    }

    public function addQuoteForPolicy($agents, $products, $insurers, $customer = null)
    {
        $controls = [
            '{id}' => ['hidden', 'customerid', $customer],
            '{status}' => ['hidden', 'status', 'new'],
            'Date Generated' => ['date', 'dategen', date('d F Y', time()), ['format' => 'd F Y']],
            'Linked Agent' => ['select', 'agent', '', $agents],
//            'Customer Name' => ['text', 'customer', $customer->name, ['class' => 'form-control', 'overwrite' => TRUE]],
            'Email Address' => ['text', 'email', $customer->email],
            'Telephone' => ['text', 'phone', $customer->mobile_no],
            'Select Product' => ['select', 'product', '', $products],
            '{insurers}' => ['select', 'insurers', '', $insurers],
            '{submit}' => ['submit', 'btnsubmit', 'Save Quote']
        ];

        $quote_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/quotes/save',
            'controls' => $controls,
            'validation' => [
                'agent' => [
                    'required' => 'Please select the agent to be linked with the quote'
                ],
                'customer' => [
                    'required' => 'Please enter the customer names'
                ],
                'product' => [
                    'required' => 'Please select insurance product'
                ]
            ]
        ];

        $qform = Generate::Form('quoteform', $quote_schematic);

        $vars = [
            'status' => 'New',
            'default_currency' => 'ksh'
        ];

        return $qform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'quotes' . DS . 'views' . DS . 'panels' . DS . 'add' . DS . 'policyfancyform.php', TRUE, $vars);
    }

    /**
     * Creates the edit quotation form
     * @param type $quote
     * @param type $agents
     * @param type $products
     * @param type $insurers
     */
    public function editQuote($quote, $agents, $products, $insurers, $forms)
    {

        //generate quote edit form
        $customer = json_decode($quote->customer_info);

        $quote_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/quotes/save',
            'controls' => [
                '{id}' => ['hidden', 'id', $quote->id],
                '{customerid}' => ['hidden', 'customerid', $quote->customers_id],
                '{status}' => ['hidden', 'status', $quote->status],
                'Date Generated' => ['date', 'dategen', date('d F Y', $quote->datetime), ['format' => 'd F Y']],
                'Linked Agent' => ['select', 'agent', $quote->insurer_agents_id, $agents],
                'Customer Name' => ['text', 'customer', $customer->customer, ['class' => 'form-control', 'overwrite' => TRUE]],
                'Email Address' => ['text', 'email', $customer->email],
                'Telephone' => ['text', 'phone', $customer->phone],
                'Select Product' => ['select', 'product', $quote->products_id, $products],
                '{insurers}' => ['select', 'insurers', '', $insurers, ['disabled' => 'disabled']],
                '{submit}' => ['submit', 'btnsubmit', 'Save Quote']
            ],
            'validation' => [
                'agent' => [
                    'required' => 'Please select the agent to be linked with the quote'
                ],
                'customer' => [
                    'required' => 'Please enter the customer names'
                ],
                'product' => [
                    'required' => 'Please select insurance product'
                ]
            ]
        ];

        $qform = Generate::Form('quoteform', $quote_schematic);

        $status = [
            'new' => 'New',
            'pending' => 'Waiting for Customer Response',
            'policy_pending' => 'Pending Policy Creation',
            'policy_created' => 'Customer Policy Created',
            'rejected' => 'Customer Rejected'
        ];

        $vars = [
            'status' => ucfirst($status[$quote->status]),
            'rawstatus' => $quote->status,
            'offer' => $quote->acceptedoffer,
            'default_currency' => 'ksh',
            'productform' => $forms['product'],
            'entityform' => $forms['entity'],
            'pricing' => $forms['pricing']
        ];

        $quoteform = $qform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'quotes' . DS . 'views' . DS . 'panels' . DS . 'edit' . DS . 'editquoteform.php', TRUE, $vars);

        $this->set('id', $quote->id);
        $this->set('quoteedit', $quoteform);
        $this->set('status', $quote->status);
        $this->set('offer', $quote->acceptedoffer);

        //generate linked documents table
        if ($this->has('documents')) {

            $documents = $this->get('documents');
            $count = count($documents);

            $columns = ['Date Generated', 'Description', 'Type', 'File Name', ''];
            $rows = ['{time}',
                '{description}',
                '{doctype}',
                '{filename}',
                '{{<a class="smallicon" data-confirm="{filename} will be deleted. Are you sure?" href="' . Url::base() . '/admin/quote/deletedoc/{quoteid}/{id}" >'
                . '<img ' . Notifications::tooltip('Delete {type}') . ' src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'];

            $dom = '<"top">rt<"bottom"p><"clear">';

            $docstable = $this->_minitable('docstable', $count, $columns, $rows, $documents, $dom);

            $this->set('doccount', $count);
            $this->set('linkeddocs', $docstable);
        }

        $modal_settings = [
            'id' => 'adddocument',
            'formid' => 'importform',
            'role' => 'dialog',
            'title' => 'Upload Quote',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];

        $form = Overlays::Modal($modal_settings);

        $this->set('uploadform', $form);
        $this->setViewPanel('edit' . DS . 'editquotepanel');
    }

    public function mailQuote($quote, $customer, $products, $agent)
    {

        $names = ucwords(strtolower($customer->customer));

        $preview_link_content = '<p>Dear ' . $names . ',</p><p>Hope this finds you well.</p><p>We have prepared offer no. ' . $quote->id . ' for you. To review the offer,please click on the link below:</p><a href=\"' . Url::base() . Url::route('/quotes/previewquote/{id}/{view}', ['id' => Help::encrypt($quote->id), 'view' => Help::encrypt('external')]) . '\">Quote Preview</a><p>If you have any questions regarding the offer, please don\'t hesitate to contact me.</p><p>Best regards,<br/>' . $agent->names . '<br/>Email: ' . $agent->email_address . '<br/>Telephone: ' . $agent->telephone_number;
        $pdf_content = '<p>Dear ' . $names . ',</p><p>Hope this finds you well.</p><p>We have prepared offer no. ' . $quote->id . ' for you. The offer is in the PDF attached to this email.</p></p>If you have any questions regarding the offer, please don\'t hesitate\nto contact me.</p><p>Best regards,<br/>' . $agent->names . '<br/>Email: ' . $agent->email_address . '<br/>Telephone: ' . $agent->telephone_number . '</p>';

        //get own company
        $owncompany = Elements::call('Companies/CompaniesController')->ownCompany(TRUE);

        $email_schema = [
            'preventjQuery' => TRUE,
            'preventZebraJs' => TRUE,
            'method' => 'POST',
            'action' => '/ajax/admin/quotes/sendemail/' . $quote->id,
            'controls' => [
                '{id}' => ['hidden', 'id', $quote->id],
                '{customer}' => ['hidden', 'customer_name', $names],
                '{replyname}' => ['hidden', 'replyname', $agent->names],
                '{replyto}' => ['hidden', 'replyto', $agent->email_address],
                '{owncompany}' => ['hidden', 'owncompany', $owncompany->name],
                '{ownemail}' => ['hidden', 'ownemail', $owncompany->email_address],
                'Send Mode*' => ['select', 'sendmode', '', [
                    '1' => 'Email linking to direct quote page',
                    '2' => 'Email with Quote PDF attachment'
                ], ['class' => 'modal_required']],
                'Email Address*' => ['text', 'email', $customer->email, ['class' => 'modal_required']],
                'Subject*' => ['text', 'subject', 'Your ' . $products['name'] . ' Insurance Offer: Please review your quotation', ['class' => 'modal_required']],
                'Message*' => ['textarea', 'content', '', ['class' => 'email_content modal_required']]
            ]
        ];

        $emailform = Generate::Form('emailform', $email_schema)->render('vertical', TRUE);

        $modal_settings = [
            'formid' => 'emailform',
            'role' => 'dialog',
            'title' => 'Send Email Quotation',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Send Quotation' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addform = Overlays::ModalDialog($modal_settings, $emailform, true);

        $this->set('id', $quote->id);
        $this->set('addform', $addform);
        $this->set('preview_link_content', $preview_link_content);
        $this->set('pdf_content', $pdf_content);

        $this->setViewPanel('email-quote-form');
    }

    public function markForm($quote, $product, $offers)
    {

        $email_schema = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/ajax/admin/quotes/savestatus/' . $quote->id,
            'controls' => [
                '{redirect}' => ['hidden', 'redirect', 'true'],
                '{sender}' => ['hidden', 'sender', 'quotes'],
                '{id}' => ['hidden', 'id', $quote->id],
                'Customer Response*' => ['select', 'response', '', [
                    'policy_pending' => 'Customer Accepted',
                    'rejected' => 'Customer Rejected'
                ], ['class' => 'modal_required']],
                'Accepted Offer for ' . $product['name'] . ' Cover*' => ['select', 'offer', '', $offers, ['class' => 'modal_required']],
                'Create Policy Now?' => ['checkbox', 'confirm_create', 'yes', ['style' => 'float:left; margin-right: 10px;']]
            ]
        ];

        $emailform = Generate::Form('statusform', $email_schema)->render('vertical', TRUE);

        $modal_settings = [
            'formid' => 'statusform',
            'role' => 'dialog',
            'title' => 'Customer Status Form',
            'buttons' => [
                'Create Customer Policy' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary pull-left',
                    'id' => 'create_button',
                    'disabled' => 'disabled'
                ],
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Customer Status' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addform = Overlays::ModalDialog($modal_settings, $emailform, true);

        $this->set('id', $quote->id);
        $this->set('statusform', $addform);

        $this->setViewPanel('statusform');
    }

    public function createEmailAttachment($file)
    {

        $filesize = Help::humanFileSize($file);
        $splfile = end(explode(DS, $file));

        $formfile = '<input type="hidden" name="email_attach_filepath" value="' . $file . '" />';
        $formfile .= '<div class="email_attachment">'
            . '<table>'
            . '<tr>'
            . '<td style="padding: 0px;">'
            . '<img src="' . RELATIVE_PROJECT_PATH . '/templates/admin/images/adobe-acrobat-logo.jpg" />'
            . '</td>'
            . '<td style="padding: 0px;">'
            . '<p style="padding-top: 5px;"><strong>' . Sanitize::shorten(ucfirst($splfile), 30) . '</strong></p>'
            . '<p>' . $filesize . '</p>'
            . '</td>'
            . '</tr>'
            . '</table>'
            . '</div>';

        echo $formfile;
    }

    /**
     * Creates every individual insurer price field and table containing each price for te above quote
     * @group InsurerPricing
     * @depends QuotesController->createInsurerPriceTable()
     * @return string Fully Rendered Prices table
     */
    public function insurerPrice($insurerid, $insurer, $default_currency, $amount = '', $recommendation = '')
    {

        return '<table class="policy table-striped insurer-price" style="width: 30%; float: left;" >
            <tr>
                <td class="heading" colspan="3"><h4>' . $insurer . '</h4></td>
            </tr>
            <tr>
                <td style="width: 40%">
                    <strong>1 installment</strong>
                </td>
                <td>
                    ' . $default_currency . '
                </td>
                <td>
                    <input type="hidden" name="insurers[]" value="' . $insurerid . '" />
                    <input style="width: 100%;" type="number" class="control text required" value="' . $amount . '" id="price_' . $insurerid . '" name="price_' . $insurerid . '" />
                </td>
            </tr>
            <tr>
                <td style="width: 40%" colspan="2">
                    Broker Recommendation <span class="required">*</span>
                </td>
                <td>
                    <input type="radio" value="' . $insurerid . '" ' . ($recommendation == $insurerid ? 'checked="checked"' : '') . ' class="required" name="recommendation" />
                </td>
            </tr>
        </table>';
    }

    public function createQuotePreview()
    {

        //create products details list
        $productslist = $this->get('product_info');

        $plist = '<ul>';
        foreach ($productslist as $details => $value) {
            $plist .= '<li><strong>' . str_replace('_', ' ', $details) . ':</strong> ' . $value . '</li>';
        }
        $plist .= '</ul>';

        $this->set('product_details_list', $plist);

        //create recommendations table
        $recoms = $this->get('recommendations');

        $recotable = '<table style="text-align:center" class="table table-striped table-bordered" width="100%" border="0" cellpadding="10">';
        foreach ($recoms as $id => $recom) {

            //loop names
            if (array_key_exists('name', $recom)) {
                $reconames[] = $recom['name'];
            }

            //loop amounts
            if (array_key_exists('amount', $recom)) {
                $recoamounts[] = 'ksh ' . number_format($recom['amount'], 2);
            }

            //set the recommended company
            if ($recom['recommended']) {
                $this->set('recom_company', $recom['name']);
            }
        }

        //names
        $recotable .= '<tr>';
        foreach ($reconames as $names) {
            $recotable .= '<td width="' . (100 / count($reconames)) . '%"><strong>' . $names . '</strong></td>';
        }
        $recotable .= '</tr>';

        //amounts
        $recotable .= '<tr>';
        foreach ($recoamounts as $amount) {
            $recotable .= '<td>' . $amount . '</td>';
        }
        $recotable .= '</tr>';

        $recotable .= '</table>';

        //get location
        $own = json_decode($this->get('own_company')->physical_details);
        $location = $own->location . ', ' . $own->citycounty;

        $this->set('location', $location);
        $this->set('recommendations', $recotable);
        $this->setViewPanel('preview-quote');
    }

    public function quotePreview($data, $kind)
    {
        $this->set('data', $data);
        $this->setViewPanel('quotations/' . $kind);
    }

    public function loadAgentAssignment($insurer_agents, $quote_no){
        $this->disable();

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/quotes/attachAgent',
            'controls' => [
                'destination' => ['hidden', 'destination', '/admin/dashboard'],
                'quote_no' => ['hidden', 'quote_no', $quote_no],
                'Link Agent' => ['select', 'agent', '', $insurer_agents]
            ],
            'validation' => [
                'agent' => [
                    'required' => 'Please choose an agent'
                ]
            ]
        ];

        $modal_settings = [
            'formid' => 'assign-agent',
            'id' => 'assign-agent-modal',
            'role' => 'dialog',
            'title' => 'Link an Agent',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Attach' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $form = Generate::Form('assign-agent', $schematic);
        $aform = $form->render('horizontal', TRUE);

        echo $assign = Overlays::ModalDialog($modal_settings, $aform);
    }
}