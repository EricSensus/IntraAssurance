<?php
namespace Jenga\MyProject\Entities\Controllers;

use Jenga\App\Core\App;
use Jenga\App\Request\Input;
use Jenga\App\Html\Generate;
use Jenga\App\Helpers\Help;
use Jenga\App\Views\Overlays;
use Jenga\App\Request\Facade\Sanitize;
use Jenga\App\Views\Redirect;
use Jenga\App\Views\Notifications;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Elements;

class EntitiesController extends Controller
{


    public function index()
    {

        if (is_null(Input::get('action')) && is_null(Input::post('action'))) {

            $action = 'show';
        } else {

            if (!is_null(Input::get('action')))
                $action = Input::get('action');

            elseif (!is_null(Input::post('action')))
                $action = Input::post('action');
        }

        $this->$action();
    }

    public function getEntityDataById($id)
    {

        if (Sanitize::is_json($id)) {
            $eids = json_decode($id, true);
            $id = array_pop($eids);
        }

        return $this->model->customerEntityData()->where('id', $id)->first();
    }

    public function getEntityById($id)
    {

        return $this->model->where('id', $id)->first();
    }

    public function getEntityTypes($id = null)
    {

        $entities = $this->model->getEntityTypes($id);

        foreach ($entities as $entity) {
            $list[$entity->id] = $entity->type;
        }

        return $list;
    }

    public function getEntities()
    {

        $dbentities = $this->model->show();

        foreach ($dbentities as $entity) {

            $etype = $this->model->getEntityTypes($entity->entity_types_id);
            $entity->type = $etype[0]->type;

            $entities[] = $entity;
        }

        $this->view->entitiesDisplay($entities);
    }

    /**
     * Returns entity based on sent id
     *
     * @param int $id
     * @param string $column this defines the column to be used to search the table
     * @param int $pid The product id to be compared against
     *
     * @return array with the following keys: id,entity and type
     */
    public function getCustomerEntity($id = null, $column = 'primary', $pid = null)
    {

        return $this->model->getCustomerEntity($id, $column, $pid);
    }

    public function add()
    {

        $types = $this->getEntityTypes();

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '/admin/entities/createentity',
            'controls' => [
                'Name of Entity' => ['text', 'name', ''],
                'Entity Type' => ['select', 'etype', '', $types]
            ],
            'validation' => [
                'name' => [
                    'required' => 'Please enter the entity name'
                ]
            ]
        ];

        $form = Generate::Form('addentityform', $schematic);
        $eform = $form->render('horizontal', true);

