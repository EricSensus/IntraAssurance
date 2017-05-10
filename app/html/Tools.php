<?php
namespace Jenga\App\Html;

use Jenga\App\Views;
use Jenga\App\Core\App;
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Html\Generate;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

class Tools{
    
    public $images_path;
    public $config;
    public $addsettings = '';
    
    private $_tools_used;
    private $_settings;
    private $_confirm;
    private $_using;
    private $_rand;
    
    function __construct($config = array()) {
        
        $this->config = $config;
        
        //create a random to identify each table instance
        $this->_rand = rand(0, 10000);
        
        //add universal tool settings
        if(array_key_exists('settings', $config)){            
            //add default wrap to table
            if(!array_key_exists('wrap_with',$config['settings'])){
                $config['settings']['wrap_with'] = 'table';
            }
        }
        else{
            //add default wrap to table
            $config['settings']['wrap_with'] = 'table';
        }
        
        $this->settings($config['settings']);
        
        foreach($config['tools'] as $key => $tool){
            
            $key = strtolower(str_replace(' ', '_', $key));
            $using_present = array_search('using', array_keys($tool));
            
            if($using_present != FALSE){                
                $this->_checkbox = TRUE;
                $this->_using[$key] = $tool['using'];
            }
            
            if(array_search('confirm', array_keys($tool))){                
                $this->_confirm[$key] = $tool['confirm'];
            }
        }
    }
    
    function __call($name, $arguments) {
        
        $this->_name($name);
        
        //add the function's default image info
        $this->_image($name);
        $this->_tools_used[$name]['path'] = $arguments[0]['path'];
        
        if(array_key_exists('icoclass', $arguments[0])){
            $this->_icon($name,$arguments[0]['icoclass']);
        }
        
        if(array_key_exists('tooltip', $arguments[0])){
            $this->_tooltip($name,$arguments[0]['tooltip']);
        }
        
        $this->_confirmform($name);
    }
    
    private function _confirmform($function){
        
        if(!is_null($this->_confirm)){
            
            if(array_key_exists($function, $this->_confirm)){

                $settings = [
                    'id'=> $function.$this->_rand.'Modal',
                    'role' => 'dialog',
                    'title' => 'Confirm Action: '.ucwords(str_replace('_', ' ', $function)),
                    'buttons' => [
                        'Cancel' => [
                            'class' => 'btn btn-default',
                            'data-dismiss' => 'modal'
                        ],
                        'Confirm '.ucwords(str_replace('_', ' ', $function)) => [
                            'type' => 'submit',
                            'class' => 'btn btn-primary'
                        ]
                    ]
                ];

                $initial_message = Views\Notifications::Alert('No records have been sent', 'info', TRUE, TRUE);

                $fxnpath = $this->_tools_used[$function]['path'];

                $this->_tools_used[$function]['resource'] = '<form action="'.$fxnpath.'" method="post">'
                        . '<input type="hidden" name="'.$function.'" value="'.$function.'" >'
                        . '<input type="hidden" name="destination" value="'.Url::current().'" >'
                            . Views\Overlays::Modal($settings, $initial_message)
                        .'</form>';

                $this->_tools_used[$function]['addition'] = 'data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#'.$function.$this->_rand.'Modal" ';;
            }
        }
    }
    
    private function _name($function, $name = ''){
        
        if($name != ''){            
            $this->_tools_used[$function]['name'] = $name;
        }
        else{            
            $this->_tools_used[$function]['name'] = ucfirst($function);
        }
    }
    
    private function _icon($function, $name = ''){
        
        if(strpos($function, '::')!==FALSE){
            $fxn = explode('::', $function);
            $function = $fxn[1];
        }
        
        if($name != ''){            
            $this->_tools_used[$function]['icon'] = $name;
        }
        else{            
            $this->_tools_used[$function]['icon'] = ucfirst($function);
        }
    }
    
    private function _image($function, $image_name = ''){
        
        if($image_name == ''){            
            $this->_tools_used[$function]['image'] = rtrim($this->images_path,'/').'/'.$function.'_icon.png';
        }
        else{            
            $this->_tools_used[$function]['image'] = $this->images_path.'/'.$image_name;
        }
    }
    
    private function _tooltip($function,$message){
        
        $fxn = explode('::', $function);
        $this->_tools_used[$fxn[1]]['tooltip'] = $message;
    }
    
