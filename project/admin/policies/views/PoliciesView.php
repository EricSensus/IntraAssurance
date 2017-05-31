<?php

namespace Jenga\MyProject\Policies\Views;

use Jenga\App\Helpers\Help;
use Jenga\App\Html\Generate;
use Jenga\App\Request\Facade\Sanitize;
use Jenga\App\Request\Input;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\View;
use Jenga\MyProject\Elements;

/**
 * Class PoliciesView
 *
 *
 * @package Jenga\MyProject\Policies\Views
 */
class PoliciesView extends View
{
    private $dash = false;

    public function generateTable($mypolicies = false, $dash = false)
    {
        $this->dash = $dash;

        $count = $this->get('count');
        $source = $this->get('source');
        $search = $this->get('search');

        $condition = $this->get('condition');

        if (!is_null($condition)) {

            //create the return url from the Navigation element
            $url = Elements::call('Navigation/NavigationController')->getUrl('policies');

            $this->set('alerts', Notifications::Alert($condition
                . '<a data-dismiss="alert" class="close" href="' . $url . '">×</a>', 'info', TRUE, TRUE));
        }

        $suburl = Elements::call('Navigation/NavigationController')->getUrl('customers');
        $polurl = Elements::call('Navigation/NavigationController')->getUrl('policies');

        $columns = ['Actions', 'Created', 'Policy No', 'Validity', 'Issue Date', 'Customer', 'Product', 'Premium',
//            'Issue', 'Renew'
        ];

        $rows = [
            '{actions}',
            '{created}',
            '{{<a href="' . $polurl . '/edit/{id}">{policyno}</a>}}',
            '{validity}',
            '{issuedate}',
            '{{<a href="' . $suburl . '/show/{customers_id}">{customer}</a>}}',
            '{product}',
            '{premium}',
//            '{image}',
//            '{renew}'
        ];

        if($mypolicies) {
            $columns = $this->removeKey('Issue', $columns);
            $rows = $this->removeKey('{image}', $rows);
        }

        $dom = '<"top">rt<"bottom"p><"clear">';
        $policiestable = $this->_mainTable('policies_table', $count, $columns, $rows, $source, $dom, $search, $mypolicies);

        $modal_settings = [
            'id' => 'emailmodal',
            'formid' => 'email-policy-form',
            'role' => 'dialog',
            'title' => 'Email Policy',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];

        $form = Overlays::Modal($modal_settings);

        $renewal_settings = [
            'id' => 'renewal_modal',
            'formid' => 'renewal_modal_form',
            'role' => 'dialog',
            'title' => 'Renew Policy',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];

        $renewal_container = Overlays::Modal($renewal_settings);

        $this->set('mailmodal', $form);
        $this->set('renewal_container', $renewal_container);
        $this->set('policies_table', $policiestable);
        $this->set('mypolicies', $mypolicies);
    }

    public function removeKey($needle, $array = array()){
        $key = array_search($needle, $array);
        if($key != false)
            unset($array[$key]);

        return $array;
    }

    private function _mainTable($name, $count, array $columns, array $rows, $source, $dom, $searchform = '', $mypolicies = false)
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

        $table->buildShortcutMenu('{actions}', 'mouseover', [
            function($id) {
                return '<li><a href="' . Url::link('/admin/policies/edit/'. $id) . '"><i class="fa fa-edit"></i> Open Policy</a></li>';
            },
            '<li class="divider"></li>',
            function($id) {
                return '<li><a href="' . Url::link('/admin/policies/processissue/' . $id) . '"><i class="fa fa-check-square"></i> Issue Policy</a></li>' .
                    '<li>
                        <a href="' . Url::link('/ajax/admin/policies/renewpolicy/' . $id) . '"
                            data-target="#renewal_modal" data-toggle="modal">
                        <i class="fa fa-refresh"></i> Renew Policy</a></li>'
                    .'<li>
                        <a href="'. Url::link('/admin/policies/downloaddocs/' . $id) .'" data-toggle="modal" data-target="#download-docs">
                            <i class="fa fa-download"></i> Download Related Docs </a>
                    </li>';
            },
            '<li class="divider"></li>',
            function($id) {
                return '<li><a target="_blank" href="' . Url::link('/admin/policies/previewpolicy/' . $id) . '"><i class="fa fa-eye"></i> Preview Policy</a></li>' .
                    '<li>
                        <a href="' . Url::link('/ajax/admin/policies/emailpolicy/' . $id) . '" 
                            data-toggle="modal" data-target="#emailmodal">
                                <i class="fa fa-envelope"></i> Email Policy</a></li>'.
                    '<li><a href="' . Url::link('/ajax/admin/policies/pdfpolicy/' . $id) . '"><i class="fa fa-file-pdf-o"></i> Generate PDF</a></li>';
            },
            '<li class="divider"></li>',
            function($id){
                return '<li><a href="javascript::void();" onclick="deletePolicy();">'
                . '<i class="fa fa-check-square-o"></i> Delete Policy'
                . '</a></li>'
                . '<form id="del_policy" action="'. Url::link('/admin/policies/deletesingle') .'" method="post">'
                . '<input type="hidden" name="id" value="'.$id.'"/>'
                . '</form>';
            }
        ]);

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/',
            'tools' => [
                'issue policy' => [
                    'path' => Url::link('/admin/policies/issuebatch'),
                    'using' => ['{id}', '{customer}', '{status}'],
                    'confirm' => true
                ],
                'Renew' => [
                    'path' => Url::link('/admin/policies/renewbatch'),
                    'using' => ['{id}', '{customer}', '{status}'],
                    'confirm' => true
                ],
                'search' => $searchform,
                'add' => [
                    'path' => '/admin/policies/add'
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

        // customer tools
        if ($mypolicies) {
            $tools = [
                'images_path' => RELATIVE_PROJECT_PATH . '/templates/admin/images/icons/',
                'tools' => ['search' => $searchform]
            ];
        }

        if (Input::post('printer')) {

            $table->printOutput();
        } else {
            if(!$this->dash)
                $table->buildTools($tools); //->assignPanel('search');
            $maintable = $table->render(TRUE);

            return $maintable;
        }
    }

    public function generateMiniTable($from)
    {

        $count = $this->get('count');
        $source = $this->get('source');
        $search = $this->get('search');

        $condition = $this->get('condition');

        if (!is_null($condition)) {

            $this->set('alerts', Notifications::Alert($condition, 'info', TRUE, TRUE));
        }

        $suburl = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'customers']);
        $polurl = Elements::load('Navigation/NavigationController@getUrl', ['alias' => 'policies']);

