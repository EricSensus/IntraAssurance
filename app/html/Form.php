<?php
namespace Jenga\App\Html;

require APP_HTML .DS. 'facade' .DS. 'Zebra_Form.php';

use Jenga\App\Helpers;
use Jenga\App\Core\App;
use Jenga\App\Request\Facade\Sanitize;

class Form extends \Zebra_Form{
    
    public $schematic = array();
    public $controlids = array();
    
    public $cleaner;
    private $_form;
    
    public function __construct($name, $schematic, Sanitize $cleaner) {
        
        //check form method
        if(isset($schematic['method'])){
            
            if($schematic['method'] == ''){                
                $this->schematic['method'] = 'POST';
            }
            else{                
                $this->schematic['method'] = $schematic['method'];
            }
        }
        
        //check form action
        if(isset($schematic['action'])){            
            $this->schematic['action'] = SITE_PATH.$schematic['action'];
        }
        
        //check form attributes
        if(isset($schematic['attributes'])){            
            $this->schematic['attributes'] = $schematic['attributes'];
        }
        
        //check form engine
        if(isset($schematic['engine'])){
            $this->schematic['engine'] = $schematic['engine'];
        }
        
        //check form validation
        if(isset($schematic['validator'])){
            $this->schematic['validator'] = $schematic['validator'];
            
            //add necessary form attribute if unavailable
            if(!isset($schematic['attributes']) && $schematic['validator'] == 'parsley'){
                $this->schematic['attributes'] = ['data-parsley-validate' => ''];
            }
            elseif(isset($schematic['attributes']) 
                    && $schematic['validator'] == 'parsley' 
                    && !array_key_exists('data-parsley-validate', $schematic['attributes'])
                    ){
                
                $this->schematic['attributes']['data-parsley-validate'] = '';
            }
        }
        
        parent::__construct($name, 
                $this->schematic['method'], 
                $this->schematic['action'], 
                $this->schematic['attributes'], 
                $schematic['map'], 
                $schematic['engine']);
        
        //set the asset path for the process.php file and the system URI
        $this->assets_path(APP_HTML .DS. 'facade' .DS. 'Zebra_Form' .DS, RELATIVE_ROOT);
        
        //process the scripts and css settings
        $this->_displaySettings($schematic);
        
        $this->cleaner = $cleaner;
        $this->schematic = $schematic;
        
        $this->_processSchematic($schematic); 
    }
    
    /**
     * Process the scripts and css settings
     * 
     * @param type $schematic
     */
    private function _displaySettings($schematic){
        
        //the jQuery scripts settings
        if($schematic['preventjQuery'] == FALSE || !isset( $schematic['preventjQuery'] )){
            
            $this->_form = '<script src="'.RESOURCES .'/javascript/jquery/jquery-2.1.4.min.js"></script>';
        }
        
        //CSS settings TRUE, FALSE or path/to/other.css
        if(!isset($schematic['css']) || $schematic['css'] === TRUE){
            
            $this->_form .= '<link rel="stylesheet" '
            . 'href="'.RELATIVE_APP_PATH .'/html/facade/Zebra_Form/public/css/zebra_form.css"'
            . '>';
        }
        elseif($schematic['css'] != FALSE){
            
            $this->_form .= '<link rel="stylesheet" '
            . 'href="'.$schematic['css'].'"'
            . '>';
        }
        
        //the scripts settings are only for jQuery, the Zebra script is loaded automatically
        if($schematic['preventZebraJs'] != TRUE || !isset($schematic['preventZebraJs'])){
            $this->_form .= '<script src="'.RELATIVE_APP_PATH .'/html/facade/Zebra_Form/public/javascript/zebra_form.js"></script>';
        }
    }
    
    private function _processSchematic($schematic){
        
        //process form controls
        if(isset($schematic['controls'])){            
            $this->_processControls($schematic);
        }
    }
    
