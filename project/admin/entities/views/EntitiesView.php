<?php
namespace Jenga\MyProject\Entities\Views;

use Jenga\App\Request\Url;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Views\View;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class EntitiesView extends View{

    public function entitiesDisplay($entities){

        $columns = ['Name','Alias','Type',''];
        $rows = ['{name}',
                '{alias}',
                '{type}',
                '{{<a class="smallicon" href="'.Url::base().'/admin/entities/edit/{id}" >'
                    . '<img '.Notifications::tooltip('Edit {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/edit_icon.png"/>'
                . '</a>'
                . '<a class="smallicon" data-confirm="{name} will be deleted. Are you sure?" href="'.Url::base().'/admin/entities/delete/{id}" >'
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
            'row_count' => count($entities),
            'rows_per_page' => 25,
            'row_source' => [
                'object' => $entities
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

        $table = Generate::Table('entitiestable',$schematic);

        $tools = [
                    'images_path' => RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small',
                    'settings' => [
                        'add_tool_names' => false,
                        'wrap_with' => 'div' //the default option is wrap_with => 'table'
                    ],
                    'tools' => [
                        'add' => [
                            'path' => '/admin/entities/add',
                            'tooltip' => 'Add Entity',
                            'type' => 'modal',
                            'settings' => [
                                'id' => 'addentitiesmodal',
                                'data-target' => '#addentitiesmodal',
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
        $entitytable = $table->render(TRUE);

        //add the delete confirm modal
        $deletemodal = Overlays::confirm();
        $this->set('deletemodal', $deletemodal);

        $this->set('entitiestable', $entitytable);
    }

    public function addNewEntityForm($form){

        $modal_settings = [
            'formid' => 'addentityform',
            'role' => 'dialog',
            'title' => 'Add New Insurance Entity',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Create Entity & Configure' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_button'
                ]
            ]
        ];

        $addentityform = Overlays::ModalDialog($modal_settings, $form);

        $this->set('addentityform',$addentityform);
        $this->setViewPanel('newentity');
    }

    public function editEntityForm($entity,$fields){

        $entities = Elements::call('Entities/EntitiesController')->getEntityTypes();

        $schematic = [
            'preventjQuery' =>TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/entities/saveentity',
            'controls' =>[
                '{id}' => ['hidden','entityid',Input::get('id')],
                '{name}' => ['text','name',$entity->name],
                '{alias}' => ['text','alias',$entity->alias,['readonly'=>'readonly']],
                '{entitytype}' => ['select','entitytype',(is_null($entity->entity_types_id) ? '' : $entity->entity_types_id),$entities],
                '{reset}' => ['reset','btnreset','Cancel'],
                '{submit}' => ['submit','btnsubmit','Save Entity']
            ],
            'validation' => [
                'name' => [
                    'required' => 'Please enter the entity name'
                ],
                'entitytype' => [
                    'required' => 'Please enter the entity entity relationship'
                ] ,
                'multiple_entities' => [
                    'required' => 'Please answer the multiple entity questions'
                ]
            ]
        ];

        $eform = Generate::Form('eform', $schematic);
        $efields = $this->entityFieldsTable($fields,$entity->forms_id);

        $vars = [
            'entityalias'=>$entity->alias,
            'entityfields' => $efields
        ];

        $fullform = $eform->render(ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'entities' .DS. 'views' .DS. 'panels' .DS. 'editentityform.php', TRUE, $vars);

        //create the edit Overlaymodal
        $this->set('editmodal',  Overlays::Modal(['id'=>'editformfield','title'=>'Edit Entity Form Field']));
        $this->set('deletemodal', Overlays::confirm());

        $this->set('entity_add_edit', $fullform);
    }

    public function entityFieldsTable($fields,$formid){

        $columns = ['Order','','Name','Type','Required',''];
        $rows = [
            '{order}',
            '{{<img '.Notifications::tooltip('Drag and Drop to change field order').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/rows.png"/>}}',
            '{{<div style="width:100%; text-align:left;">{name}</div>}}',
            '{type}',
            '{required}',
            '{{<a data-toggle="modal" data-backdrop="static" data-target="#editformfield" class="smallicon" '
            . '     href="'.Url::base().'/admin/entities/fieldedit/{formname}/{sysname}" >'
                . '<img '.Notifications::tooltip('Edit {name}').' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/edit_icon.png"/>'
            . '</a>'
            . '<a class="smallicon" data-confirm="{name} will be deleted. Are you sure?" href="'.Url::base().'/admin/entities/fielddelete/{formid}/'.Input::get('id').'/{sysname}" >'
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
                'url' => Url::base().'/ajax/admin/entities/fieldrowreorder/'.$formid,
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

        $table = Generate::Table('entitiestable',$schematic);
        return $table->render(TRUE);
    }

    public function addEntityFieldForm($eform){

        $productform = $eform->render(
                ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'entities' .DS. 'views' .DS. 'panels' .DS.
                'entityfieldform.php',TRUE);

        $modal_settings = [
            'formid' => 'entityfieldform',
            'role' => 'dialog',
            'title' => 'Add Entity Field',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Create Entity Field' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'save_field_button'
                ]
            ]
        ];

        $startform = Overlays::ModalDialog($modal_settings, $productform);
        $this->set('enterfieldform', $startform);

        $this->setViewPanel('entityfieldpanel');
    }

    public function fieldEditForm($eform){

        $attributes = ['attributes' => $this->get('attributes')];
        $editfieldform = $eform->render(
                ABSOLUTE_PATH .DS. 'project' .DS. 'admin' .DS. 'entities' .DS. 'views' .DS. 'panels' .DS.
                'editformfield.php',TRUE,$attributes);

        $modal_settings = [
            'formid' => 'editfieldform',
            'role' => 'dialog',
            'title' => 'Edit Entity Form Field',
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

    /**
     * Creates select form options based on entities sent and products
     * @depends EntitiesController->selectFormFromProductId()
     * @param type $entities the raw unassigned entities
     * @param type $c_entities customer entities
     */
    public function createSelectForm($entities,$c_entities,$defaultvalue = null){

        if(count($c_entities)==0){

            $select .= '<select name="entities" class="entities">';
            $select .= '<option value="">--select--</option>';

            foreach($entities as $entity){

                $input .= '<input type="hidden" value="'.$entity->forms_id.'" name="entityformid_'.$entity->id.'">';
                $input .= '<input type="hidden" value="'.$entity->id.'" name="newentity">';

                $select .= '<option value="'.$entity->id.'" '.($entity->id == $defaultvalue ? 'selected="selected"' : '').' class="addnew">Add New '.ucfirst($entity->name).'</option>';
            }
        }
        else{

            $select .= '<select name="entities" class="entities ">';
            $select .= '<option value="">--select--</option>';

            foreach($c_entities as $key => $c_entity){

                $indentity = $c_entity['entity'];

                //if(!is_null($indentity)){

                    $indvalue = array_keys($indentity);
                    $indtype = $c_entity['type'];

                    $name = $indentity[$indvalue[0]];

                    if($c_entity['id'] == $defaultvalue){
                        $selection = 'selected="selected"';
                        $selectbool = true;
                    }

                    $select .= '<option value="'.$c_entity['id'].'" '.$selection.'>'.'<strong>'.$indtype.': </strong>'.$name.'</option>';
                    unset($selection);
                //}
            }

            foreach($entities as $entity){

                $input .= '<input type="hidden" value="'.$entity->forms_id.'" name="entityformid_'.$entity->id.'">';
                $input .= '<input type="hidden" value="'.$entity->id.'" name="newentity">';

                if($entity->id == $defaultvalue && $selectbool != true){
                    $selection = 'selected="selected"';
                }

                $select .= '<option value="'.$entity->id.'" '.$selection.' class="addnew">Add New '.ucfirst($entity->name).'</option>';
            }
        }

        $select .= '</select>'.$input;

        return $select;
    }

    /**
     * Reformats the sent entities into a table
     * @param array $storedentities
     * @param int $id the id of the stored entity
     */
    public function displayStoredEntities($storedentities,$id){

        foreach($storedentities as $storedentity){

            $entity = $storedentity['entity'];
            $keys = array_keys($entity);

            $type = $storedentity['type'];

            $entstr .= '<input type="hidden" class="storedentity entity_'.$id.'" name="entids[]" value="'.$id.'">'
            . '<table class="table table-bordered storedentity entity_'.$id.'" width="100%">'
                . '<tr>'
                    . '<td width="25%"><strong>Name: </strong>'.$entity[$keys[0]].'</td>'
                    . '<td width="60%"><strong>Type:</strong> '.$type.'</td>'
                    . '<td align="center">'
                        . '<a class="smallicon delete_stored_entity" id="entity_'.$id.'" >'
                            . '<img '.Notifications::tooltip('Delete '.$entity[$keys[0]]).' src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/delete_icon.png" width="18"/>'
                        . '</a>'
                    . '</td>'
                . '</tr>'
            . '</table>';
        }

        return $entstr;
    }
}
