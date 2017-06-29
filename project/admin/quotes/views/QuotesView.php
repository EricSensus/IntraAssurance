<?php

namespace Jenga\MyProject\Quotes\Views;

use Jenga\App\Views\View;
use Jenga\App\Request\Url;
use Jenga\App\Helpers\Help;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;
use Jenga\App\Project\Core\Project;
use Jenga\App\Request\Facade\Sanitize;

use function DI\object;
use Jenga\MyProject\Elements;

class QuotesView extends View
{
    /**
     * @var bool
     */
    private $dash = false;
    /**
     * @var string
     */
    public $url;

    /**
     * Generate quotes table
     * @param bool $dashboard
     */
    public function generateMiniTable($dashboard = false)
    {
        $count = $this->get('count');
        $source = $this->get('source');
        $quoteurl = Elements::call('Navigation/NavigationController')->getUrl('quotes');
        $quote_preview_url = Url::link('/quotes/previewquote/');
        $columns = ['No', 'Full Names', 'Insured Entity', 'Status', 'Preview'];
        $rows = ['{{<div style="width:100%;text-align:center">'
            . '<a href="' . Url::base() . $quoteurl . '/edit/{id}">{id}</a>'
            . '</div>}}',
            '{customer}',
            '{entity}',
            '{status}',
            '{{<a href="' . $quote_preview_url . '{_id}" target="_blank" ><i class="fa fa-eye"></i> Preview</a>}}'
        ];

        $dom = '<"top">rt<"bottom"p><"clear">';

        $quotestable = $this->_table('active_quotes_table', $count, $columns, $rows, $source, $dom);

        $this->set('activequotes', $quotestable);
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

    /**
     * SHow the leeds
     */
    public function showLeads()
    {
        $count = $this->get('count');
        $source = $this->get('source');

        //$quoteurl = Elements::call('Navigation/NavigationController')->getUrl('quotes');

        $columns = ['No', 'Actions', 'Lead Details', '', ''];
        $rows = [
            '{quote_no}',
            '{actions}',
            '{{
                        <div style="width:100%;text-align:left">
                            {cname} got a <strong>{products_id}</strong> quote on <strong>{datetime}</strong>
                        </div>
                    }}',
            '{{<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#quotemodal" href="' . Url::link('/ajax/quotes/previewquote/') . '{encryptedno}/' . Help::encrypt('internal') . '">View</a>}}',
            '{{<a data-toggle="modal" data-target="#confirmquotemodal" class="btn btn-default btn-sm" title="Mark Customer Response" href="' . SITE_PATH . '/ajax/admin/quotes/internalacceptquote/{quote_no}">'
            . 'Confirm Quote'
            . '</a>}}'
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

        echo Overlays::Modal($modal_settings);
    }

    /**
     * SHow leeds table
     * @param $name
     * @param $count
     * @param array $columns
     * @param array $rows
     * @param $source
     * @param $dom
     * @return \Jenga\App\Html\type
     */
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

        $table->buildShortcutMenu('{actions}', 'mouseover', [

            function ($customers_id, $name, $insurer_agents_id) {
                return '<li><a href="' . Url::base() . '/admin/customers/show/' . $customers_id . '"><i class="fa fa-address-book-o" aria-hidden="true"></i> View ' . $name .
                    (!empty($insurer_agents_id) ? ' (Existing)' : ' (New)')
                    . '</a></li>';
            },
            '<li class="divider"></li>',
            function ($status, $quote_no, $insurer_agents_id) {

                if ($status == 'New') {
                    return '<li><a title="Link an Agent" class="dropdown-item" data-toggle="modal" data-target="#assign-agent-modal" href="' . Url::link('/admin/leads/assignAgent/') . $quote_no . '">
                            <i class="fa fa-hand-lizard-o"></i> Link to an Agent</a>
                          </li>';
                } else {
                    return '<li><a id="quo_' . $quote_no . '" class="dropdown-item" data-toggle="modal" data-target="#addtaskmodal" href="' . Url::link('/admin/leads/createTask/') . Help::encrypt($insurer_agents_id) . '">'
                        . '<i class="fa fa-eye"></i> Create a New Task</a>'
                        . '</li>';
                }
            },
            '<li class="divider"></li>',

            function ($quote_no) {
                return '<li><a class="dropdown-item" data-toggle="modal" data-target="#quotemodal" href="' . Url::link('/ajax/quotes/previewquote/') . Help::encrypt($quote_no) . '/' . Help::encrypt('internal') . '"><i class="fa fa-eye"></i> View Quote</a></li>';
            },

            '<li>
                <a data-toggle="modal" data-target="#confirmquotemodal" class="dropdown-item" title="Mark Customer Response" href="' . SITE_PATH . '/ajax/admin/quotes/internalacceptquote/' . '{quote_no}">
                    <i class="fa fa-check-square-o"></i> Confirm Quote
                </a>
            </li>',
            '<li class="divider"></li>',
            function ($quote_no) {
                return '<li><a class="dropdown-item text-danger" data-confirm="Quote No.' . $quote_no . ' will be deleted. Are you sure?" title="Delete Quote" href="' . SITE_PATH . '/ajax/admin/quotes/delete/' . $quote_no . '">'
                    . '<i class="fa fa-trash-o"></i> Delete Quote</a></li>';
            }
        ]);