        $this->view->addNewEntityForm($eform);
    }

    public function createEntity()
    {

        $this->view->disable();
        $entity = $this->model;

        $entity->name = Input::post('name');
        $entity->alias = strtolower(str_replace(' ', '_', $entity->name));
        $entity->entity_types_id = Input::post('etype');

        $save = $entity->save();

        if (!array_key_exists('ERROR', $save)) {

            Redirect::withNotice('Entity created, now configure the entity fields', 'success')
                ->to('/admin/entities/edit/' . $save['last_altered_row']);
        }
    }

    public function edit()
    {

        $id = Input::get('id');
        $entity = $this->model->find($id);
        $fields = $this->entityFields($entity->forms_id);

        $this->view->editEntityForm($entity, $fields);
    }

    public function entityFields($id)
    {

        return Elements::call('Forms/FormsController')->getFormFields($id);
    }

    public function delete()
    {

        if (!is_null(Input::get('id'))) {

            $id = Input::get('id');
            $delete = $this->model->where('id', '=', $id)->delete();
        } elseif (!is_null(Input::post('ids'))) {

            foreach (Input::post('ids') as $id) {
                $this->model->where('id', '=', $id)->delete();
            }
        }

        $url = Elements::call('Navigation/NavigationController')->getUrl('setup');
        Redirect::withNotice('The entity has been deleted', 'success')
            ->to($url);
    }

    public function createEntityField()
    {
        $form_ctrl = Elements::call('Forms/FormsController');
        $check = $form_ctrl->CheckByAliasAndCreate(Input::get('alias'), 'entities');

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '#',
            'controls' => [
                '{form}' => ['hidden', 'formid', $check->id],
                'Field Name' => ['text', 'entity_field_name', ''],
                'Field Type' => ['select', 'entity_field_type', '', $form_ctrl->controls],
                'Required' => ['checkbox', 'required', 'yes']
            ],
            'validation' => [
                'entity_field_name' => [
                    'required' => 'Please enter the entity field name'
                ],
                'entity_field_type' => [
                    'required' => 'Please enter the entity field type'
                ]
            ]
        ];

        $eform = Generate::Form('entityfieldform', $schematic);
        $this->view->addEntityFieldForm($eform);
    }

    public function saveFormFromEntityAlias($formid, $entityalias)
    {

        $entity = $this->model->find(['alias' => $entityalias]);

        $entity->forms_id = $formid;
        $save = $entity->save();

        if (!array_key_exists('ERROR', $save))
            return TRUE;
        else
            return $save['ERROR'];

    }

    public function saveEntityField()
    {

        $this->view->disable();
        parse_str(Input::get('formdata'), $formvalues);
        $formclass = Elements::call('Forms/FormsController');
        $type = $formvalues['entity_field_type'];
        $fieldattrs = $formclass->fields($type);

        //process the sent attributes from form with the user defined values
        foreach ($fieldattrs as $attr_formal_name => $attr_values) {

            $attr_name = $attr_values[0];
            $attributes[$attr_name] = $formvalues[$attr_name];
        }
        //add the required field
        $attributes['required'] = is_null($formvalues['required']) ? 'no' : 'yes';
        $key = Sanitize::clean($formvalues['entity_field_name']);

        $control = [
            $key => [
                'human_name' => $formvalues['entity_field_name'],
                'field_type' => $type,
                'field_attributes' => $attributes
            ]
        ];
        $form = $formclass->getForm($formvalues['formid']);;

        if (!Sanitize::is_json($form->controls)) {

            $form->controls = json_encode($control);
            $order = array_keys($control);
        } else {

            //decode previously saved controls
            $prevcontrols = json_decode($form->controls, TRUE);
            $order = json_decode($form->field_order, true);

            //check for previuos control key, remove and replace with new control
            if (array_key_exists($formvalues['key'], $prevcontrols)) {

                //replace with the new control
                Help::array_splice_assoc($prevcontrols, $formvalues['key'], 1, $control);

                //process the new field order
                $valuepos = array_search($formvalues['key'], $order);
                unset($order[$valuepos]);

                Help::insert_at_index($order, $valuepos, $key);
            } else {
                $prevcontrols = $prevcontrols + $control;
                //get the new entry
                array_push($order, $key);
            }

            $form->controls = json_encode($prevcontrols);
        }

        //save the new order
        $form->field_order = json_encode($order);

        $save = $form->save();

        if (!array_key_exists('ERROR', $save))
            Notifications::Alert('Entity field: ' . $formvalues['entity_field_name'] . ' has been saved', 'success');
    }

    public function fieldEdit()
    {

        $field = Input::get('field');

        $formelement = Elements::call('Forms/FormsController');
        $form = $formelement->getFormByName(Input::get('formname'));

        $controls = json_decode($form->controls, true);
        $control = $controls[$field];

        $required = $control['field_attributes']['required'];

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '#',
            'controls' => [
                '{form}' => ['hidden', 'formid', $form->id],
                '{key}' => ['hidden', 'key', $field],
                'Field Name' => ['text', 'entity_field_name', $control['human_name']],
//                'Field Type (read only)' => ['text', 'entity_field_type', $this->types[$control['field_type']], ['readonly' => 'readonly']],
                'Field Type' => ['select', 'entity_field_type', $control['field_type'], $formelement->controls],
                'Required' => ['checkbox', 'required', 'yes', $required == 'yes' ? ['checked' => 'checked'] : '']
            ],
            'validation' => [
                'entity_field_name' => [
                    'required' => 'Please enter the entity field name'
                ]
            ]
        ];
//        print_r($schematic['controls']);
//        exit;
        $eform = Generate::Form('editfieldform', $schematic);

        //get the system name for the control attribute
        if (!array_key_exists($control['field_type'], $formelement->controls)) {
            $type = 'text';
        } elseif ($control['field_type'] == 'comment') {
            $type = 'textarea';
        } else {
            $type = $control['field_type'];
        }

        $fxnname = $type . 'Attributes';