    /**
     * Loads the form controls and labels
     * 
     * @param type $schematic
     */
    private function _processControls($schematic){
        
        $controls = $schematic['controls'];
        
        foreach($controls as $name => $fielddata){
            
            if(is_string($fielddata)){                
                $stripname = str_replace(' ', '_', $this->_clean($name));
            }
            elseif(is_array($fielddata)){
                
                $stripname = str_replace(' ', '_', $this->_clean($fielddata[1]));
                
                $fieldtype = $fielddata[0];
                $defaultvalue = $fielddata[2];
            }
            
            //process field attributes
            $attributes = $this->_processFieldAttributes(end($fielddata));
            
            if($fieldtype == 'submit'){                
                $name = '';
            }
            
            if($fieldtype != 'hidden' && $fieldtype != 'checkbox'){
                
                if(strpos($name, '{')===FALSE){
                    
                    $this->add('label','label_'.$stripname, $stripname, $name, isset($attributes['label']) ? $attributes['label'] : ''); 
                    
                    if(is_array($attributes)){
                        unset($attributes['label']);
                    }  
                }
            }
            
            if($fieldtype == 'country'){
                
                $fieldtype = 'select';
                $getcountry = TRUE;
                
                //hack for the country, to escape Zebra error
                $fielddata[3] = [''=>'Select Country'];
            }
            
            if(is_array($attributes)){
                $attributes = count($attributes) == 0 ? '' : $attributes;
            }
            
            if($fieldtype != 'button'){
                $control = $this->add($fieldtype, $stripname, $defaultvalue, $attributes);
            }
            elseif($fieldtype == 'button'){
                $control = $this->add($fielddata[0], $stripname, $defaultvalue, $fielddata[3], $attributes);
            }
            
            if($fieldtype == 'checkbox'){
                
                if(strpos($name, '{')===FALSE){
                    
                    $this->add('label','label_'.$stripname, $stripname, $name, isset($attributes['label']) ? $attributes['label'] : ''); 
                    
                    if(is_array($attributes)){
                        unset($attributes['label']);
                    }  
                }
            }
            
            if($fieldtype != 'hidden'){                
                $this->controlids[] = $stripname;
            }
            
            //this is for select fields
            if($fieldtype == 'select'){
                
                //check the overwrite variable for select variable
                if(array_key_exists('',$fielddata[3])){                    
                    $overwrite = TRUE;
                }
                else{                    
                    $overwrite = FALSE;
                }
                
                //add the the select options
                $control->add_options($fielddata[3], $overwrite);
            }
            
            //this is for date fields
            if($fieldtype == 'date'){
                
                if(!is_null($fielddata[3])){
                    if(array_key_exists('format',$fielddata[3])){
                        $control->format($fielddata[3]['format']);
                    }
                }
                
                $control->set_rule(['date' => ['error'=>'Date is Invalid']]);
            }
            
            //this is for country fields
            if($getcountry){
                
                include($this->form_properties['assets_server_path']. "includes".DS."country.json.php");
                $jsonObj = json_decode($jsonCountries);
                
                $country = array_combine($jsonObj->keys, $jsonObj->values);
                
                //add the the country options
                $control->add_options($country);
                unset($getcountry);
            }
            
            //this is for upload fields
            if($fieldtype == 'file' && isset($fielddata[2]['upload_folder'])){
               
                $control->set_rule(['upload' => [
                    
                    $fielddata[2]['upload'], ZEBRA_FORM_UPLOAD_RANDOM_NAMES,
                    'error', 'File could not be uploaded'
                ]]);
            }
            elseif($fieldtype == 'file' && !isset($fielddata[2]['upload_folder'])){
                
                App::critical_error("The 'upload_folder' parameter must be set");
            }
            
            if(isset($schematic['validation'])){

                $validation_names = array_keys($schematic['validation']);
                
                if(in_array($stripname, $validation_names)){
                    
                    $validation = $this->validation($schematic, $stripname);
                    $control->set_rule($validation);
                }
            }
        }
    }
    
    /**
     * Processes the validation section
     * 
     * @param type $schematic
     * @param type $control
     * @return string
     */
    private function validation($schematic, $control){
        
        $crules = $schematic['validation'][$control];
        
        if(is_array($crules)){
            
            foreach($crules as $splrule => $message){

                if(array_key_exists($control, $schematic['validation'])){

                    $rules[$splrule] = ['error', $message ];
                }
            }
        }
        elseif(is_string($crules)){
            
            $rules[$crules] = ['error', $schematic['validation'][$control] ];
        }
        
        return $rules;
    }
    
    /**
     * Cleans the elements based on filter rules
     * 
     * @param type $txtparam
     * @return type
     */
    private function _clean(&$txtparam) {
        
        //add the filter rule 
        Sanitize::add_filter('txttolower', function($value, $params = NULL){
            return strtolower($value);
        });
        
        $txt = array(
                'vals' => $txtparam                
            );
        
        $filter_rules = array(
                'vals' => 'trim|sanitize_string|noise_words'   
            ); 
        
        $clean = $this->cleaner->filter($txt, $filter_rules);
        $txtparam = $clean['vals'];
        
        return $txtparam;
    }
    