    /**
     * Processes the attributes and returns a string 
     * of the properties to be inserted
     * 
     * @param type $properties
     * @return string $properties_string
     */
    private function _attrs($properties){
        
        foreach($properties as $attr => $value){            
            $properties_string .= $attr.' = "'.$value.'" ';
        }
        
        return $properties_string;
    }

    public function settings($params){
        
        $this->_settings = $params;
    }
    
    /**
     * Process the add button
     * @param type $params
     */
    public function add($params){
        
        $this->_name('add',$params['name']);
        $this->_image('add', $params['image']);
        
        if(array_key_exists('icoclass', $params)){
            $this->_icon(__METHOD__,$params['icoclass']);
        }
        
        if(array_key_exists('tooltip', $params)){
            $this->_tooltip(__METHOD__,$params['tooltip']);
        }
        
        if($params['type'] == 'modal'){
            
            if(isset($params['settings'])){
                
                $this->_tools_used['add']['id'] = $params['settings']['id'].$this->_rand;
                unset($params['settings']['id']);
                
                $settings = array_merge($params['settings'],['data-toggle'=>'modal']);
                
                //modify the data-target in settings
                $settings['data-target'] = $settings['data-target'].$this->_rand;
                
                $this->addsettings = $this->_attrs($settings);
            }
            else{
                
                $this->_tools_used['add']['id'] = 'addform'.$this->_rand;
                $properties = ['data-toggle'=>'modal','data-target' => '#addform'.$this->_rand,'data-backdrop' => 'static'];
                $this->addsettings = $this->_attrs($properties);
            }
            
            $this->_tools_used['add']['path'] = SITE_PATH.'/ajax'.$params['path'];
            
            $modal_settings = [
                'id' => $this->_tools_used['add']['id'],
                'role' => 'dialog',
                'title' => ''
            ];
            
            $this->_tools_used['add']['resource'] = Overlays::Modal($modal_settings);
        }
        else{
            
            $this->_tools_used['add']['path'] = SITE_PATH.$params['path'];
        }
    }
    