//        $attrs = $formelement->$fxnname($control['field_attributes']);
        $attrs = $formelement->getFieldAttributes($type);

        $this->set('attributes', $attrs);
        $this->view->fieldEditForm($eform);
    }

    public function fieldDelete()
    {

        $form = Elements::call('Forms/FormsController')->getForm(Input::get('formid'));

        $controls = json_decode($form->controls, true);
        $order = json_decode($form->field_order, true);

        if (array_key_exists(Input::get('field'), $controls)) {

            $key = Input::get('field');
            unset($controls[$key]);

            //save the new controls
            $form->controls = json_encode($controls);

            //process the new field order
            $valuepos = array_search($key, $order);
            unset($order[$valuepos]);

            //save the new order
            $form->field_order = json_encode($order);

            $form->save();

            Redirect::withNotice('The field has been deleted', 'success')
                ->to('/admin/entities/edit/' . Input::get('entityid'));
        } else {

            Redirect::withNotice('The field (' . Input::get('field') . ') does not exists', 'error')
                ->to('/admin/entities/edit/' . Input::get('entityid'));
        }
    }

    public function fieldRowReorder()
    {

        $this->view->disable();

        $formid = Input::get('formid');
        $form = Elements::call('Forms/FormsController')->getForm($formid);

        //$controls = json_decode($form->controls,true);
        $order = json_decode($form->field_order, true);

        //remove one because array positions start from zero
        $from = Input::get('fromPosition') - 1;
        $to = Input::get('toPosition') - 1;

        //reorder the array
        Help::array_move_values($order, $from, $to);

        $form->field_order = json_encode($order);
        $form->save();
    }

    public function saveEntity()
    {

        $this->view->disable();

        $entity = $this->model->find(Input::post('entityid'));

        $entity->name = Input::post('name');
        $entity->alias = strtolower(str_replace(' ', '_', Input::post('name')));
        $entity->entity_types_id = Input::post('entitytype');

        $save = $entity->save();

        if (!array_key_exists('ERROR', $save)) {

            $url = Elements::call('Navigation/NavigationController')->getUrl('setup');
            Redirect::withNotice($entity->name . ' has been saved', 'success')
                ->to($url . '#entities');
        }
    }

    /**
     * Saves entity date sent from remote elements into the customer_entity_data
     *
     * @param type $customerid
     * @param type $entityid
     * @param type $data
     *
     * @return object $results
     */

    public function saveEntityDataRemotely($customerid, $entityid, $data, $cdataid = null, $product_id)
    {
        $entity = $this->model->customerEntityData($cdataid);
        $entity->customers_id = $customerid;
        $entity->entities_id = $entityid;
        $entity->entity_values = $data;
        $entity->product_id = $product_id;
        $entity->save();
        return $entity->last_altered_row;
    }

    /**
     * Generates the entity select form based on product id and customer data id sent
     *
     * @param type $pid product id
     * @param type $cid customer data id
     */
    public function selectFormFromProductId($pid = null, $cid = null)
    {

        if (App::has('_ajax_request')) {
            $this->view->disable();

            $cid = Input::post('customerid');
            $pid = Input::post('productid');
        }

        //get the entities
        if (Sanitize::is_json($cid)) {
            $entities = $this->getCustomerEntity($cid, 'primary', $pid);
        } else {
            $entities = $this->getCustomerEntity($cid, 'customers_id', $pid);
        }

        //get the products
        $product = Elements::call('Products/ProductsController')->getProduct($pid, 'object');
        $etype = $this->model->getEntitiesByType($product->entity_types_id);

        if (App::has('_ajax_request')) {

            echo $this->view->createSelectForm($etype, $entities);
        } else {

            if (Sanitize::is_json($cid) == false) {
                $data_input = '<input type="hidden" value="' . $cid . '" name="data_id">';
            }

            $default = $entities[0]['id'];

            if (Input::post('ajax') != 'yes') {
                return $data_input . $this->view->createSelectForm($etype, $entities, $default);
            } else {
                echo $data_input . $this->view->createSelectForm($etype, $entities, $default);
            }
        }
    }

    /**
     * Generates full entity form from form id sent from quotes element
     */
    public function getFullEntityForm($formid = null, $wrap = 'table')
    {

        $this->view->disable();

        if (is_null($formid)) {
            $formid = Input::post('defaultval');
            echo Elements::call('Forms/FormsController')->processFormAttributes($formid, $formid);
        } else {
            return Elements::call('Forms/FormsController')->processFormAttributes($formid, $formid, $wrap);
        }
    }

    /**
     * Returns a rendered table and hidden input field showing the entity id type and values
     * @param type $ids
     */
    public function returnEntityEntries($ids = null)
    {

        $this->view->disable();

        if (is_null($ids))
            $entval = Input::post('entityval');
        else
            $entval = $ids;

        $stored = $this->getCustomerEntity($entval);

        if (Input::post('ajax') == 'yes') {
            echo $this->view->displayStoredEntities($stored, $entval);
        } else {
            return $this->view->displayStoredEntities($stored, $entval);
        }
    }

    public function getEntityIdByAlias($alias)
    {
        return $this->model->find([
            'alias' => $alias
        ]);
    }

    public function getEntityDataByCustomerAndEntityId($customer_id, $entity_id, $product_id)
    {
        return $this->model->customerEntityData()
            ->where('customers_id', $customer_id)
            ->where('entities_id', $entity_id)
            ->where('product_id', $product_id)
            ->show();
    }

    public function getEntityDataByfinder($finder)
    {
        return $this->model->customerEntityData($finder);
    }
}
