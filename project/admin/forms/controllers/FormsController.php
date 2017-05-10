<?php
namespace Jenga\MyProject\Forms\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Input;
use Jenga\MyProject\Elements;
use Symfony\Component\HttpFoundation\Request;

class FormsController extends Controller
{
    public $controls = [
        'text' => 'Text field',
        'textarea' => 'Text area',
        'select' => 'Select',
        'country' => 'Country',
        'number' => 'Number field',
        'checkbox' => 'Checkbox',
        'checkboxes' => 'Checkboxes (multiple)',
        'price' => 'Price/Value field',
        'email' => 'Email address',
        'radios' => 'Radio (multiple)',
        'date' => 'Date field',
        'yes_no' => 'Yes/No option',
        'file' => 'File upload',
        'hidden' => 'Hidden',
        'password' => 'Password Field (masked)',
        'hint' => 'Hints and Information'
    ];
    public $fields_attributes = [
        'text' => [
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'textarea' => [
            'Rows' => ['rows', '', 'text'],
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'date' => [
            'Date Format (d - day, F,M - Month, Y - Year)' => ['date_format', 'd M Y', 'select', [
                'mm/dd/yy' => 'Default - mm/dd/yy',
                'yy-mm-dd' => 'ISO 8601 - yy-mm-dd',
                'd M, y' => 'Short - d M, y'],
                'd MM, y' => 'Medium - d MM, y',
                'DD, d MM, yy' => 'Full - DD, d MM, yy',
            ],
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'select' => [
            'Options (Separate by comma)' => ['choices', '', 'textarea'],
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'country' => [
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'checkboxes' => [
            'Options (Separate by comma)' => ['choices', '', 'textarea'],
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'number' => [
            'Minimum Value' => ['min', '', 'text'],
            'Maximum Value' => ['max', '', 'text'],
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'price' => [
            'Minimum Value' => ['min', '', 'text'],
            'Maximum Value' => ['max', '', 'text'],
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'checkbox' => [
            'Checked' => ['default', 'yes', 'checkbox'],
            'Class' => ['class', '', 'text'],
        ],
        'radios' => [
            'Options (Separate by comma)' => ['choices', '', 'textarea'],
            'Class' => ['class', '', 'text'],
            'Default' => ['default', '', 'text']
        ],
        'yes_no' => [
            'Class' => ['class', '', 'text'],
            'Default' => ['default', 'No', 'text']
        ],
        'hint' => [
            'Class' => ['class', '', 'text'],
            'Message' => ['message', '', 'textarea'],
        ]
    ];

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

    public function CheckByAliasAndCreate($alias, $from)
    {

        $check = $this->model->where('form_name', '=', $alias)->show();

        if ($this->model->count() == '0') {

            $createform = $this->model;
            $createform->form_name = $alias;

            $save = $createform->save();

            if (!array_key_exists('ERROR', $save)) {

                //update the form id in the products/entities table
                if ($from == 'products')
                    Elements::call('Products/ProductsController')->saveFormFromProductAlias($save['last_altered_row'], $alias);
                elseif ($from == 'entities')
                    Elements::call('Entities/EntitiesController')->saveFormFromEntityAlias($save['last_altered_row'], $alias);

                return $this->model->find($save['last_altered_row']);
            }
        } else {

            return $check[0];
        }
    }

    public function getFormByName($name)
    {

        return $this->model->where('form_name', $name)->first();
    }

    public function getFieldByType()
    {
        $this->view->disable();
        $type = Input::get('field_type');
        $fields = $this->fields($type);
        echo $this->_process($fields, null);
    }

    public function fields($type)
    {
        if (array_key_exists($type, $this->fields_attributes))
            return $this->fields_attributes[$type];
        else
            return [];
    }

    /**
     * Processes the sent field attributes and returns a formatted table
     * @param array $attrs
     * @param string $params
     * @return string
     */
    private function _process($attrs, $params = null)
    {
        $table = '<table cellspacing="0" cellpadding="0">';

        foreach ($attrs as $key => $attr) {
            $table .= '<tr class="row">'
                . '<td width="30%"><label>' . $key . '</label></td>'
                . '<td width="70%">';
            switch ($attr[2]) {
                case 'hidden':
                    $table .= '<input type="hidden" class="control text" value="' . $attr[1] . $params[$attr[0]] . '" id="' . $attr[0] . '" name="' . $attr[0] . '">';
                    break;
                case 'county':
                    $table .= '<input type="text" class="control text" value="' . $attr[1] . $params[$attr[0]] . '" id="' . $attr[0] . '" name="' . $attr[0] . '">';
                    break;
                case 'text':
                    $table .= '<input type="text" class="control text" value="' . $attr[1] . $params[$attr[0]] . '" id="' . $attr[0] . '" name="' . $attr[0] . '">';
                    break;
                case 'date':
                    $table .= '<input type="date" class="control text date" value="' . $attr[1] . $params[$attr[0]] . '" id="' . $attr[0] . '" name="' . $attr[0] . '">';
                    break;
                case 'textarea':
                    $table .= '<textarea wrap="soft" rows="3" name="' . $attr[0] . '" id="' . $attr[0] . '">' . $params[$attr[0]] . '</textarea>';
                    break;

                case 'select':
                    if (!is_null($params[$attr[0]]))
                        $attribute = 'selected="selected"';

                    $table .= '<select name="' . $attr[0] . '" id="' . $attr[0] . '">';
                    $table .= '<option value="' . $attr[1] . '">--select--</option>';

                    foreach ($attr[3] as $skey => $svalue) {
                        $table .= '<option value="' . $skey . '" ' . $attribute . '>' . $svalue . '</option>';
                    }

                    $table .= '</select>';
                    break;
                case 'radios':
                    if (!is_null($params[$attr[0]]))
                        $attribute = 'checked="checked"';
                    foreach ($attr[3] as $skey => $svalue) {
                        $table .= '<input type="radio" value="' . $skey . '" ' . $attribute . '>' . $svalue . '/>';
                    }
                    break;
                case 'checkboxes':
                    if (!is_null($params[$attr[0]]))
                        $attribute = 'checked="checked"';
                    foreach ($attr[3] as $skey => $svalue) {
                        $table .= '<input type="checkbox" value="' . $skey . '" ' . $attribute . '>' . $svalue . '/>';
                    }
                    break;
                case 'checkbox':
                    if (!is_null($params[$attr[0]]))
                        $attribute = 'checked="checked"';

                    $table .= '<input type="checkbox" value="' . $attr[1] . '" name="' . $attr[0] . '" id="' . $attr[0] . '" ' . $attribute . '>';
                    break;
                case 'yes_no':
                    $table .= '<input type="radio" value="Yes" name="' . $attr[0] . '" id="' . $attr[0] . '" >';
                    $table .= '<input type="radio" value="No" name="' . $attr[0] . '" id="' . $attr[0] . '" >';
                    break;
            }

            $table .= '</td>'
                . '</tr>';
        }
        $table .= '</table>';

        return $table;
    }

    public function getFormFields($formid)
    {

        $form = $this->getForm($formid);
        $fields = json_decode($form->controls, true);
        $order = json_decode($form->field_order, true);
        if (!is_null($order)) {

            $count = 1;
            foreach ($order as $fieldkey) {

                $field = $this->model->createEmpty();

                $field->formid = $formid;
                $field->formname = $form->form_name;
                $field->order = $count;

                //get the field values acording to the fieldkey
                $attributes = $fields[$fieldkey];

                $field->name = $attributes['human_name'];
                $field->sysname = $fieldkey;
                $field->type = $this->controls[$attributes['field_type']];
                $field->required = ($attributes['field_attributes']['required'] == 'yes' ? '*' : '');

                $fieldcontainer[] = $field;
                $count++;
            }
            return $fieldcontainer;
        }
    }

    public function getForm($formid)
    {

        $form = $this->model->find($formid);
        return $form;
    }

    /**
     * Processes the sent form fields into their correct HTML tags
     * @param type $formid
     * @param type $senderid
     * @param type|string $wrap
     * @return type
     */
    public function processFormAttributes($formid, $senderid, $wrap = 'table')
    {

        $form = $this->getForm($formid)->data;
        $controls = json_decode($form->controls, true);
        $order = json_decode($form->field_order, true);
        foreach ($order as $ctrlkey) {
            $control = $controls[$ctrlkey];
            switch ($control['field_type']) {
                case 'select':
                    $types[] = 'select';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createSelect($ctrlkey, $control);
                    break;
                case 'country':
                    $types[] = 'country';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createCountries($ctrlkey, $control);
                    break;
                case 'date':
                    $types[] = 'date';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createDate($ctrlkey, $control);
                    break;
                case 'text':
                    $types[] = 'text';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createInput($ctrlkey, $control);
                    break;
                case 'textarea':
                    $types[] = 'textarea';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createTextArea($ctrlkey, $control);
                    break;
                case 'number':
                    $types[] = 'number';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createInput($ctrlkey, $control);
                    break;
                case 'price':
                    $types[] = 'price';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }

                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createInput($ctrlkey, $control, 'price');
                    break;
                case 'checkbox':
                    $types[] = 'checkbox';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name'] . $rkey] = $this->createCheckbox($ctrlkey, $control);
                    break;
                    break;
                case 'radios':
                    $types[] = 'radios';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name']] = $this->createRadios($ctrlkey, $control);
                    break;
                case 'checkboxes':
                    $types[] = 'checkboxes';
                    if ($control['field_attributes']['required'] == 'yes') {
                        $required[] = 'yes';
                        $rkey = '<span class="required">*</span>';
                    }
                    $fields[] = $ctrlkey;
                    $list[$control['human_name']] = $this->createCheckboxes($ctrlkey, $control);
                    break;
                case 'yes_no':
                    $types[] = 'yes_no';
                    $fields[] = $ctrlkey;
                    $list[$control['human_name']] = $this->createYesNo($ctrlkey, $control);
                    break;
                    break;
                case 'hint':
                    $types[] = 'yes_no';
                    $fields[] = $ctrlkey;
                    $list[$control['human_name']] = $this->createHint($ctrlkey, $control);
                    break;

            }

            unset($rkey);
        }

        $formfields['index'] = $fields;
        $formfields['fields'] = $list;
        $formfields['types'] = $types;
        $formfields['required'] = $required;
        return $this->wrapFields($formfields, $wrap, $senderid);
    }

    private function getClass($attr)
    {
        $string = $attr['class'];
        if ($attr['required'] == 'yes') {
            $string .= ' required';
        }
        if (empty($string)) {
            return null;
        }
        $classes = explode(',', $string);
        $_class = [];
        foreach ($classes as $class) {
            $_class[] = $class;
        }
        if (empty($_class)) {
            return null;
        }
        return implode(' ', $_class);
    }

    /**
     * Create select list for form
     *
     * @param type $name
     * @param type $control
     * @param array $options
     * @return string
     */
    public function createSelect($name, $control, $options = null)
    {
        $attr = $control['field_attributes'];
        $class = $this->getClass($attr);
        $ctrl = '<select name="' . $name . '" id="' . $name . '" '
            . (empty($class) ? '' : 'class="' . $class . '"')
            . ' >';
        $ctrl .= '<option value="">--select--</option>';
        $choices = empty($options) ? explode(',', $control['field_attributes']['choices']) : $options;
        foreach ($choices as $choice) {
            if (is_array($testString = $this->keyValue($choice))) {
                $ctrl .= '<option value="' . $testString[0] . '" '
                    . ($control['field_attributes']['default'] == $testString[0] ? 'selected="selected"' : '') . '>'
                    . $testString[1] . '</option>';
            } else {
                $ctrl .= '<option value="' . trim($choice) . '" '
                    . ($control['field_attributes']['default'] == $choice ? 'selected="selected"' : '') . '>'
                    . $choice . '</option>';
            }
        }

        $ctrl .= '</select>';

        return $ctrl;
    }

    private function keyValue($string)
    {
        $raw = trim($string);
        if (str_contains($raw, ':')) {
            return explode(':', $raw);
        }
        return $raw;
    }

    public function createDate($name, $control)
    {
        $attr = $control['field_attributes'];
        $attr['class'] .= ',control,mydatepicker,text';
        $class = $this->getClass($attr);
        $ctrl = '<input name="' . $name . '" class="' . $class . '" type="text" id="' . $name . '" value="' . $attr['default'] . '" ';

        if (!empty($attr['date_format']))
            $ctrl .= 'dervis-date="' . $attr['date_format'] . '" ';


        $ctrl .= '/>';

        return $ctrl;
    }

    /**
     * Create input field for text,numbers and prices
     * @param type $name
     * @param type $control
     * @return string
     */
    private function createInput($name, $control)
    {
        $attr = $control['field_attributes'];
        $type = $control['field_type'];
        $attr['class'] .= ',control,text';
        $class = $this->getClass($attr);
        if ($type == 'price')
            $type = 'number';
        elseif ($type == 'date')
            $type = 'text';

        $ctrl = '<input name="' . $name . '" class="' . $class . '" type="' . $type . '" id="' . $name . '" value="' . $attr['default'] . '" ';

        if (!empty($attr['min']))
            $ctrl .= 'min="' . $attr['min'] . '" ';

        if (!empty($attr['max']))
            $ctrl .= 'max="' . $attr['max'] . '" ';

        $ctrl .= '/>';

        return $ctrl;
    }

    /**
     * @param $name
     * @param $control
     * @return string
     * @todo Is this the best way
     */
    private function createHint($name, $control)
    {
        return '<span></span>';
    }

    /**
     * Create checklist for form
     * @todo Work on default selected options
     * @param type $name
     * @param type $control
     * @return string
     */
    private function createCheckboxes($name, $control)
    {
        $attr = $control['field_attributes'];
        $class = $this->getClass($attr);
        $choices = explode(',', $attr['choices']);
        $ctrl = '';
        foreach ($choices as $choice) {
            $ctrl .= '<label><input name="' . $name . '[]" type="checkbox" value="' . $choice . '" class="' . $class . '"/> ' . $choice . '</label>';
        }
        return $ctrl;
    }

    private function createYesNo($name, $control)
    {
        $attr = $control['field_attributes'];
        $default = $attr['default'] == 'Yes' ? 'Yes' : 'No';
        $ctrl = '';
        //  $ctrl = '<div class="form-inline">';
        // <label class="radio-inline">
        $ctrl .= '<label><input name="' . $name . '" type="radio" id="' . $name . '" value="Yes"'
            . ($default == 'Yes' ? 'checked="checked"' : '') .
            ' />Yes</label>';
        $ctrl .= '<label><input name="' . $name . '" type="radio" id="' . $name . '" value="No"'
            . ($default == 'No' ? 'checked="checked"' : '') .
            ' />No</label>';
        //    $ctrl .= '</div>';
        return $ctrl;
    }

    /**
     * Wraps the sent form dields into either table rows or divs
     *
     * @param type $fields
     * @param type $wrap
     * @return string
     */
    public function wrapFields($fields, $wrap, $formname)
    {
        $ctrl = '';
        if ($wrap == 'table') {

            foreach ($fields['fields'] as $label => $field) {

                $ctrl .= '<tr class="extra_form_fields">';
                $ctrl .= '<td>';
                $ctrl .= '<label>' . $label . '</label>';
                $ctrl .= '<input type="hidden" name="index_' . $formname . '" value="' . htmlspecialchars(json_encode($fields['index'])) . '" />';
                $ctrl .= '</td>';
                $ctrl .= '<td>';
                $ctrl .= $field;
                $ctrl .= '</td>'
                    . '</tr>';
            }
        } elseif ($wrap == 'div') {

            foreach ($fields['fields'] as $label => $field) {

                $ctrl .= '<div class="row extra_form_fields">';
                $ctrl .= '<div class="label">';
                $ctrl .= '<label>' . $label . '</label>';
                $ctrl .= '<input type="hidden" name="index_' . $formname . '" value="' . htmlspecialchars(json_encode($fields['index'])) . '" />';
                $ctrl .= '</div>';
                $ctrl .= '<div class="field">';
                $ctrl .= $field;
                $ctrl .= '</div>'
                    . '</div>';
            }
        }

        return $ctrl;
    }

    public function getFieldAttributes($control, $params = null)
    {
        $fields = $this->fields($control);
        return $this->_process($fields, $params);
    }

    private function createRadios($name, $control)
    {
        $attr = $control['field_attributes'];
        $class = $this->getClass($attr);
        $choices = explode(',', $attr['choices']);
        $ctrl = '';
        foreach ($choices as $choice) {
            if (is_array($testString = $this->keyValue($choice))) {
                $ctrl .= '<label><input name="' . $name . '" type="radio" value="' . $testString[0] . '" class="' . $class . '"/> ' . $testString[1] . '</label>';
            } else {
                $ctrl .= '<label><input name="' . $name . '" type="radio" value="' . $choice . '" class="' . $class . '"/> ' . $choice . '</label>';
            }
        }
        return $ctrl;
    }

    private function createCountries($name, $control)
    {
        $countries = Elements::call('Travel/TravelController')->getCountries();
        return $this->createSelect($name, $control, $countries);
    }

    private function createTextArea($name, $control)
    {
        $attr = $control['field_attributes'];
        $attr['class'] .= ',control,text';
        $class = $this->getClass($attr);
        return "<textarea name='$name' class='$class'  id='$name'" .
            (empty($attr['rows']) ? '' : " rows='" . $attr['rows'] . "'")
            . '>' . $attr['default'] . '</textarea>';
    }

    private function createCheckbox($name, $control)
    {
        $attr = $control['field_attributes'];
        $class = $this->getClass($attr);
        $checked = ($attr['default'] == 'yes') ? 'checked' : '';
        $ctrl = '<input name="' . $name . '" type="checkbox" id="' . $name . '" value="' . $name . '" class="' . $class . '" ' . $checked . ' >';

        return $ctrl;
    }
}