        $minitable = $table->render(TRUE);

        return $minitable;
    }

    /**
     * Incomplete table for policy
     */
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
     * @depends QuotesBlueprint/QuotesController->showQuotes();
     */
    public function generateTable($dash = false)
    {
        $this->dash = $dash;

        $count = $this->get('count');
        $source = $this->get('source');

        $quoteurl = Elements::call('Navigation/NavigationController')->getUrl('quotes');

        $columns = ['Actions',
            'Quote No',
            'Date Generated',
            'Full Names',
            'Insured Entity',
            'Product',
            'Status',
            'Premium'
            //'Source',

        ];

        $rows = ['{actions}',
            '{{<a href="' . $quoteurl . '/edit/{id}">'
            . '<div style="width:100%;text-align:center">'
            . '{id}'
            . '</div></a>}}',
            '{date}',
            '{{<a href="' . Url::link('/admin/customers/show') . '/{customers_id}">{customer}</a>}}',
            '{entity}',
            '{product}',
            '{status}',
            '{{<div style="text-align:right">{premium}</div>}}',
            //'{source}'
        ];

        if ($dash) {
            $columns = $this->removeValue('Full Names', $columns);
            $rows = $this->removeValue('{{<a href="' . Url::link('/admin/customers/show') . '/{customers_id}">{customer}</a>}}', $rows);
        }

        $dom = '<"top">rt<"bottom"p><"clear">';

        $quotestable = $this->_mainTable('quotes_table', $count, $columns, $rows, $source, $dom);

        $this->set('quotes_table', $quotestable);
    }

    /**
     * @param $needle
     * @param array $array
     * @return array
     */
    public function removeValue($needle, $array = array())
    {
        $key = array_search($needle, $array);
        if ($key != false)
            unset($array[$key]);

        return $array;
    }

    /**
     * Transform table
     * @param $name
     * @param $count
     * @param array $columns
     * @param array $rows
     * @param $source
     * @param $dom
     * @return \Jenga\App\Html\type
     */
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

    /**
     * Get the main table for display
     * @param $name
     * @param $count
     * @param array $columns
     * @param array $rows
     * @param $source
     * @param $dom
     * @return \Jenga\App\Html\type
     */
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

        $products = $this->get('products');
        $statuslist = [
            'new' => 'New',
            'pending' => 'Response Pending',
            'policy_pending' => 'Policy Pending',
            'policy_created' => 'Complete',
            'rejected' => 'Rejected'
        ];

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
                            'Product Type' => ['select', 'qtype', '', []],
                            'Quote Status' => ['select', 'status', '', $statuslist]
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
                    'using' => ['{id}' => '{customer},{product},{premium}']
                ]
            ]
        ];

        if (Input::post('printer')) {
            $table->printOutput();
        } else {

            if (!$this->dash) {
                $table->buildTools($tools); //->assignPanel('search');
            }

            $table->buildShortcutMenu('{actions}', 'mouseover', [
                function ($id) {

                    return '<li><a class="dropdown-item" data-toggle="modal" data-target="#quotemodal" href="' . Url::link('/ajax/quotes/previewquote/') . Help::encrypt($id) . '/' . Help::encrypt('internal') . '">'
                        . '<i class="fa fa-file-text-o"></i> Open Quote</a></li>'
                        . '<li class="divider"></li>'
                        . '<li><a class="dropdown-item" title="Edit Quote" href="' . SITE_PATH . '/admin/quotes/edit/' . $id . '">'
                        . '<i class="fa fa-edit"></i> Edit Quote</a></li>';
                },
                '<li class="divider"></li>',
                function ($id) {
                    return '<li><a target="_blank" class="dropdown-item" title="Preview Quote" href="' . SITE_PATH . '/quotes/previewquote/' . Help::encrypt($id) . '/' . Help::encrypt('internal') . '">'
                        . '<i class="fa fa-eye"></i> Open Quote in Preview</a></li>';
                },
                function ($status, $id) {
                    if ($status != 'Accepted') {

                        if ($this->user()->is('customer')) {
                            $response = 'Respond to this Quote';
                        } else {
                            $response = 'Mark Customer Response';
                        }

                        return '<li><a data-toggle="modal" data-target="#confirmquotemodal" class="dropdown-item" title="Mark Customer Response" href="' . SITE_PATH . '/ajax/admin/quotes/internalacceptquote/' . $id . '">'
                            . '<i class="fa fa-check-square-o"></i> ' . $response . '</a></li>';
                    }
                    return null;
                },
                '<li class="divider"></li>',
                function ($id) {
                    return '<li><a data-toggle="modal" data-target="#emailmodal" class="dropdown-item" title="Email Quote" href="' . SITE_PATH . '/ajax/admin/quotes/emailquote/' . $id . '">'
                        . '<i class="fa fa-envelope"></i> Email Quote</a></li>';
                },
                function ($id) {
                    return '<li><a target="_blank" class="dropdown-item" title="Create PDF Quote" href="' . SITE_PATH . '/ajax/admin/quotes/pdfquote/' . $id . '">'
                        . '<i class="fa fa-file-pdf-o fa-lg"></i> Generate PDF from Quote</a></li>';
                },
                function ($id) {
                    return '<li><a  class="dropdown-item" title="Archive Quote" href="' . SITE_PATH . '/admin/quotes/archiveQuote/' . $id . '">'
                        . '<i class="fa fa-archive"></i> Archive Quote</a></li>';
                },
                function ($id, $status) {

                    if ($status != 'Accepted') {
                        return '<li class="divider"></li>'
                            . '<li><a class="dropdown-item text-danger" data-confirm="Quote No.' . $id . ' will be deleted. Are you sure?" title="Delete Quote" href="' . SITE_PATH . '/ajax/admin/quotes/delete/' . $id . '">'
                            . '<i class="fa fa-trash-o"></i> Delete Quote</a></li>';
                    }
                },
            ]);

            $maintable = $table->render(TRUE);

            return $maintable;
        }
    }

    /**
     * Minimal table with mapped attributes
     * @param $name
     * @param $count
     * @param array $columns
     * @param array $rows
     * @param $source
     * @param $dom
     * @param null $tools
     * @return \Jenga\App\Html\type
     */
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

        if (!is_null($tools)) {
            $table->buildTools($tools);
        }

        $minitable = $table->render(TRUE);

        return $minitable;
    }

    /**
     * Get the quote list
     * @param $list
     */
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
     * @param null $customer
     */
    public function addQuote($agents, $products, $insurers, $customer = null)
    {
        if (!empty($customer)) {
            $controls = [
                '{id}' => ['hidden', 'customerid', $customer->id],
                '{status}' => ['hidden', 'status', 'new'],
                'Date Generated' => ['date', 'dategen', date('d F Y', time()), ['format' => 'd F Y']],
                'Name' => ['text', 'customer', $customer->name, ['class' => 'form-control', 'readonly' => 'readonly']],
                'Email Address' => ['text', 'email', $customer->email, ['readonly' => 'readonly']],
                'Telephone' => ['text', 'phone', $customer->mobile_no, ['readonly' => 'readonly']],
                'Select Product' => ['select', 'product', '', $products],
                '{insurers}' => ['select', 'insurers', '', $insurers],
                '{submit}' => ['submit', 'btnsubmit', 'Create Quote']
            ];
        } else {

            $controls = [
                '{id}' => ['hidden', 'customerid', ''],
                '{status}' => ['hidden', 'status', 'new'],
                'Date Generated' => ['date', 'dategen', date('d F Y', time()), ['format' => 'd F Y']],
                'Linked Agent' => ['select', 'agent', '', $agents, ['required' => '']],
                'Customer Name' => ['select', 'customer', '', [], ['class' => 'form-control', 'id' => 'customer']],
                'Email Address' => ['text', 'email', ''],
                'Telephone' => ['text', 'phone', ''],
                'Select Product' => ['select', 'product', '', $products],
                '{insurers}' => ['select', 'insurers', '', $insurers],
                '{submit}' => ['submit', 'btnsubmit', 'Start Quote Generation', ['class' => 'form-control pull-right']]
            ];
        }

        $quote_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/quotes/save',
            'controls' => $controls,
            'validation' => [
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

        $quoteform = $qform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'quotes' . DS . 'views' . DS . 'panels' . DS . 'internal' . DS . 'quote-template.php', TRUE, $vars);

        $this->set('quoteadd', $quoteform);
        $this->setViewPanel('internal' . DS . 'full-quote-form');
    }

    /**
     * Add quote for policy
     * @param $agents
     * @param $products
     * @param $insurers
     * @param null $customer
     * @return string
     */
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

    /**
     * Confirm quote internally
     * @param array $data
     */
    public function internalConfirmQuote($data)
    {
        $this->set('data', $data);
        $this->setViewPanel('confirm-quote-form');
    }

    /**
     * Send an email of quote
     * @param $quote
     * @param $customer
     * @param $products
     * @param $agent
     */
    public function mailQuote($quote, $customer, $products, $agent)
    {

        $names = ucwords(strtolower($customer->name));
        $preview_link_content = '<p>Dear ' . $names . ',</p><p>Hope this finds you well.</p><p>We have prepared offer no. ' . $quote->id . ' for you. To review the offer,please click on the link below:</p><a href=\"' . Url::base() . Url::route('/quote/actions/{link}', ['link' => Help::encrypt($quote->id)]) . '\">Quote Preview</a><p>If you have any questions regarding the offer, please don\'t hesitate to contact me.</p><p>Best regards,<br/>' . $agent->names . '<br/>Email: ' . $agent->email_address . '<br/>Telephone: ' . $agent->telephone_number;
        $pdf_content = '<p>Dear ' . $names . ',</p><p>Hope this finds you well.</p><p>We have prepared offer no. ' . $quote->id . ' for you to review</p><p>Please click on the link below:</p><p><a href=\"' . Url::base() . Url::route('/quote/actions/{link}', ['link' => Help::encrypt($quote->id)]) . '\">Quote Preview</a></p> The offer is in the PDF attached to this email.</p></p>If you have any questions regarding the offer, please don\'t hesitate\nto contact me.</p><p>Best regards,<br/>' . $agent->names . '<br/>Email: ' . $agent->email_address . '<br/>Telephone: ' . $agent->telephone_number . '</p>';

        //get own company
        $owncompany = Elements::call('Companies/CompaniesController')->ownCompany(TRUE);
        $email_schema = [
            'preventjQuery' => TRUE,
            'preventZebraJs' => TRUE,
            'method' => 'POST',
            'action' => '/customer/quote/sendemail',
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
                'Message*' => ['textarea', 'content', '', ['class' => 'email_content modal_required']],
                '{submit}' => ['submit', 'btnsubmit', 'Send Email']
            ]
        ];
        $emailform = Generate::Form('emailform', $email_schema)->render('vertical', TRUE);

        $modal_settings = [
            'formid' => 'emailform',
            'role' => 'dialog',
            'size' => 'large',
            'title' => 'Send Email Quotation',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Send Quotation' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary mail',
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

    /**
     *
     * @param $quote
     * @param $product
     * @param $offers
     */
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

    /**
     * Create email attachment
     * @param $file
     */
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

    /**
     * Create quote preview
     * @param $data
     * @param bool $confirm
     */
    public function createQuotePreview($data, $confirm = false)
    {
        $object = (object)$data;

        $this->set('info', $data);
        $this->set('confirm', $confirm);
        $this->set('additional_covers', $this->covers($object->product));

        $this->setViewPanel('previews/' . $object->product_info->alias);
    }

    /**
     * Covers
     * @param $object
     * @return string
     */
    private function covers($object)
    {
        $primitives = ['windscreen', 'riotes', 'audio', 'passenger', 'terrorism'];
        $return = [];
        foreach ($primitives as $one) {
            if ($object->{$one} == 'yes') {
                $return[] = ucfirst($one);
            }
        }
        if (empty($return)) {
            return 'N/A';
        }
        return implode(',', $return);
    }

    /**
     * Get quote preview
     * @param array $data
     */
    public function quotePreview($data)
    {
        $this->set('data_array', $data);
        $this->setViewPanel('quotations/' . $data[0]->product->alias);
    }

    /**
     * Load assignments for agent
     * @param $insurer_agents
     * @param $quote_no
     */
    public function loadAgentAssignment($insurer_agents, $quote_no)
    {
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

    /**
     * The quote wizard
     * @param $schematic
     * @param $customer
     */
    public function quoteWizard($schematic, $customer)
    {
        $schematic = $this->oneSchematic($schematic);
        $form = Generate::Form('quoteform', $schematic);
        $this->set('form', $form->render('horizontal', TRUE));
        $this->set('customer_data', $customer);
        $this->setViewPanel('internal/tab-form');
    }

    /**
     * Wizard to edit quote
     * @param $schematic
     * @param $customer
     * @param $quote
     */
    public function quoteEditWizard($schematic, $customer, $quote)
    {
        $schematic = $this->oneSchematic($schematic);
        $form = Generate::Form('quoteform', $schematic);
        $this->set('form', $form->render('horizontal', TRUE));
        $this->set('customer_data', $customer);
        $this->set('_quote', $quote);
        $this->setViewPanel('edit/tab-form');
    }

    /**
     * A full schematic for the whole product input fields [Step1, step 2 and step3]
     * @param $schematic
     * @return array
     */
    private function oneSchematic($schematic)
    {
        $controls = [];
        $maps = [];
        $count = 1;
        foreach ($schematic as $schema) {
            if (empty($controls)) {
                $controls = [
                    '{specialnote' . 0 . '}' =>
                        ['note', 'specialnote0', "<span class='killer0'></span>"]
                ];
                $maps[] = 1;
            }
            unset($schema['controls']['{submit}']);
            $schema['controls']['{specialnote' . $count . '}'] = ['note', 'specialnote' . $count, "<span class='killer$count'></span>"];
            $controls = array_merge($controls, $schema['controls']);
            $maps = array_merge($maps, $schema['map']);
            $count++;
        }
        return [
            'preventjQuery' => true,
            'engine' => 'bootstrap',
            'validator' => 'parsley',
            'css' => false,
            'method' => 'post',
            //  'action' => '/admin/myquote/save/motor',
            'attributes' => ['data-parsley-validate' => ''],
            'controls' => $controls,
            'map' => $maps];
    }

    /**
     * Loading tabs
     * @param $schematics
     * @param array $customer
     * @param $pdt
     */
    public function loadTabs($schematics, $customer = array(), $pdt)
    {
        foreach ($schematics as $key => $schematic) {
            $this->set('tab' . $key, $schematic);
        }

        if ($pdt == 'medical') {
            $entity_data = Elements::call('Medical/MedicalController')
                ->view->createEntityDataArr($this->get('entity_data'));
        } else if ($pdt == 'travel') {
            $entity_data = Elements::call('Travel/TravelController')
                ->view->createEntityDataArr($this->get('entity_data'));
        }

        $this->set('entity_data_arr', $entity_data);
        $this->set('customer_data', $customer);
        $this->setViewPanel('internal/' . $pdt);
    }

    /**
     * @param $tracking
     */
    public function showUnfinished($tracking)
    {
        $schematic = [
            'table' => [
                'width' => '100%',
                'class' => 'table table-striped dataTable no-footer',
                'border' => 0,
                'cellpadding' => 5
            ],
            'dom' => '<"top">rt<"bottom"p><"clear">',
            'columns' => ['Actions', 'Name', 'Email', 'Phone', 'Product', 'Step', 'Start', 'Updated'],
            'ordering' => [
                'Name' => 'asc',
                'disable' => 0
            ],
            'row_count' => count($tracking),
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $tracking
            ],
            'row_variables' => ['{actions}', '{customer}', '{email}', '{phone}', '{product}', '{progress}', '{begin}', '{modified}']
        ];
        $table = Generate::Table('unfinished_quotes', $schematic);
        $table->buildShortcutMenu('{actions}', 'mouseover', [
            function ($quote_id) {
                return '<li><a target="_blank" class="dropdown-item" title="View Info" href="' . SITE_PATH . '/quotes/previewquote/' . Help::encrypt($quote_id) . '/' . Help::encrypt('external') . '">'
                    . '<i class="fa fa-eye"></i> View Info</a></li>';
            },
        ]);
        $render = $table->render(true);
        $this->set('table', $render);
    }
}