    /**
     * Processing for the import button and form
     * @param type $params
     */
    public function import($params){
        
        //insert upload folder
        if(array_key_exists('upload_folder', $params))
            $upload['upload_folder'] = $params['upload_folder'];
        else
            App::exception ('Please add upload folder to the table tools');
        
        //bind the file extensions in this format data-allowed-file-extensions = ['csv','txt']
        if(array_key_exists('allowed_file_extensions', $params)){
            
            foreach ($params['allowed_file_extensions'] as $ext) {
                
                $filext[] = '"'.$ext.'"';
            }
            
            $filexts = join(',', $filext);
            
            $uploadparams['data-allowed-file-extensions'] = '['.$filexts.']';
        }
        
        //insert file preview setting
        if(array_key_exists('file_preview', $params)){
            
            $uploadparams['data-show-preview'] = ($params['file_preview'] == TRUE ? 'true' : 'false');
        }
        
        //process upload parameters
        foreach ($uploadparams as $attr => $value) {
            
            $upload_string[] = $attr.' = \''.$value.'\' ';
        }
        
        $import = '<form id="importform" enctype="multipart/form-data" action="'.$params['path'].'" method="POST" >'
                    . '<input type="hidden" name="'.ini_get("session.upload_progress.name").'" value="123" />'
                    . '<input type="hidden" name="upload_folder" value="'.$upload['upload_folder'].'">'
                    . '<input id="input-id" name="file_import" type="file" class="file" '.join(' ', $upload_string).'>'
                . '</form>';
        
        $settings = [
            'id'=>'import'.$this->_rand.'Modal',
            'role' => 'dialog',
            'title' => 'Upload Import File',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ]
            ]
        ];
        
        $this->_tools_used['import']['id'] = 'import'.$this->_rand;
        $this->_tools_used['import']['resource'] = Views\Overlays::Modal($settings, $import);        
        $this->_tools_used['import']['path'] = $params['path'];
        
        if(array_key_exists('icoclass', $params)){
            $this->_icon(__METHOD__,$params['icoclass']);
        }
        
        if(array_key_exists('tooltip', $params)){
            $this->_tooltip(__METHOD__,$params['tooltip']);
        }
        
        $this->_name('import',$params['name']);
        $this->_image('import', $params['image']);
    }
    
    public function export($params){
        
        $this->_tools_used['export']['path'] = SITE_PATH.$params['path'];
        
        $this->_name('export',$params['name']);
        $this->_image('export', $params['image']);
        
        if(array_key_exists('icoclass', $params)){
            $this->_icon(__METHOD__,$params['icoclass']);
        }
        
        if(array_key_exists('tooltip', $params)){
            $this->_tooltip(__METHOD__,$params['tooltip']);
        }
        
        $settings = [
            'id'=>'export'.$this->_rand.'Modal',
            'role' => 'dialog',
            'title' => 'Confirm Export Settings',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Export' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary'
                ]
            ]
        ];
        
        $exportform = '<table>'
                . '<tr>
                        <td width="50%">
                            <p style="font-size:small">Please enter your exported filename: &nbsp;</p>
                        </td>
                        <td style="padding: 12px">
                            <input name="filename" type="text" value="" />
                        </td>
                      </tr>'
                . '</table>'
                . '<table class="table table-hover">'
                    . '<tbody>
                      <tr>
                        <td colspan="2">
                            <p style="font-size:small">Please select your desired export settings.</p>
                        </td>
                      </tr>
                        <tr>
                        <td width="10%"><input name="pages" type="radio" id="export_current_page_details" value="" checked="checked" /></td>
                        <td>Export Current Page <span id="export_current_page"></span></td>
                      </tr>
                      <tr>
                        <td width="10%"><input name="pages" type="radio" value="all_pages" /></td>
                        <td>All Pages</td>
                      </tr>
                      <tr>
                        <td colspan="2">
                            <span style="font-size:small">Select format of Export Document</span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">
                            <label class="radio-inline">
                                <input type="radio" name="format" value="excel" checked>MS Excel
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="format" value="csv">CSV
                            </label>
                        </td>
                      </tr>
                    </tbody>'
                . '</table>'
                . '<p style="font-size:small; text-align: right">Press <strong>ESC key</strong> to close.</p>';
        
        $this->_tools_used['export']['resource'] = 
                '<form action="'.$this->_tools_used['export']['path'].'" method="post" target="_blank">'
                . '<input type="hidden" name="export" value="export" >'
                    . Views\Overlays::Modal($settings, $exportform)
                .'</form>';  
    }
    
    public function printer($params){
        
        $this->_tools_used['printer']['path'] = SITE_PATH.$params['path'];
        
        $this->_name('printer',$params['name']);
        $this->_image('printer', $params['image']);
        
        if(array_key_exists('icoclass', $params)){
            $this->_icon(__METHOD__,$params['icoclass']);
        }
        
        if(array_key_exists('tooltip', $params)){
            $this->_tooltip(__METHOD__,$params['tooltip']);
        }
        
        $settings = [
            'id'=>'print'.$this->_rand.'Modal',
            'role' => 'dialog',
            'title' => 'Confirm Print Settings',
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Print' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary'
                ]
            ]
        ];
        
        $printform = '<p style="font-size:small">Please select your desired print settings.</p>'
                . '<table class="table table-striped table-hover">'
                . '<tbody>
                      <tr>
                        <td width="10%"><input name="pages" type="radio" id="printer_current_page_details" value="" checked="checked" /></td>
                        <td>Print Current Page <span id="print_current_page"></span></td>
                      </tr>
                      <tr>
                        <td width="10%"><input name="pages" type="radio" value="all_pages" /></td>
                        <td>All Pages</td>
                      </tr>
                    </tbody>'
                . '</table>'
                . '<p style="font-size:small; text-align: right">Press <strong>ESC key</strong> to close.</p>';
        
        $this->_tools_used['printer']['resource'] = 
                '<form action="'.$this->_tools_used['printer']['path'].'" method="post" target="_blank">'
                . '<input type="hidden" name="printer" value="printer">'
                    . Views\Overlays::Modal($settings, $printform)
                .'</form>';  
    }
    
    public function search($params){
        
        $this->_name('search',$params['name']);
        $this->_image('search', $params['image']);
        
        if(array_key_exists('icoclass', $params)){
            $this->_icon(__METHOD__,$params['icoclass']);
        }
        
        if(array_key_exists('tooltip', $params)){
            $this->_tooltip(__METHOD__,$params['tooltip']);
        }
        
        $this->_tools_used['search']['resource'] = '';
        
        $settings = [
            'id'=>'search'.$this->_rand.'Modal',
            'role' => 'dialog',
            'title' => $params['title'],
            'buttons' => [
                'Cancel' => [
                    'class' => 'btn btn-default',
                    'data-dismiss' => 'modal'
                ],
                'Search' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'search_button'
                ]
            ]
        ];
        
        //add the search hidden field
        $params['form']['method'] = 'post';
        $params['form']['controls']['search'] = ['hidden','search','search'];
        
        $sform = Generate::Form('searchform', $params['form']);
        $searchform = $sform->render('', TRUE);
        
        $this->_tools_used['search']['resource'] = Views\Overlays::Modal($settings, $searchform);
    }
    
    public function delete($params){
        
        $delete_path = SITE_PATH.$params['path'];        
        $this->_tools_used['delete']['path'] = $delete_path;
        
        $this->_name('delete',$params['name']);
        $this->_image('delete', $params['image']);
        
        if(array_key_exists('icoclass', $params)){
            $this->_icon(__METHOD__,$params['icoclass']);
        }
        
        if(array_key_exists('tooltip', $params)){
            $this->_tooltip(__METHOD__,$params['tooltip']);
        }
        
        //set confirmation 
        $this->_confirm['delete'] = true;
        $this->_confirmform('delete');
    }
    
    /**
     * Creates all the toolbar sections from the sent schematic
     * 
     */
    public function create(){
        
        $this->images_path = $this->config['images_path'];        
        $tools = array_keys($this->config['tools']);
        
        //add list of tools to process later
        $toolresource['tools'] = $this->config['tools'];
        
        foreach($tools as $tool){
            
            $tool_params = $this->config['tools'][$tool];
            
            //purify tools
            $tool = strtolower(str_replace(' ', '_', $tool));            
            $this->$tool($tool_params);
        }
        
        $toolbar .= '<div class="toolbar '.$this->_settings['toolbar_class'].'">';
        $used_tools = array_keys($this->_tools_used);
        
        foreach($used_tools as $used_tool){
            
            $name = $this->_tools_used[ $used_tool ][ 'name' ];
            
            if($used_tool == 'delete'){
                
                $path = '#';
                $additions = 'data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#delete'.$this->_rand.'Modal" ';
                $ajaxclass = 'ajaxextensions';
                
                if(array_key_exists('tooltip', $this->_tools_used[$used_tool])){                
                    $deladd = Notifications::tooltip($this->_tools_used[$used_tool]['tooltip']);
                }
                else{
                    $deladd = 'title="'.$name.'"';
                }
            }
            elseif($used_tool == 'add'){
                
                $path = $this->_tools_used['add']['path'];
                $additions = $this->addsettings;
            }
            elseif($used_tool == 'printer'){
                
                $path = '#';
                $additions = 'data-toggle="modal" data-backdrop="false" data-target="#print'.$this->_rand.'Modal"';
            }
            elseif($used_tool == 'import'){
                
                $path = '#';
                $additions = 'data-toggle="modal" data-backdrop="false" data-target="#import'.$this->_rand.'Modal"';
            }
            elseif($used_tool == 'export'){
                
                $path = '#';
                $additions = 'data-toggle="modal" data-backdrop="false" data-target="#export'.$this->_rand.'Modal"';
            }
            elseif($used_tool == 'search'){
                
                $path = '#';
                $additions = 'data-toggle="modal" data-backdrop="false" data-target="#search'.$this->_rand.'Modal"';
            }
            else{
                
                if(array_key_exists('addition',$this->_tools_used[$used_tool])){
                    $path = '#';
                    $additions = $this->_tools_used[$used_tool]['addition'];
                    $ajaxclass = 'ajaxextensions';
                }
                else{
                    $path = $this->_tools_used[$used_tool][ 'path' ];
                    $additions = '';
                }
            }            
            
            if(array_key_exists('tooltip', $this->_tools_used[$used_tool])){   
                $additions .= Notifications::tooltip($this->_tools_used[$used_tool]['tooltip']).' ';
            }
            
            $toolbar .= '<div class="toolcell '.$this->_settings['cell_class'].'" id="'.$used_tool.'">';            
            
            if(isset($this->_settings) && strtolower($this->_settings['wrap_with']) == 'table'){
                
                $toolbar .= '<table width="80" border="0">
                              <tr>
                                <td height="50" class="toolicon">
                                    <a href="'.$path.'" id="'.strtolower(str_replace(' ','_',$name)).'" class="'.strtolower(str_replace(' ','_',$name)).' toolsbutton '.$ajaxclass.'" '.$additions.'>';
                                    
                                    if(!array_key_exists('icon', $this->_tools_used[$used_tool])){
                                        $toolbar .= '<img src="'.$this->_tools_used[ $used_tool ][ 'image' ].'" title="'.$name.'" '.$deladd.'/>';
                                    }
                                    elseif(array_key_exists('icon', $this->_tools_used[$used_tool])){
                                        $toolbar .= '<i class="'.$this->_tools_used[$used_tool]['icon'].'"></i>';
                                    }
                                        
                        $toolbar .= '</a>
                                </td>
                              </tr>';

                                if(!isset($this->_settings['add_tool_names']) || $this->_settings['add_tool_names'] == TRUE){                
                                    $toolbar .= '<tr>
                                                <td height="25" class="toolname">'
                                                    . '<a href="'.$path.'" id="'.strtolower(str_replace(' ','_',$name)).'" class="toolsbutton '.$ajaxclass.'" '.$additions.'>'
                                                    . '<span>'.$this->_tools_used[ $used_tool ][ 'name' ].'</span>'
                                                    . '</a>'
                                            . '</td>
                                            </tr>';
                                }

                $toolbar .= '</table>';
            }
            elseif(strtolower($this->_settings['wrap_with']) == 'div'){
                
                $toolbar .= '<div class="toolicon">'
                        . '<a href="'.$path.'" id="'.strtolower(str_replace(' ','_',$name)).'" class="'.strtolower(str_replace(' ','_',$name)).' toolsbutton '.$ajaxclass.'" '.$additions.'>';
                
                            if(!array_key_exists('icon', $this->_tools_used[$used_tool])){
                                $toolbar .= '<img src="'.$this->_tools_used[ $used_tool ][ 'image' ].'" title="'.$name.'" '.$deladd.'/>';
                            }
                            elseif(array_key_exists('icon', $this->_tools_used[$used_tool])){
                                $toolbar .= '<i class="'.$this->_tools_used[$used_tool]['icon'].'"></i>';
                            }
                            
                $toolbar .= '</a>
                            </div>';
                
                if(!isset($this->_settings['add_tool_names']) || $this->_settings['add_tool_names'] == TRUE){ 
                    
                    $toolbar .= '<div class="toolname">'
                                . '<a href="'.$path.'" id="'.strtolower(str_replace(' ','_',$name)).'" class="toolsbutton '.$ajaxclass.'" '.$additions.'>'
                                . '<span>'.$this->_tools_used[ $used_tool ][ 'name' ].'</span>'
                                . '</a>'
                                . '</div>';
                    
                }
            }
            
            $toolbar .= '</div>';
            
            if(isset($this->_tools_used[ $used_tool ]['resource'])){
                
                $resource_tool[$used_tool] = $this->_tools_used[ $used_tool ]['resource'];
            }
        }
        
        $toolbar .= '</div>';
        
        //build the resource section
        if(count($resource_tool) >= '1'){
            
            //$toolbar .= '<div class="toolbar_resouce" style="display: none;">';
            $resource_keys = array_keys($resource_tool);
            
            foreach($resource_keys as $resource){
                
                $toolbar .= '<div id="'.$resource.'" class="resource">';
                
                //load the resource
                $toolbar .= $resource_tool[$resource];
                
                $toolbar .= '</div>';
            }
            
            if(in_array('import', $resource_keys)){
                
                $id = $this->_tools_used['import']['id'];
                $toolbar .= '<script src="'.RELATIVE_APP_PATH.'/resources/bootstrap/extensions/fileinput/fileinput.js"></script>'
                        . '<link href="'.RELATIVE_APP_PATH.'/resources/bootstrap/extensions/fileinput/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />';
            }
            
            $toolbar .= HTML::script('$(function () {'
                    . '//this is to prevent caching of remote bootstrap modals
                        $("a[data-toggle=modal]").click(function(ev) {
                            ev.preventDefault();
                            var target = $(this).attr("href");
                            
                            if(target != "#"){
                                var idtarget = $(this).attr("data-target");

                                // load the url and show modal on success
                                $(idtarget+" .modal-content").load(target, function() { 
                                    $("#"+idtarget).modal("show"); 
                                });
                            }
                        });
                        
                        })','script',TRUE);
        }     
        
        $toolresource['bar'] = $toolbar;
        $toolresource['key'] = $this->_rand;
        
        return $toolresource;
    }
}