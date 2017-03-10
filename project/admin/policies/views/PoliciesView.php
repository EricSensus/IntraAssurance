<?php
namespace Jenga\MyProject\Policies\Views;

use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\View;

use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class PoliciesView extends View{
    
    public function generateTable(){
        
        $count = $this->get('count');
        $source = $this->get('source');
        $search = $this->get('search');
        
        $condition = $this->get('condition');
        
        if(!is_null($condition)){
            
            //create the return url from the Navigation element
            $url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'policies']);
            
            $this->set('alerts', Notifications::Alert($condition
                    .'<a data-dismiss="alert" class="close" href="'.Url::base().$url.'">×</a>', 'info', TRUE, TRUE));
        }
        
        $suburl = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'customers']);
        $polurl = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'policies']);
        
        $columns = ['Created','Policy No','Validity','Issue Date','Customer','Product','Premium','Issue'];
        
        $rows = ['{created}',
                '{{<a href="'.SITE_PATH.$polurl.'/edit/{id}">{policyno}</a>}}',
                '{validity}',
                '{issuedate}',
                '{{<a href="'.SITE_PATH.$suburl.'/show/{customers_id}">{customer}</a>}}',
                '{product}',
                '{premium}',
                '{image}'];    
        
        $dom = '<"top">rt<"bottom"p><"clear">';        
        $policiestable = $this->_mainTable('policies_table', $count, $columns, $rows, $source, $dom,$search);
        
        $this->set('policies_table', $policiestable);
    }
    
    private function _mainTable($name, $count, array $columns, array $rows, $source, $dom, $searchform=''){
        
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

        $table = Generate::Table($name,$schematic);

        $tools = [
            'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/',
            'tools' => [
                        'issue policy' => [
                            'path' => '/admin/policies/issuebatch',
                            'using' => ['{id}','{customer}','{status}'],
                            'confirm' => true
                        ],
                        'search' => $searchform,
                        'add' => [
                            'path' => '/admin/policies/add'
                        ],
                        'import' => [
                            'path' => Url::base().'/admin/policies/import',
                            'upload_folder' => ABSOLUTE_PATH.'/tmp',
                            'allowed_file_extensions' => ['csv','xls','xlsx','xlsm','xlsb'],
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
                            'using' => ['{id}'=>'{insurer},{customer}'],
                            'confirm' => true
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
    
    public function generateMiniTable($from){
        
        $count = $this->get('count');
        $source = $this->get('source');
        $search = $this->get('search');
        
        $condition = $this->get('condition');
        
        if(!is_null($condition)){
            
            $this->set('alerts', Notifications::Alert($condition, 'info', TRUE, TRUE));
        }
        
        $suburl = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'customers']);
        $polurl = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'policies']);
        
        $columns = ['Policy No','Validity','Customer','Issue'];
        
        $rows = ['{{<a href="'.SITE_PATH.$polurl.'/edit/{id}">{policyno}</a>}}',
                '{validity}',
                '{customer}',
                '{image}'];    
        
        $dom = '<"top">rt<"bottom"p><"clear">';   
        
        if($from == 'unprocessed'){
            
            $unprocessedtable = $this->_miniTable('policiestable', $count, $columns, $rows, $source, $dom,$search);
            $this->set('unprocessedtable', $unprocessedtable);
        }
        elseif($from == 'expiring'){
            
            $expiredtable = $this->_miniTable('expiringtable', $count, $columns, $rows, $source, $dom,$search);
            $this->set('expiringtable', $expiredtable);
        }
    }
    
    private function _minitable($name, $count, array $columns, array $rows, $source, $dom){
        
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

        $table = Generate::Table($name,$schematic);        
        $minitable = $table->render(TRUE);
        
        return $minitable;
    }
    
    public function addPolicyForm($customer = null){
        
        if(is_null($customer)){
            
            $controls = [
                'Customer Name (Type Name Below)' => ['text','customer','',['class'=>'form-control','overwrite'=>TRUE]],
            ];

//            if(!is_null($customer->quotes))
                $controls['{submit}'] = ['submit','btnsubmit','Create Policy'];
        }
        else{
            
            $controls = ['Customer Name (Type Name Below)' => ['text','customer',$customer->name,['class'=>'form-control','overwrite'=>TRUE]]];
            
            if(!is_null($customer->quotes)){
                
                //process quotes
                foreach ($customer->quotes as $item) {  

                    $select[$item['id']] = ucfirst($item['product']).' '
                                    . 'Quote generated on '.  date('d-M-Y', $item['datetime']).', '
                                    . 'for ksh '.number_format($item['amount'],2).', '
                                    . 'covered by '.$item['insurer'];
                }

                $controls += [
                    'Select a Quote for '.$customer->name => ['select','quotes','',$select]
                ];
            }
            else{

                $quotes = ['quotes' => Notifications::Alert('No quotes found for <strong>'.$customer->name.'</strong>', 'notice', true)];
            }

            $controls += ['{submit}' => ['submit','btnsubmit','Create Policy']];
        }

        $policy_schematic = [
            'preventjQuery' =>TRUE,
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
        $fullform = $pform->render(ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'policies' .DS. 'views' .DS. 'panels' .DS. 'policyform.php', TRUE, $quotes);
        
        $this->set('policyadd', $fullform);
        $this->set('policyguide', $this->_policyGuide('one'));
        
        $this->setViewPanel('policiesaddpanel');
    }
    
    public function processCoverage($coverage,$premium){
        
        //process entity
        $entity = $coverage['entity'][0]['entity'];
        
        $table = '<table width="100%" cellpadding="10" border="0" class="policy">';
        
        $count = 0;
        if(!is_null($entity)){
            
            foreach($entity as $label => $property){

                if($count == 0){
                    $table .= '<tr>'
                            . '<td colspan="2" class="premium"><h4><strong>'.$coverage['entity']['type'].' Details</strong></h4></td>'
                            . '</tr>';
                }

                    $table .= '<tr>'
                            . '<td><strong>'.str_replace('_', ' ', $label).'</strong></td>'
                            . '<td>'.$property.'</td>'
                            . '</tr>';

                $count++;
            }
        }
        
        //process product
        $products = json_decode($coverage['product'],true);
        $count = 0;
        
        if(!is_null($products)){
            
            foreach($products as $label => $property){

                if($count == 0){
                    $table .= '<tr class="heading">'
                            . '<td colspan="2" class="premium"><h4><strong>Product Details</strong></h4></td>'
                            . '</tr>';
                }

                if(is_numeric($property))
                    $property = 'ksh '.number_format ($property, 2);

                $table .= '<tr>'
                        . '<td><strong>'.str_replace('_', ' ', $label).'</strong></td>'
                        . '<td>'.$property.'</td>'
                        . '</tr>';

                $count++;
            }
        }
        
        $table .= '<tr class="last">'
                        . '<td class="premium"><h4>Total</h4></td>'
                        . '<td class="premium"><h4>'.$premium.'</h4></td>'
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
    public function addPolicy($policy) {
        
        $confirm_schematic = [
            'preventjQuery' =>TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/policies/savepolicy',
            'controls' => [
                '{offer}' => ['hidden','offer',$policy->offer],
                '{policyno}' => ['hidden','policyno',$policy->no],
                '{_id}' => ['hidden','customers_id',$policy->customer->id],
                '{insurers_id}' => ['hidden','insurers_id',$policy->insurer['id']],
                '{customer_quotes_id}' => ['hidden','customer_quotes_id',$policy->quoteid],
                '{products_id}' => ['hidden','products_id',$policy->product['id']],
                '{status}' => ['hidden','status',$policy->status],
                '{code}' => ['hidden','code',$policy->currency_code],
                '{amount}' => ['hidden','amount',$policy->amount],
                '{startdate}' => ['date','startdate',$policy->startdate,['format'=>'d F Y']],
                '{enddate}' => ['date','enddate',$policy->enddate,['format'=>'d F Y']],
                '{submit}' => ['submit','btnsubmit','Confirm Policy & Coverage']
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
            'coverage' => $this->processCoverage($policy->coverage,$policy->premium)
        ];
        
        $cform = Generate::Form('confirmform', $confirm_schematic);
        $confirmform = $cform->render(ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'policies' .DS. 'views' .DS. 'panels' .DS. 'policy-add-form.php', TRUE, $confirmvars);
        
        $this->set('policyadd', $confirmform);
        $this->set('policyguide', $this->_policyGuide('two'));
        
        $this->setViewPanel('policiesaddpanel');
    }
    
    public function editPolicy($policy){
        $editschematic = [
            'preventjQuery' =>TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/policies/savepolicyedit',
            'controls' => [
                '{id}' => ['hidden','id',$policy->id],
                '{_id}' => ['hidden','customers_id',$policy->customer->id],
                '{insurers_id}' => ['hidden','insurers_id',$policy->insurer['id']],
                '{customer_quotes_id}' => ['hidden','customer_quotes_id',$policy->quoteid],
                '{products_id}' => ['hidden','products_id',$policy->product['id']],
                '{status}' => ['hidden','status',$policy->status],
                '{policynumber}' => ['text','policynumber',$policy->policy_number],
                '{issuedate}' => ['date','issuedate',date('d F Y',$policy->issue_date),['format'=>'d F Y']],
                '{startdate}' => ['date','startdate',date('d F Y',$policy->start_date),['format'=>'d F Y']],
                '{enddate}' => ['date','enddate',date('d F Y',$policy->end_date),['format'=>'d F Y']],
                '{submit}' => ['submit','btnsubmit','Confirm Policy & Coverage']
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
        
        if($policy->customer_quotes_id == 0){            
            $alerts = Notifications::Alert('No customer quote is linked to this policy', 'info', TRUE);
        }
        
        $editvars = [
            'policyno' => $policy->policy_number,
            'quoteno' => $policy->customer_quotes_id,
            'dategen' => $policy->dategenerated,
            'customername' => $policy->customer->name,
            'policyemail' => $policy->customer->email,
            'product' => ucfirst($policy->product['name']),
            'insurer' => ucfirst($policy->insurer['name']),
            'status' => ucfirst($policy->status),
            'premium' => 'ksh '.number_format($policy->amount,2),
            'coverage' => $this->processCoverage($policy->coverage,'ksh '.number_format($policy->amount,2)),
            'alerts' => $alerts
        ];
        
        $eform = Generate::Form('confirmform', $editschematic);
        $editform = $eform->render(ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'policies' .DS. 'views' .DS. 'panels' .DS. 'policieseditform.php', TRUE, $editvars);
        
        $this->set('policyedit', $editform);
        
        $this->setViewPanel('policieseditpanel');
    }

    public function uploadAdditionalDocs(){

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
    
    public function issuePolicy($policy,$output='policiesaddpanel'){
        
        $customer = Elements::load('Users/UsersController@getCustomerName', ['id'=>$policy->customers_id]);
        
        $issue_schematic = [
            'preventjQuery' =>TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/policies/saveissue',
            'controls' => [
                '{id}' => ['hidden','policyid',$policy->id],
                '{policyno}' => ['text','policynumber',$policy->policy_number],
                '{issuedate}' => ['date','issuedate',($policy->issue_date == 0 ? '' : date('d F Y',$policy->issue_date)),['format'=>'d F Y']],
                '{sendemail}' => ['checkbox','sendemail','yes'],
                '{cancel}' => ['submit','btncancel','Issue Later'],
                '{submit}' => ['submit','btnsubmit','Issue Policy']
            ]
        ];
        
        $emaillabel = ['label'=> 'Send email notification to '.$customer];
        
        $iform = Generate::Form('issueform', $issue_schematic);
        $issueform = $iform->render(ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'policies' .DS. 'views' .DS. 'panels' .DS. 'policyissue.php', TRUE, $emaillabel);
        
        $this->set('policyadd', $issueform);
        $this->set('policyguide', $this->_policyGuide('three'));
        
        $this->setViewPanel($output);
    }
    
    public function batchPolicy($policies,$output='process-issue'){
        
        $count=0;
        
        $control_list = [];
        $label_list = [];
        
        foreach($policies as $policy){
            
            $customer = Elements::call('Customers/CustomersController')->find($policy->customers_id);
            $polurl = Elements::call('Navigation/NavigationController')->getUrl('policies');
            
            $controls = [                    
                    '{destination}' => ['hidden','destination',$polurl],
                    '{id_'.$count.'}' => ['hidden','policyid_'.$count,$policy->id],
                    '{policyno_'.$count.'}' => ['text','policynumber_'.$count,$policy->policy_number],
                    '{issuedate_'.$count.'}' => ['date','issuedate_'.$count,($policy->issue_date == 0 ? '' : date('d F Y',$policy->issue_date)),['format'=>'d F Y']],
                    '{sendemail_'.$count.'}' => ['checkbox','sendemail_'.$count,'yes'],
                    '{submit_'.$count.'}' => ['submit','btnsubmit_'.$count,'Issue Policy']
                ];
            
            $label = ['label_'.$count => 'Send email notification to '.$customer->name];
            $customers[] = $customer->name;
            
            $count++;
            
            $control_list += $controls;
            $label_list += $label;
        }
        
        $issue_schematic = [
            'preventjQuery' =>TRUE,
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
        $issueform = $iform->render(ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'policies' .DS. 'views' .DS. 'panels' .DS. 'batch-policies-issue.php', TRUE, $vars);
        
        $this->set('policyadd', $issueform);
        
        $this->setViewPanel($output);
        
    }
    
    public function getQuoteList($list){
        
        $select = '<select class="control" id="quotes" name="quotes">';
        
        foreach ($list as $item) {            
            $select .= '<option value="'.$item['id'].'">'
                            . ucfirst($item['product']).' '
                            . 'Quote generated on '.  date('d-M-Y', $item['datetime']).', '
                            . 'for ksh '.number_format($item['amount'],2).', '
                            . 'covered by '.$item['insurer']
                        .'</option>';
        }
        
        $select .= '</select>';
        
        return $select;
    }
    
    private function _policyGuide($step){
        switch ($step) {
            case 'one':
                $fullstep = ['<h3>Step 1</h3>',
                            '<p>Retrieve Customer Info & Quote</p>',
                            '<p>Policy & Coverage Confirmation</p>',
                            '<p>Upload Additional Documents</p>',
                            '<p>Policy Approval & Issuance</p>'];
                $class = ['active','disabled','disabled','disabled'];
                break;
            
            case 'two':
                $fullstep = [
                            '<h3>Step 2</h3>',
                            '<p>Retrieve Customer Info & Quote</p>',
                            '<p>Policy & Coverage Confirmation</p>',
                            '<p>Upload Additional Documents</p>',
                            '<p>Policy Approval & Issuance</p>'];
                $class = ['disabled','active','disabled','disabled'];
                break;

            case 'three':
                // upload additional documents
                $fullstep = [
                    '<h3>Step 3</h3>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Policy & Coverage Confirmation</p>',
                    '<p>Upload Additional Documents</p>',
                    '<p>Policy Approval & Issuance</p>'];
                $class = ['disabled','disabled','active','disabled'];
                break;

            case 'four':
                $fullstep = [
                            '<h3>Step 4</h3>',
                            '<p>Policy & Coverage Confirmation</p>',
                            '<p>Policy & Coverage Confirmation</p>',
                            '<p>Upload Additional Documents</p>',
                            '<p>Policy Approval & Issuance</p>'];
                $class = ['disabled','disabled','disabled','active'];
                break;
        }

        $guide = '<ul class="nav nav-pills">';
        $guide .= '<li class="'.$class[0].'"><a href="#"><h3>Step 1</h3>'.$fullstep[1].'</a></li>';
        $guide .= '<li class="'.$class[1].'"><a href="#"><h3>Step 2</h3>'.$fullstep[2].'</a></li>';
        $guide .= '<li class="'.$class[2].'"><a href="#"><h3>Step 3</h3>'.$fullstep[3].'</a></li>';
        $guide .= '<li class="'.$class[3].'"><a href="#"><h3>Step 4</h3>'.$fullstep[4].'</a></li>';
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
    
    public function matchImportColumns($doc) {
        
        $alert = Notifications::Alert('The <strong>'.$doc->worksheet->name.'</strong> has been imported. '
                . 'Now align matching policies table columns with the imported document columns', 'success', TRUE);
        
        $columns = ['policy_number'=>'Policy No',
                    'customer_name'=>'Customer Name',
                    'product_name'=> 'Product Name',
                    'insurer_name' => 'Insurer Name',
                    'issue_date'=>'Policy Issue Date',
                    'start_date'=>'Policy Start Date',
                    'end_date'=>'Policy End Date',
                    'status'=>'Status',
                    'amount'=>'Amount'
                    ];
        
        $importcolumns = $doc->worksheet->columns;
        
        //get full table
        $count = 1;
        $table = '<div class="row">';
        foreach ($columns as $dbcol => $column) {
            
            //create select tag
            $scount = -1;
            $select = '<select class="form-control" name="importselect_'.$count.'">';
            foreach ($importcolumns as $imcolumn) {

                if($scount == -1){
                    $select .= '<option selected="selected" value="">Skip Column</option>';
                    $scount++;
                }
                
                $select .= '<option value="'.$scount.'">'.$imcolumn.'</option>';
                $scount++;
            }
            $select .= '</select>';
            
            $table .= '<div class="col-md-3">'
                        . '<table class="table table-striped table-bordered">'
                            . '<tr>'
                                . '<td><p><strong>Column '.$count.': '.$column.'</strong></p></td>'
                            . '</tr>'
                            . '<tr height="50">'
                                . '<td>'
                                . '<input type="hidden" name="columns[]" value="'.$count.','.$dbcol.'" >'
                                . 'Select the import column which matches with <strong>'. $column .'</strong>'
                                .'</td>'
                            . '</tr>'
                            . '<tr>'
                                . '<td>'.$select.'</td>'
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
    
    public function showImportErrors($errorlog) {
        
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
                        . '<td>'.$coords.'</td>'
                        . '<td>Database</td>'
                        . '<td>'.$error.'</td>'
                    . '</tr>';
        }
        
        //product errors
        $pderrors = $errorlog['products'];
        foreach ($pderrors as $coords => $error) {
            
            $table .= '<tr>'
                        . '<td>'.$coords.'</td>'
                        . '<td>Products</td>'
                        . '<td>'.$error.'</td>'
                    . '</tr>';
        }
        
        //product errors
        $inserrors = $errorlog['insurers'];
        foreach ($inserrors as $coords => $error) {
            
            $table .= '<tr>'
                        . '<td>'.$coords.'</td>'
                        . '<td>Insurers</td>'
                        . '<td>'.$error.'</td>'
                    . '</tr>';
        }
        
        $table .= '</tbody>'
            . '</table>';
    }
}