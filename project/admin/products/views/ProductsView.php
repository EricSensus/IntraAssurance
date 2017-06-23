<?php
namespace Jenga\MyProject\Products\Views;

use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\View;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class ProductsView extends View {
    /**
     * @param $products
     */
    public function productsDisplay($products){

        $columns = ['Name','Alias','Type',''];
        $rows = ['{name}',
                '{alias}',
                '{renew}',
                '{{<a class="smallicon" href="'.Url::base().'/admin/products/addedit/{id}" >'
                    . '<img '.Notifications::tooltip('Edit {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/edit_icon.png"/>'
                . '</a>'
                . '<a class="smallicon" data-confirm="{name} will be deleted. Are you sure?" href="'.Url::base().'/admin/products/delete/{id}" >'
                    . '<img '.Notifications::tooltip('Delete {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                . '</a>}}'];

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
            'row_count' => count($products),
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $products
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

        $table = Generate::Table('productstable',$schematic);

        $tools = [
                    'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small',
                    'settings' => [
                        'add_tool_names' => false,
                        'wrap_with' => 'div' //the default option is wrap_with => 'table'
                    ],
                    'tools' => [
                        'add' => [
                            'path' => '/admin/products/add',
                            'tooltip' => 'Add Product',
                            'type' => 'modal',
                            'settings' => [
                                'id' => 'addproductsmodal',
                                'data-target' => '#addproductsmodal',
                                'data-backdrop' => 'static'
                            ]
                        ],
                        'delete' => [
                            'path' => '/admin/products/delete',
                            'using' => ['{id}'=>'{name}']
                        ]
                    ]
                ];

        $table->buildTools($tools);
        $producttable = $table->render(TRUE);

        //add the delete confirm modal
        $deletemodal = Overlays::confirm();
        $this->set('deletemodal', $deletemodal);

        $this->set('products_setup', $producttable);
    }

    /**
     * @param $product
     * @param $fields
     */
    public function addProductForm($product,$fields){

        $entities = Elements::call('Entities/EntitiesController')->getEntityTypes();

        $schematic = [
            'preventjQuery' =>TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/products/saveproduct',
            'controls' =>[
                '{id}' => ['hidden','productid',Input::get('id')],
                '{name}' => ['text','name',$product->name],
                '{alias}' => ['text','alias',$product->alias,['readonly'=>'readonly']],
                '{producttype}' => ['select','producttype',(is_null($product->product_type) ? '0' : $product->product_type),['0'=>'recurring / renewable','1'=>'one-time (non-renewable)']],
                '{entity_type}' => ['select','entitytype',$product->entity_types_id,$entities],
                '{multiple}' => ['select','multiple_entities',$product->multiple_entities,['0'=>'No','1'=>'Yes']],
                '{reset}' => ['reset','btnreset','Cancel'],
                '{submit}' => ['submit','btnsubmit','Save Product']
            ],
            'validation' => [
                'name' => [
                    'required' => 'Please enter the product name'
                ],
                'entitytype' => [
                    'required' => 'Please enter the product entity relationship'
                ] ,
                'multiple_entities' => [
                    'required' => 'Please answer the multiple entity questions'
                ]
            ]
        ];

        $pform = Generate::Form('pform', $schematic);
        $pfields = $this->productFieldsTable($fields,$product->forms_id);

        $vars = [
            'productalias'=>$product->alias,
            'productfields' => $pfields
        ];

        $fullform = $pform->render(ABSOLUTE_PATH
                .DS. 'project' .DS. 'admin' .DS. 'products'
                .DS. 'views' .DS. 'panels' .DS. 'addeditproductform.php',
                TRUE, $vars);

        //create the edit Overlaymodal
        $this->set('editmodal',  Overlays::Modal(['id'=>'editformfield','title'=>'Edit Product Form Field']));
        $this->set('deletemodal', Overlays::confirm());

        $this->set('product_add_edit', $fullform);
    }

    /**
     * @param $fields
     * @param $formid
     * @return \Jenga\App\Html\type
     */
    public function productFieldsTable($fields,$formid){
        $columns = ['Order','','Name','Type','Required',''];
        $rows = [
            '{order}',
            '{{<img '.Notifications::tooltip('Drag and Drop to change field order').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/rows.png"/>}}',
            '{{<div style="width:100%; text-align:left;">{name}</div>}}',
            '{type}',
            '{required}',
            '{{<a data-toggle="modal" data-backdrop="static" data-target="#editformfield" class="smallicon" '
            . '     href="'.Url::base().'/admin/products/fieldedit/{formname}/{sysname}" >'
                . '<img '.Notifications::tooltip('Edit {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/edit_icon.png"/>'
            . '</a>'
            . '<a class="smallicon" data-confirm="{name} will be deleted. Are you sure?" href="'.Url::base().'/admin/products/fielddelete/{formid}/'.Input::get('id').'/{sysname}" >'
                . '<img '.Notifications::tooltip('Delete {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
            . '</a>}}'
            ];

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
                'Order' => 'asc'
            ],
            'rowreorder' => [
                'url' => Url::base().'/ajax/admin/products/fieldrowreorder/'.$formid,
                'requesttype' => 'GET',
                'debug' => true
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
            'row_count' => count($fields),
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $fields
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

        $table = Generate::Table('productstable',$schematic);
        return $table->render(TRUE);
    }

    /**
     * @param $form
     */
    public function addNewProductForm($form){

        $modal_settings = [
            'formid' => 'addform',
            'role' => 'dialog',
            'title' => 'Add New Insurance Product',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Create Product & Configure' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addform = Overlays::ModalDialog($modal_settings, $form);

        $this->set('addform',$addform);
        $this->setViewPanel('newproduct');
    }

    /**
     * @param $pform
     */
    public function fullProductForm($pform){

        $productform = $pform->render(
                ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'products' .DS. 'views' .DS. 'panels' .DS.
                'productform.php',TRUE);

        $modal_settings = [
            'formid' => 'productfieldform',
            'role' => 'dialog',
            'title' => 'Add Product Field',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Create Product Field' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_field_button'
                ]
            ]
        ];

        $startform = Overlays::ModalDialog($modal_settings, $productform);
        $this->set('startform', $startform);

        $this->setViewPanel('fieldform');
    }

    public function fieldEditForm($pform){
        $attributes = ['attributes' => $this->get('attributes')];
        $editfieldform = $pform->render(
                ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'products' .DS. 'views' .DS. 'panels' .DS.
                'editformfield.php',TRUE,$attributes);

        $modal_settings = [
            'formid' => 'editfieldform',
            'role' => 'dialog',
            'title' => 'Edit Product Form Field',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Save Field Edits' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_edit_field_button'
                ]
            ]
        ];

        $editform = Overlays::ModalDialog($modal_settings, $editfieldform, true);
        $this->set('editform', $editform);

        $this->setViewPanel('editformfieldpanel');
    }

}