    /**
     * Processes any attributes sent for each field and returns the zebra compatible attributes
     * 
     * @param type $attributes
     */
    private function _processFieldAttributes($attributes){
        
        if(isset($attributes['label'])){
            
            if($attributes['label'] == 'inside'){                
                $results['label'] = ['inside' => TRUE];
            }
        }
        else{
            $results = $attributes;
        }
        
        return $results;
    }
    
    /**
     * Loads the form
     * 
     * @param type $template
     * @param type $return
     * @param type $variables
     */
    public function render($template = '', $return = false, $variables = ''){
        
        if(isset($this->schematic['map'])){            
            $this->_form .= $this->cede(array($this, 'form_map'), $return, $variables);
        }
        else{            
            $this->_form .= $this->cede($template, $return, $variables);
        }
        
        //check form validation
        if(isset($this->schematic['validator'])){
            if($this->schematic['validator'] == 'parsley'){
                $this->_form .= '<script src="'.RELATIVE_APP_PATH.'/html/facade/Zebra_Form/plugins/parsleyjs/parsley.min.js" type="text/javascript"></script>'; 
            }
        }
        
        if($return == false){
            echo $this->_form;
        }
        else{
            return $this->_form;
        }
    }
    
    /**
     * Processes the schematic map section and rearranges the form controls accordingly
     * 
     * @param type $arg1
     * @param type $arg2
     * @return string
     */
    public function form_map($arg1, $arg2){
        
        $controlcount = 0;
        $rowcount = 0;
        
        foreach($this->schematic['map'] as $row){
            
            if($this->schematic['engine'] == 'bootstrap'){
                $rowclass = 'form-group';
                $cellclass = 'col-md-'.(12 / $row);
            }
            else{
                $rowclass = 'row '.($rowcount % 2 == 0 ? 'even' : '').' column-'.$row;
                $cellclass = 'cell';
            }
            
            $html .= '<div class="'.$rowclass.'">';

            if($this->schematic['engine'] == 'bootstrap'){
                $html .= '<div class="row">';
            }
            
            for($r=0; $r<=($row-1); $r++){
                
                if($row > 1){
                    $html .= '<div class="'.$cellclass.'">';    
                }
                elseif($row == 1 && $this->schematic['engine'] == 'bootstrap'){
                    $html .= '<div class="col-md-12">';
                }
                
                $id = $this->controlids[$controlcount]; 
                
                if(!is_null($arg2[$id])){                    
                    $type = $arg2[$id]->get_attributes(['type'])['type'];
                }
                
                if(isset($arg1['label_'.$id])){
                    $html .= $arg1['label_'.$id];
                }

                $radiochk = $this->_isCheckBoxOrRadio($arg1, $id);
                
                if(!$radiochk){
                    $html .= $arg1[$id];  
                }
                else{
                    
                    
                    $html .= '<table class="checkbox">'
                                . '<tr>';
                    
                    foreach($radiochk as $key){
                        
                        $html .= '<td>'.$arg1[$key].'</td>';
                        $html .= '<td>'.$arg1['label_'.$key]. '</td>';
                    }
                    
                    $html .= '</tr>'
                        . '</table>';
                }
                
                $html .= ($row > 1 || ($row == 1 && $this->schematic['engine'] == 'bootstrap') ? '</div>' : '' );
                
                if(!is_null($arg2[$id])){                    
                    $arg2[$id]->get_attributes(['type'])['type'];
                }
                
                $controlcount ++;
             }     
            
             if($this->schematic['engine'] == 'bootstrap'){
                $html .= '</div>';
             }
             
            $html .= '<div class="clear"></div>';
            $html .= '</div>';
            
            $rowcount++;
        }
        
        return $html;
    }
    
    /**
     * Checks if sent form control is a radio button or checkbox
     * 
     * @param type $arg
     * @param type $id
     * @return boolean
     */
    private function _isCheckBoxOrRadio($arg, $id){
        
        $list = [];
        foreach(array_keys($arg) as $key){
            
            //all radios and checkbox control are the id with an underscore
            if(strpos($key, 'label_') === FALSE){
                
                if(strpos($key, $id.'_') !== FALSE)
                    $list[] = $key;
            }
        }
        
        if(count($list) > 0)
            return $list;
        
        return FALSE;
    }
}