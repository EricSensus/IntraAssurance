<?php
namespace Jenga\MyProject\Products\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Helpers\Help;
use Jenga\App\Html\Generate;
use Jenga\App\Request\Facade\Sanitize;
use Jenga\App\Request\Input;
use Jenga\App\Views\Notifications;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Elements;

class ProductsController extends Controller
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

    public function getProduct($id = NULL, $return = 'array')
    {
        if (!is_null($id)) {
            return $this->model->getProduct($id, $return);
        } else {
            return $this->model->all();
        }
    }

    public function getProducts()
    {

        $dbproducts = $this->model->show();

        foreach ($dbproducts as $product) {

            //get renew status
            $product->renew = ($product->type == 0 ? 'Recurring/Renewable' : 'Non-Recurring');
            $products[] = $product;
        }

        $this->view->productsDisplay($products);
    }

    public function addedit()
    {

        $id = Input::get('id');
        $product = $this->model->find($id);
        $fields = $this->productFields($product->forms_id);
        $this->view->addProductForm($product, $fields);
    }

    public function productFields($id)
    {

        $fields = Elements::call('Forms/FormsController')->getFormFields($id);
        return $fields;
    }

    public function add()
    {

        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            //'css' => false,
            'action' => '/admin/products/createproduct',
            'controls' => [
                'Name of Product' => ['text', 'name', ''],
                'Product Type' => ['select', 'ptype', 0, ['0' => 'recurring / renewable', '1' => 'one-time (non-renewable)']]
            ],
            'validation' => [
                'name' => [
                    'required' => 'Please enter the product name'
                ]
            ]
        ];

        $form = Generate::Form('addform', $schematic);
        $pform = $form->render('horizontal', true);

        $this->view->addNewProductForm($pform);
    }

    public function createProduct()
    {

        $this->view->disable();
        $product = $this->model;

        $product->name = Input::post('name');
        $product->alias = strtolower(str_replace(' ', '_', $product->name));
        $product->type = Input::post('ptype');

        $save = $product->save();

        if (!array_key_exists('ERROR', $save)) {

            Redirect::withNotice('Product created, now configure the product fields', 'success')
                ->to('/admin/products/addedit/' . $save['last_altered_row']);
        }
    }

    public function createProductField()
    {

        $check = Elements::call('Forms/FormsController')->CheckByAliasAndCreate(Input::get('alias'), 'products');
        $schematic = [
            'preventjQuery' => TRUE,
            'method' => 'POST',
            'action' => '#',
            'controls' => [
                '{form}' => ['hidden', 'formid', $check->id],
                'Field Name' => ['text', 'product_field_name', ''],
                'Field Type' => ['select', 'product_field_type', '', Elements::call('Forms/FormsController')->controls],
                'Required' => ['checkbox', 'required', 'yes']
            ],
            'validation' => [
                'product_field_name' => [
                    'required' => 'Please enter the product field name'
                ],
                'product_field_type' => [
                    'required' => 'Please enter the product field type'
                ]
            ]
        ];
        $pform = Generate::Form('productfieldform', $schematic);
        $this->view->fullProductForm($pform);
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
                'Field Name' => ['text', 'product_field_name', $control['human_name']],
                'Field Type' => ['select', 'product_field_type', $control['field_type'],$formelement->controls],
                'Required' => ['checkbox', 'required', 'yes', $required == 'yes' ? ['checked' => 'checked'] : '']
            ],
            'validation' => [
                'product_field_name' => [
                    'required' => 'Please enter the product field name'
                ]
            ]
        ];

        $eform = Generate::Form('editfieldform', $schematic);

        //get the system name for the control attribute
        if (!array_key_exists($control['field_type'], $formelement->controls)) {
            $type = 'text';
        } else {
            $type = $control['field_type'];
        }
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
                ->to('/admin/products/addedit/' . Input::get('productid'));
        } else {

            Redirect::withNotice('The field (' . Input::get('field') . ') does not exists', 'error')
                ->to('/admin/products/addedit/' . Input::get('productid'));
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

    public function saveProductField()
    {

        $this->view->disable();

        parse_str(Input::get('formdata'), $formvalues);
        $type = $formvalues['product_field_type'];
        $fieldattrs = Elements::call('Forms/FormsController')->fields($type);
        //process the sent attributes from form with the user defined values

        foreach ($fieldattrs as $attr_formal_name => $attr_values) {
            $attr_name = $attr_values[0];
            $attributes[$attr_name] = $formvalues[$attr_name];
        }
        //add the required field
        $attributes['required'] = is_null($formvalues['required']) ? 'no' : 'yes';

        $key = Sanitize::clean($formvalues['product_field_name']);

        $control = [
            $key => [
                'human_name' => $formvalues['product_field_name'],
                'field_type' => $type,
                'field_attributes' => $attributes
            ]
        ];
        $form = Elements::call('Forms/FormsController')->getForm($formvalues['formid']);
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
            Notifications::Alert('Product field: ' . $formvalues['product_field_name'] . ' has been saved', 'success');
    }

    public function saveFormFromProductAlias($formid, $productalias)
    {

        $product = $this->model->find(['alias' => $productalias]);

        $product->forms_id = $formid;
        $save = $product->save();

        if (!array_key_exists('ERROR', $save))
            return TRUE;
        else
            return $save['ERROR'];

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

        if (is_null($delete)) {

            $url = Elements::call('Navigation/NavigationController')->getUrl('setup');
            Redirect::withNotice('The product has been deleted', 'success')
                ->to($url);
        }
    }

    public function saveProduct()
    {

        $this->view->disable();

        $product = $this->model->find(Input::post('productid'));

        $product->name = Input::post('name');
        $product->alias = strtolower(str_replace(' ', '_', Input::post('name')));
        $product->type = Input::post('producttype');
        $product->entity_types_id = Input::post('entitytype');
        $product->multiple_entities = Input::post('multiple_entities');

        $save = $product->save();

        if (!array_key_exists('ERROR', $save)) {

            $url = Elements::call('Navigation/NavigationController')->getUrl('setup');
            Redirect::withNotice($product->name . ' has been saved', 'success')
                ->to($url . '#products');
        }
    }

    public function getFullProductForm($id = null)
    {

        $this->view->disable();

        if (Input::post('id') != '')
            $id = Input::post('id');

        $product = $this->model->find($id)->data;

        if (Input::post('id') != '') {
            echo Elements::call('Forms/FormsController')->processFormAttributes($product->forms_id, $id);
        } else {
            return Elements::call('Forms/FormsController')->processFormAttributes($product->forms_id, $id);
        }
    }

    public function getProductByAlias($alias)
    {
        return $this->model->find(['alias' => $alias]);
    }
}