        $columns = ['Policy No', 'Validity', 'Customer', 'Issue'];

        $rows = ['{{<a href="' . SITE_PATH . $polurl . '/edit/{id}">{policyno}</a>}}',
            '{validity}',
            '{customer}',
            '{image}'];

        $dom = '<"top">rt<"bottom"p><"clear">';

        if ($from == 'unprocessed') {

            $unprocessedtable = $this->_miniTable('policiestable', $count, $columns, $rows, $source, $dom, $search);
            $this->set('unprocessedtable', $unprocessedtable);
        } elseif ($from == 'expiring') {

            $expiredtable = $this->_miniTable('expiringtable', $count, $columns, $rows, $source, $dom, $search);
            $this->set('expiringtable', $expiredtable);
        }
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
        $table->buildShortcutMenu('{actions}', 'mouseover', [
            '<li class="divider"></li>',
            '<li><a data-toggle="modal" data-target="#confirmquotemodal" class="dropdown-item" title="Mark Customer Response" href="' . SITE_PATH . '/ajax/admin/quotes/internalacceptquote/' . '{quote_no}">'
            . '<i class="fa fa-check-square-o"></i> Confirm Quote'
            . '</a></li>'
        ]);

        $minitable = $table->render(TRUE);

        return $minitable;
    }

    public function addPolicyForm($customer = null)
    {

        if (is_null($customer)) {

            $controls = [
                'Customer Name (Type Name Below)' => ['text', 'customer', '', ['class' => 'form-control', 'overwrite' => TRUE]],
            ];

//            if(!is_null($customer->quotes))
            $controls['{submit}'] = ['submit', 'btnsubmit', 'Create Policy'];
        } else {

            $controls = ['Customer Name (Type Name Below)' => ['text', 'customer', $customer->name, ['class' => 'form-control', 'overwrite' => TRUE]]];

            if (!is_null($customer->quotes)) {

                //process quotes
                foreach ($customer->quotes as $item) {

                    $select[$item['id']] = ucfirst($item['product']) . ' '
                        . 'Quote generated on ' . date('d-M-Y', $item['datetime']) . ', '
                        . 'for ksh ' . number_format($item['amount'], 2) . ', '
                        . 'covered by ' . $item['insurer'];
                }

                $controls += [
                    'Select a Quote for ' . $customer->name => ['select', 'quotes', '', $select]
                ];
            } else {

                $quotes = ['quotes' => Notifications::Alert('No quotes found for <strong>' . $customer->name . '</strong>', 'notice', true)];
            }

            $controls += ['{submit}' => ['submit', 'btnsubmit', 'Create Policy']];
        }

        $policy_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/policies/createpolicy',
            'controls' => $controls,
            'validation' => [
                'customer' => [
                    'required' => 'Please enter the customer names'
                ]
            ]
        ];

        $pform = Generate::Form('policyform', $policy_schematic);
        $fullform = $pform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'policies' . DS . 'views' . DS . 'panels' . DS . 'policyform.php', TRUE, $quotes);

        $this->set('policyadd', $fullform);
        $this->set('policyguide', $this->_policyGuide('one'));

        $this->setViewPanel('policiesaddpanel');
    }

    public function processCoverage($coverage, $premium, $edit = false)
    {
        //process entity
        $entity = $coverage['entity'];

        $table = '<table width="100%" cellpadding="10" border="0" class="policy">';

        // get the indices by product alias
        $indices = $this->getIndicesByProductAlias($this->get('product_alias'), $this->get('other_covers_no'));

        $count = 0;
        if (!is_null($entity)) {

            foreach ($entity as $property) {
                if ($count == 0) {
                    $table .= '<tr>'
                        . '<td colspan="2" class="premium"><h4><strong>' . $property['type'] . ' Details</strong></h4></td>'
                        . '</tr>';
                }


                $ent_count = 0;
                if (count($property['entity'])) {

                    foreach ($property['entity'] as $label => $entity_item) {
                        $number = $count + 1;
                        if ($ent_count == 0) {
                            $table .= '<tr><th colspan="2"><h5>' . $property['type'] . ' ' . $number . '</th></tr>';
                        }

                        $title = (isset($indices[$label])) ? $indices[$label] : $label;

                        if (!empty($entity_item)) {
                            $table .= '<tr>';
                            $table .= '<td><strong>' . ucwords(str_replace('_', ' ', $title)) . '</strong></td>';
                            $table .= '<td>' . $entity_item . '</td>';
                            $table .= '</tr>';
                        }

                        $ent_count++;
                    }
                }

                $count++;
            }
        }

        if ($edit) {
            $table .= '</table>';
            return $table;
        }

        //process product
        $products = json_decode($coverage['product'], true);
        $count = 0;

        if (!is_null($products) && !empty($products)) {
            $products = array_except($products, ['btnsubmit', 'form_step', 'step', 'request_type']);

            foreach ($products as $label => $property) {
                if ($count == 0) {
                    $table .= '<tr class="heading">'
                        . '<td colspan="2" class="premium"><h4><strong>Product Details</strong></h4></td>'
                        . '</tr>';
                }

                if (!empty($property) && isset($indices[$label])) {
                    $table .= '<tr>'
                        . '<td><strong>' . $indices[$label] . '</strong></td>'
                        . '<td>' . str_replace('_', ' ', $property) . '</td>'
                        . '</tr>';
                } else {
                    if ($this->getCoreOptionals($label)) {
                        $first2_letters = substr($label, 0, 2);

                        $key = $this->identifyOptional($first2_letters, $label);

                        $table .= '<tr>'
                            . '<td><strong>' . $key . '</strong></td>'
                            . '<td>Yes</td>'
                            . '</tr>';
                    }
                }


                $count++;
            }
        }

        $table .= '<tr><td colspan="2">&nbsp;</td></tr>';

        $amounts = $coverage['amounts'];
//        dump($amounts);
        $other_covers = $amounts->other_covers;
        if (count($other_covers)) {
//            print_r($other_covers);exit;
            foreach ($other_covers as $key => $cover) {
                $table .= '<tr class="heading">'
                    . '<td colspan="2"><h4><strong>' . $key . '</strong></h4></td>'
                    . '</tr>';

                if (count($cover)) {
                    foreach ($cover as $key => $item) {
                        $table .= '<tr>'
                            . '<td><strong>' . ucwords(str_replace('_', ' ', $key)) . '</strong></td>'
                            . '<td>' . number_format(round($item, 2), 2) . '</td>'
                            . '</tr>';
                    }
                }
            }
        }

        if (!$edit) {
            $table .= '<tr>';

            $table .= '<td><strong>P.H.C.F</strong></td>';
            $table .= '<td>' . $amounts->policy_levy . '</td>';

            $table .= '</tr>';

            $table .= '<tr>';

            $table .= '<td><strong>Tranining Levy</strong></td>';
            $table .= '<td>' . $amounts->training_levy . '</td>';

            $table .= '</tr>';

            $table .= '<tr>';

            $table .= '<td><strong>Stamp Duty</strong></td>';
            $table .= '<td>' . $amounts->stamp_duty . '</td>';

            $table .= '</tr>';

            $table .= '<tr class="last">'
                . '<td class="premium"><h4>Total</h4></td>'
                . '<td class="premium"><h4><b>' . number_format(round($amounts->total, 2), 2) . '</b></h4></td>'
                . '</tr>';
        }

        $table .= '</table>';

        return $table;
    }

    public function loadCoverDetails($amounts, $products)
    {
        $amounts = json_decode($amounts['amounts']);
        if (count($amounts)) {
            foreach ($amounts as $title => $amount) {
                if ($amount->chosen) {
                    $amounts = $amount;
                }
            }
        }


        $table = '<table style="width: 100%;">';

        //process product
        $count = 0;

        if (count($products)) {
            $products = array_except($products, ['btnsubmit', 'form_step', 'step', 'request_type']);

            foreach ($products as $label => $property) {
                $indices = $this->getIndicesByProductAlias($this->get('product_alias'));

                if ($count == 0) {
                    $table .= '<tr class="heading">'
                        . '<td colspan="2" class="premium"><h4><strong>Product Details</strong></h4></td>'
                        . '</tr>';
                }

                if (!empty($property) && isset($indices[$label])) {
                    $key = $indices[$label];

                    $table .= '<tr>'
                        . '<td><strong>' . $key . '</strong></td>'
                        . '<td>' . str_replace('_', ' ', $property) . '</td>'
                        . '</tr>';
                } else {
                    if ($this->getCoreOptionals($label)) {
                        $first2_letters = substr($label, 0, 2);

                        $key = $this->identifyOptional($first2_letters, $label);

                        $table .= '<tr>'
                            . '<td><strong>' . $key . '</strong></td>'
                            . '<td>Yes</td>'
                            . '</tr>';
                    }
                }

                $count++;
            }
        }

        $table .= '<tr><td colspan="2">&nbsp;</td></tr>';

        if (count($amounts)) {
            foreach ($amounts as $title => $amount) {
                if($amount->chosen) {
                    unset($amount->chosen);
                    // core
//                    dump($amount);exit;
                    $table .= $this->loadCore(array_except($this->amountAsArray($amounts), [
                        'stamp_duty',
                        'training_levy',
                        'policy_levy',
                        'other_covers',
                        'insurer_id',
                        'total'
                    ]));
                }
            }
        }

        $other_covers = $amounts->other_covers;
        if (count($other_covers)) {
            foreach ($other_covers as $key => $cover) {
                $table .= '<tr class="heading">'
                    . '<td colspan="2"><h4><strong>' . $key . '</strong></h4></td>'
                    . '</tr>';

                if (count($cover)) {
                    foreach ($cover as $key => $item) {
                        $table .= '<tr>'
                            . '<td><strong>' . ucwords(str_replace('_', ' ', $key)) . '</strong></td>'
                            . '<td>' . number_format(round($item, 2), 2) . '</td>'
                            . '</tr>';
                    }
                }
            }
        }

        $table .= '<tr>';

        $table .= '<td><strong>P.H.C.F</strong></td>';
        $table .= '<td>' . $amounts->policy_levy . '</td>';

        $table .= '</tr>';

        $table .= '<tr>';

        $table .= '<td><strong>Tranining Levy</strong></td>';
        $table .= '<td>' . $amounts->training_levy . '</td>';

        $table .= '</tr>';

        $table .= '<tr>';

        $table .= '<td><strong>Stamp Duty</strong></td>';
        $table .= '<td>' . $amounts->stamp_duty . '</td>';

        $table .= '</tr>';

        $table .= '<tr class="last">'
            . '<td class="premium"><h4>Total</h4></td>'
            . '<td class="premium"><h4><b>' . number_format(round($amounts->total, 2), 2) . '</b></h4></td>'
            . '</tr>';

        $table .= '</table>';

        return $table;
    }

    /**
     * Create the main policy generation page
     *
     * @depends PoliciesController->createPolicy()
     * @param object $policy
     */
    public function addPolicy($policy)
    {
        $confirm_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/policies/savepolicy',
            'controls' => [
                '{offer}' => ['hidden', 'offer', $policy->offer],
                '{policyno}' => ['hidden', 'policyno', $policy->no],
                '{_id}' => ['hidden', 'customers_id', $policy->customer->id],
                '{insurers_id}' => ['hidden', 'insurers_id', $policy->insurer['id']],
                '{customer_quotes_id}' => ['hidden', 'customer_quotes_id', $policy->quoteid],
                '{products_id}' => ['hidden', 'products_id', $policy->product['id']],
                '{status}' => ['hidden', 'status', $policy->status],
                '{code}' => ['hidden', 'code', $policy->currency_code],
                '{amount}' => ['hidden', 'amount', $policy->amount],
                '{startdate}' => ['date', 'startdate', $policy->startdate, ['format' => 'd F Y']],
                '{enddate}' => ['date', 'enddate', $policy->enddate, ['format' => 'd F Y']],
                '{submit}' => ['submit', 'btnsubmit', 'Validity and Coverage Confirmation']
            ],
            'validation' => [
                'startdate' => [
                    'required' => 'Please enter the policy start date'
                ],
                'enddate' => [
                    'required' => 'Please enter the policy end date'
                ]
            ]
        ];

        $confirmvars = [
            'policyno' => $policy->no,
            'quoteno' => $policy->quoteid,
            'dategen' => $policy->dategenerated,
            'customername' => $policy->customer->name,
            'policyemail' => $policy->customer->email,
            'product' => ucfirst($policy->product['name']),
            'insurer' => ucfirst($policy->insurer['name']),
            'quotestatus' => ucfirst($policy->quotestatus),
            'currencycode' => $policy->currency_code,
            'amount' => $policy->amount,
            'premium' => $policy->premium,
            'coverage' => $this->processCoverage($policy->coverage, $policy->premium)
        ];

        $cform = Generate::Form('confirmform', $confirm_schematic);
        $confirmform = $cform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'policies' . DS . 'views' . DS . 'panels' . DS . 'policy-add-form.php', TRUE, $confirmvars);

        $this->set('policyadd', $confirmform);
        $this->set('policyguide', $this->_policyGuide('two'));

        $this->setViewPanel('policiesaddpanel');
    }

    public function editPolicy($policy)
    {
        $readonly = [];
        $dates = 'date';
        if($this->user()->is('customer')) {
            $readonly = ['readonly' => 'readonly'];
            $dates = 'text';
        }
//        dump($readonly);exit;
        $editschematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/policies/savepolicyedit',
            'controls' => [
                '{id}' => ['hidden', 'id', $policy->id],
                '{_id}' => ['hidden', 'customers_id', $policy->customer->id],
                '{insurers_id}' => ['hidden', 'insurers_id', $policy->insurer['id']],
                '{customer_quotes_id}' => ['hidden', 'customer_quotes_id', $policy->quoteid],
                '{products_id}' => ['hidden', 'products_id', $policy->product['id']],
                '{status}' => ['hidden', 'status', $policy->status],
                '{policynumber}' => ['text', 'policynumber', $policy->policy_number, $readonly],
                '{issuedate}' => [$dates, 'issuedate', date('d F Y', $policy->issue_date), ['format' => 'd F Y'], $readonly],
                '{startdate}' => [$dates, 'startdate', date('d F Y', $policy->start_date), ['format' => 'd F Y'], $readonly],
                '{enddate}' => [$dates, 'enddate', date('d F Y', $policy->end_date), ['format' => 'd F Y'], $readonly],
                '{submit}' => ['submit', 'btnsubmit', 'Save Edits']
            ],
            'validation' => [
                'startdate' => [
                    'required' => 'Please enter the policy start date'
                ],
                'enddate' => [
                    'required' => 'Please enter the policy end date'
                ]
            ]
        ];

        if ($policy->customer_quotes_id == 0) {
            $alerts = Notifications::Alert('No customer quote is linked to this policy', 'info', TRUE);
        }

//        $editvars = [
//            'policyno' => $policy->policy_number,
//            'quoteno' => $policy->customer_quotes_id,
//            'dategen' => $policy->dategenerated,
//            'customername' => $policy->customer->name,
//            'policyemail' => $policy->customer->email,
//            'product' => ucfirst($policy->product['name']),
//            'insurer' => ucfirst($policy->insurer['name']),
//            'status' => ucfirst($policy->status),
//            'premium' => 'ksh '.number_format($policy->amount,2),
//            'coverage' => $this->processCoverage($policy->coverage,'ksh '.number_format($policy->amount,2)),
//            'alerts' => $alerts
//        ];

        $eform = Generate::Form('confirmform', $editschematic);
        $editform = $eform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'policies' . DS . 'views' . DS . 'panels' . DS . 'policieseditform.php', TRUE, $editvars);

        // linked document/upload
        $modal_settings = [
            'id' => 'adddocument',
            'formid' => 'importform',
            'role' => 'dialog',
            'title' => 'Upload Policy Documents',
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

        // load some quote details
        $this->set('policyno', $policy->policy_number);
        $this->set('quoteno', $policy->customer_quotes_id);
        $this->set('dategen', $policy->dategenerated);
        $this->set('customername', $policy->customer->name);
        $this->set('policyemail', $policy->customer->email);
        $this->set('product', ucfirst($policy->product['name']));
        $this->set('insurer', ucfirst($policy->insurer['name']));
        $this->set('status', ucfirst($policy->status));
        $this->set('premium', 'ksh ' . number_format($policy->amount, 2));
        $this->set('coverage', $this->processCoverage($policy->coverage, 'ksh ' . number_format($policy->amount, 2), true));
        $this->set('alerts', $alerts);
        $this->set('policy_id', $policy->id);
        $this->set('policyedit', $editform);
        $policy_cover_details = $this->loadCoverDetails($policy->coverage, json_decode($policy->coverage['product'], true));
        $this->set('policy_cover_details', $policy_cover_details);

        $this->setViewPanel('policieseditpanel');
    }

    public function uploadAdditionalDocs()
    {

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

        $this->set('doccount', $this->get('docs_count'));
        $this->set('policyadd', $docstable);

        $modal_settings = [
            'id' => 'adddocument',
            'formid' => 'importform',
            'role' => 'dialog',
            'title' => 'Upload Policy Documents',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];

        $form = Overlays::Modal($modal_settings);

        $this->set('uploadform', $form);
        $this->set('policy_id', $this->get('id'));
        $this->set('current_step', 'three');
        $this->set('policyguide', $this->_policyGuide('three'));
        $this->setViewPanel('policiesaddpanel');
    }

    public function issuePolicy($policy, $output = 'policiesaddpanel')
    {

        $customer = Elements::call('Customers/CustomersController')->model->where('id', $policy->customers_id)->first();

        $issue_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/policies/saveissue',
            'controls' => [
                '{id}' => ['hidden', 'policyid', $policy->id],
                '{policyno}' => ['text', 'policynumber', $policy->policy_number],
                '{issuedate}' => ['date', 'issuedate', ($policy->issue_date == 0 ? '' : date('d F Y', $policy->issue_date)), ['format' => 'd F Y']],
                '{sendemail}' => ['checkbox', 'sendemail', 'yes'],
                '{cancel}' => ['submit', 'btncancel', 'Issue Later'],
                '{submit}' => ['submit', 'btnsubmit', 'Issue Policy']
            ]
        ];

        $emaillabel = ['label' => 'Send email notification to ' . $customer->name];

        $iform = Generate::Form('issueform', $issue_schematic);
        $issueform = $iform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'policies' . DS . 'views' . DS . 'panels' . DS . 'policyissue.php', TRUE, $emaillabel);

        $this->set('policyadd', $issueform);
        $this->set('policyguide', $this->_policyGuide('four'));

        $this->setViewPanel($output);
    }

    public function batchPolicy($policies, $output = 'process-issue')
    {

        $count = 0;

        $control_list = [];
        $label_list = [];

        foreach ($policies as $policy) {
            $customer = Elements::call('Customers/CustomersController')->find($policy->customers_id);
            $polurl = Elements::call('Navigation/NavigationController')->getUrl('policies');

            $controls = [
                '{destination}' => ['hidden', 'destination', $polurl],
                '{id_' . $count . '}' => ['hidden', 'policyid_' . $count, $policy->id],
                '{policyno_' . $count . '}' => ['text', 'policynumber_' . $count, $policy->policy_number],
                '{issuedate_' . $count . '}' => ['date', 'issuedate_' . $count, ($policy->issue_date == 0 ? '' : date('d F Y', $policy->issue_date)), ['format' => 'd F Y']],
                '{sendemail_' . $count . '}' => ['checkbox', 'sendemail_' . $count, 'yes'],
                '{submit_' . $count . '}' => ['submit', 'btnsubmit_' . $count, 'Issue Policy']
            ];

            $label = ['label_' . $count => 'Send email notification to ' . $customer->name];
            $customers[] = $customer->name;

            $count++;

            $control_list += $controls;
            $label_list += $label;
        }

        $issue_schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/policies/saveissue',
            'controls' => $control_list
        ];

        $vars = [
            'labels' => $label_list,
            'count' => $count,
            'customer' => $customers
        ];

        $iform = Generate::Form('issueform', $issue_schematic);
        $issueform = $iform->render(ABSOLUTE_PATH . DS . 'project' . DS . 'admin' . DS . 'policies' . DS . 'views' . DS . 'panels' . DS . 'batch-policies-issue.php', TRUE, $vars);

        $this->set('policyadd', $issueform);

        $this->setViewPanel($output);

    }

    public function getQuoteList($list)
    {

        $select = '<select class="control" id="quotes" name="quotes">';

        foreach ($list as $item) {
            $select .= '<option value="' . $item['id'] . '">'
                . ucfirst($item['product']) . ' '
                . 'Quote generated on ' . date('d-M-Y', $item['datetime']) . ', '
                . 'for ksh ' . number_format($item['amount'], 2) . ', '
                . 'covered by ' . $item['insurer']
                . '</option>';
        }

        $select .= '</select>';

        return $select;
    }

    private function _policyGuide($step)
    {
        switch ($step) {
            case 'one':
                $fullstep = ['<h3>Step 1</h3>',
                    '<p>Retrieve Customer Info & Quote</p>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Upload Additional Documents</p>',
                    '<p>Policy Approval & Issuance</p>'];
                $class = ['active', 'disabled', 'disabled', 'disabled'];
                break;

            case 'two':
                $fullstep = [
                    '<h3>Step 2</h3>',
                    '<p>Retrieve Customer Info & Quote</p>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Upload Additional Documents</p>',
                    '<p>Policy Approval & Issuance</p>'];
                $class = ['disabled', 'active', 'disabled', 'disabled'];
                break;

            case 'three':
                // upload additional documents
                $fullstep = [
                    '<h3>Step 3</h3>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Upload Additional Documents</p>',
                    '<p>Policy Approval & Issuance</p>'];
                $class = ['disabled', 'disabled', 'active', 'disabled'];
                break;

            case 'four':
                $fullstep = [
                    '<h3>Step 4</h3>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Upload Additional Documents</p>',
                    '<p>Policy Approval & Issuance</p>'];
                $class = ['disabled', 'disabled', 'disabled', 'active'];
                break;
        }

        $guide = '<ul class="nav nav-pills">';
        $guide .= '<li class="' . $class[0] . '"><a href="#"><h3>Step 1</h3>' . $fullstep[1] . '</a></li>';
        $guide .= '<li class="' . $class[1] . '"><a href="#"><h3>Step 2</h3>' . $fullstep[2] . '</a></li>';
        $guide .= '<li class="' . $class[2] . '"><a href="#"><h3>Step 3</h3>' . $fullstep[3] . '</a></li>';
        $guide .= '<li class="' . $class[3] . '"><a href="#"><h3>Step 4</h3>' . $fullstep[4] . '</a></li>';
        $guide .= '</ul>';
        $guide .= '<div class="clearfix"></div>';

//            . '<a class="list-group-item '.$class[0].'" href="#">'
//                    . $fullstep[0]
//            . '</a>'
//            . '<a class="list-group-item '.$class[1].'" href="#">'
//                    . $fullstep[1]
//            . '</a>'
//            . '<a class="list-group-item '.$class[2].'" href="#">'
//                    . $fullstep[2]
//            . '</a>'
//            . '</div>';

        return $guide;
    }

    public function matchImportColumns($doc)
    {

        $alert = Notifications::Alert('The <strong>' . $doc->worksheet->name . '</strong> has been imported. '
            . 'Now align matching policies table columns with the imported document columns', 'success', TRUE);

        $columns = ['policy_number' => 'Policy No',
            'customer_name' => 'Customer Name',
            'product_name' => 'Product Name',
            'insurer_name' => 'Insurer Name',
            'issue_date' => 'Policy Issue Date',
            'start_date' => 'Policy Start Date',
            'end_date' => 'Policy End Date',
            'status' => 'Status',
            'amount' => 'Amount'
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
        $this->set('policies_match_columns', $table);

        $this->setViewPanel('policies-match-columns');
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
        $pderrors = $errorlog['products'];
        foreach ($pderrors as $coords => $error) {

            $table .= '<tr>'
                . '<td>' . $coords . '</td>'
                . '<td>Products</td>'
                . '<td>' . $error . '</td>'
                . '</tr>';
        }

        //product errors
        $inserrors = $errorlog['insurers'];
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

    public function generatePolicyPreview($policy_data)
    {
        $this->set('policy', $policy_data);
        $this->set('customer', $policy_data->customer_info);
        $this->set('quote', $policy_data->quote);
        $this->set('insurer', $policy_data->insurer);
        $this->set('amounts', $policy_data->amounts);

        $amounts = $policy_data->amounts;
        if (count($amounts)) {
            foreach ($amounts as $title => $amount) {
                if ($amount->chosen) {
                    unset($amount->chosen);
                    $amounts = $amount;
                }
            }
        }
        $amounts_arr = $this->amountAsArray($amounts);
//        dump($amounts);exit;

        $core = $this->loadCore(array_except($amounts_arr, [
            'stamp_duty',
            'training_levy',
            'policy_levy',
            'other_covers',
            'insurer_id',
            'total',
            'total_net_premiums'
        ]));

        $product_details = $this->loadProductDetails($policy_data->product_info, $this->get('product_alias'));
        $other_covers = $this->loadOtherCovers($policy_data->amounts->other_covers);
        $more = $this->moreAmounts($amounts);

        $modal_settings = [
            'id' => 'download-docs',
            'formid' => 'download-docs-form',
            'role' => 'dialog',
            'size' => 'large',
            'title' => 'Download Policy Document(s)',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];

        $form = Overlays::Modal($modal_settings);

        $this->set('download_docs_modal', $form);
        $this->set('core', $core);
        $this->set('more', $more);
        $this->set('product_details', $product_details);
        $this->set('other_covers', $other_covers);
        $this->set('amounts', $amounts);
        $this->setViewPanel('preview_policy');
    }

    public function loadProductDetails($product_info, $product_alias = null)
    {
        $product_info = $this->amountAsArray($product_info);

        $product_info = array_except($product_info, [
            'btnsubmit',
            'step',
            'form_step',
            'i_agree'
        ]);
        $product_details = '';

        if (count($product_info)) {

            $indices = $this->getIndicesByProductAlias($product_alias);

            foreach ($product_info as $key => $info) {

                if (!empty($info) && isset($indices[$key])) {
                    $title = $indices[$key];

                    $product_details .= '<tr>
                        <td><strong>' . $title . '</strong></td>
                        <td>' . str_replace('_', ' ', $info) . '</td>
                    </tr>';
                } else {
                    if ($this->getCoreOptionals($key)) {
                        $first2_letters = substr($key, 0, 2);

                        $title = $this->identifyOptional($first2_letters, $key);
                        $product_details .= '<tr>
                        <td><strong>' . $title . '</strong></td>
                        <td>Yes</td>
                    </tr>';
                    }
                }
            }
        }

        return $product_details;
    }

    public function getCoreOptionals($key)
    {
        $optionals = [];
        for ($l = 'a'; $l <= 'd'; $l++) {
            for ($i = 1; $i <= 4; $i++) {
                $index = 'b' . $l . $i;

                $optionals[] = $index;
            }
        }
        return in_array($key, $optionals);
    }

    public function identifyOptional($letters, $key)
    {
        if ($letters == 'ba') {
            return 'Out Patient';
        } else if ($letters == 'bb') {
            if ($key == 'bb1' || $key == 'bb2')
                $return = 'Normal - Overall limit per year 60000';

            if ($key == 'bb3' || $key == 'bb4')
                $return = 'Caesarean - Overall limit per year 120000';
            return $return;
        } else if ($letters == 'bc') {
            return 'Last Expense';
        } else if ($letters == 'bd') {
            return 'Personal Accident';
        }
    }

    public function loadOtherCovers($amounts)
    {
        $html = '';

        if (count($amounts)) {
            foreach ($amounts as $title => $amount) {
                if (count($amount)) {
                    $html .= '<tr><td colspan="2"><b>' . $title . '</b></td></tr>';
                    foreach ($amount as $key => $amount_item) {
                        $html .= '<tr>
                            <td>' . ucwords(str_replace('_', ' ', $key)) . '</td>
                            <td>Ksh. ' . number_format($amount_item, 2) . '</td>
                        </tr>';
                    }
                }
            }
        }

        return $html;
    }

    public function loadCore($amount)
    {
        $core = [];

        $html = '';

        foreach ($amount as $key => $value) {
            $html .= '<tr>
                <td>' . ucwords(str_replace('_', ' ', $key)) . '</td>
                <td>Ksh. ' . number_format($value, 2) . '</td>
            </tr>';
        }
        return $html;
    }

    public function amountAsArray($amounts)
    {
        $array = [];
        if (count($amounts)) {
            foreach ($amounts as $key => $amount) {
                $array[$key] = $amount;
            }
        }
        return $array;
    }

    public function moreAmounts($amounts)
    {
//        dump($amounts);exit;
        $more = '';
        $more .= '<tr>
                    <td><b>Training Levy</b></td>
                    <td>Ksh. ' . number_format($amounts->training_levy, 2) . '</td>
                </tr>
                <tr>
                    <td><b>Policy Levy</b></td>
                    <td>Ksh. ' . number_format($amounts->policy_levy, 2) . '</td>
                </tr>
                <tr>
                    <td><b>Stamp Duty</b></td>
                    <td>Ksh. ' . number_format($amounts->stamp_duty, 2) . '</td>
                </tr>';
        return $more;
    }

    public function loadDownloadDocsModal()
    {
        $docstable = '';
        $docs = $this->get('source');

//        print_r($docs);exit;
        if (count($docs)) {
            foreach ($docs as $doc) {
                $docstable .= '<tr>';

                $docstable .= '<td>' . $doc->time . '</td>';
                $docstable .= '<td>' . $doc->description . '</td>';
                $docstable .= '<td>' . $doc->doctype . '</td>';
                $docstable .= '<td>' . $doc->filename . '</td>';
                $docstable .= '<td>
                    <a href="' . Url::link('/') . $doc->filepath . '" download="download"><i class="glyphicon glyphicon-download"></i></a>
                </td>';

                $docstable .= '</tr>';
            }
        } else {
            $docstable .= '<tr>';

            $docstable .= '<th colspan="5" class="text-center">No linked documents found!</th>';

            $docstable .= '</tr>';
        }

        $this->set('doc_rows', $docstable);
        $this->setViewPanel('related_docs');
    }

    /**
     * Renew one or more of the selected policies
     * @param $policies
     */
    public function renewPolicies($policies)
    {
        $this->set('policies', $policies);
        $this->setViewPanel('renew_policies');
    }

    public function showMailModal()
    {
        $this->setViewPanel('email-policy-form');
    }

    public function mailPolicy($policy, $customer, $products, $agent)
    {
        $names = ucwords(strtolower($customer->name));

        $preview_link_content = '<p>Dear ' . $names . ',</p><p>Hope this finds you well.</p><p>We have prepared policy no. ' . $policy->policy_number . ' for you. To review the policy, please click on the link below:</p>' . Url::link('/policies/previewpolicy/' . $policy->id) . '<p>You could also Download linked documents from the link.</p><p>If you have any questions regarding the offer, please don\'t hesitate to contact me.</p><p>Best regards,<br/>' . $agent->names . '<br/>Email: ' . $agent->email_address . '<br/>Telephone: ' . $agent->telephone_number;

        $pdf_content = '<p>Dear ' . $names . ',</p><p>Hope this finds you well.</p><p>We have prepared policy no. ' . $policy->id . ' for you. The policy is in the PDF attached to this email.</p> To review the policy, please click on the link below:</p>' . Url::link('/policies/previewpolicy/' . $policy->id) . '<p>You could also Download linked documents from the link.</p></p>If you have any questions regarding the policy, please don\'t hesitate\nto contact me.</p><p>Best regards,<br/>' . $agent->names . '<br/>Email: ' . $agent->email_address . '<br/>Telephone: ' . $agent->telephone_number . '</p>';

        //get own company
        $owncompany = Elements::call('Companies/CompaniesController')->ownCompany(TRUE);

        $email_schema = [
            'preventjQuery' => TRUE,
            'preventZebraJs' => TRUE,
            'method' => 'POST',
            'action' => '/ajax/admin/policies/sendemail/' . $policy->id,
            'controls' => [
                '{id}' => ['hidden', 'id', $policy->id],
                '{customer}' => ['hidden', 'customer_name', $names],
                '{replyname}' => ['hidden', 'replyname', $agent->names],
                '{replyto}' => ['hidden', 'replyto', $agent->email_address],
                '{owncompany}' => ['hidden', 'owncompany', $owncompany->name],
                '{ownemail}' => ['hidden', 'ownemail', $owncompany->email_address],
                'Send Mode*' => ['select', 'sendmode', '', [
                    '1' => 'Email linking to direct policy page',
                    '2' => 'Email with Policy PDF attachment'
                ], ['class' => 'modal_required']],
                'Email Address*' => ['text', 'email', $customer->email, ['class' => 'modal_required']],
                'Subject*' => ['text', 'subject', 'Your ' . $products['name'] . ' Insurance Offer: Please review your policy', ['class' => 'modal_required']],
                'Message*' => ['textarea', 'content', '', ['class' => 'email_content modal_required']]
            ]
        ];

        $emailform = Generate::Form('emailform', $email_schema)->render('vertical', TRUE);

        $modal_settings = [
            'formid' => 'emailform',
            'role' => 'dialog',
            'title' => 'Email Policy',
            'size' => 'large',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Send Policy' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addform = Overlays::ModalDialog($modal_settings, $emailform, true);

        $this->set('id', $policy->id);
        $this->set('addform', $addform);
        $this->set('preview_link_content', $preview_link_content);
        $this->set('pdf_content', $pdf_content);

        $this->setViewPanel('email-policy-form');
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

    public function getIndicesByProductAlias($alias, $other_covers_no = 0)
    {
        $indices = [];

        if ($alias == 'medical_insurance')
            $indices = Elements::call('Medical/MedicalController')->view->medicalIndices($other_covers_no);
        else if ($alias == 'travel_insurance')
            $indices = Elements::call('Travel/TravelController')->view->travelIndices($other_covers_no);
        else if ($alias == 'personal_accident')
            $indices = Elements::call('Accident/AccidentController')->view->accidentIndices();
        else if ($alias == 'motor_insurance')
            $indices = Elements::call('Motor/MotorController')->view->motorIndices();
        else if ($alias == 'domestic_package')
            $indices = Elements::call('Domestic/DomesticController')->view->domesticIndices();

        return $indices;
    }

    public function showRenewalModal($policy){
        $renew_schema = [
            'preventjQuery' => TRUE,
            'preventZebraJs' => TRUE,
            'method' => 'POST',
            'action' => '/admin/policies/renewpolicy',
            'controls' => [
                '{id}' => ['hidden', 'id', $policy->id],
                '{note}' => ['note', 'note', '<h5>Policy#: <b>'.$policy->policy_number.'</b></h5>'],
                'End Date' => ['date', 'end_date', $policy->end_date],
                'Period' => ['select', 'period', '', [
                        '3 month' => '3 Months',
                        '6 month' => '6 Months',
                        '1 year' => '1 Year'
                    ]
                ]
//                '{submit}' => ['submit', 'btnsubmit', 'Send Email']
            ]
        ];
        $renewalform = Generate::Form('renewal_modal_form', $renew_schema)->render('vertical', TRUE);

        $modal_settings = [
            'formid' => 'renewal_modal_form',
            'role' => 'dialog',
            'size' => 'large',
            'title' => 'Renew Policy',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Renew Policy' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary mail',
                    'id' => 'renew_pol_btn'
                ]
            ]
        ];

        $modal_content = Overlays::ModalDialog($modal_settings, $renewalform, true);
        $this->set('modal_content', $modal_content);
        $this->setViewPanel('renewal_policy_modal');
    }
}