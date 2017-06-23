<?php
namespace Jenga\App\Html;

use Jenga\App\Views;
use Jenga\App\Core\App;
use Jenga\App\Views\HTML;
use Jenga\App\Html\Tools;
use Jenga\App\Views\View;
use Jenga\App\Helpers\Help;
use Jenga\App\Request\Session;
use Jenga\App\Views\Notifications;
use Jenga\App\Request\Facade\Sanitize;

class Table extends View {
    
    public $data;
    public $table;
    public $name;
    public $toolbar = '';
    
    private $_schematic;
    
    private $_columns;
    private $_rows;
    
    private $_addrows = array();
    private $_rowcount;
    private $_pages;
    private $_addrowcount = 1;
    private $_tools;
    private $_instancekey = 0;
    private $_attachtools = true;
    private $_checkbox = false;
    private $_contextmenu = false;
    private $_contextmenuvars = [];
    private $_contextmenuscript;
    
    public function __construct($name, $schematic, Sanitize $cleaner) {
        
        //assign the table name
        $this->name = $name;
        
        //assign the schematic
        $this->_schematic = $schematic;
        
        //assign the sanitizer
        $this->cleaner = $cleaner;
    }
    
    private function _init(){ 
        
        if($this->_schematic['format'] == 'dataTable' || !isset($this->_schematic['format'])){
            
            //register dataTables caching mechanism into HTML processing queue
            HTML::register('dataTables');
            
            $this->table .= "<script type=\"text/javascript\">
                    $(document).ready( function () {";
            
            if(!isset($this->_schematic['rowreorder'])){
                $tableinit .= "
                       var ".$this->name."_table = $('#".$this->name."').DataTable({"
                    . "stateSave: true,";
            }
            else{
                $tableinit .= "$('#".$this->name."').dataTable({"
                    . "stateSave: false,";
                
                $tableinit .= '"columnDefs": [
                                { "orderable": true, targets: 1 }
                              ],';
            }
            
            //disable columns
            if(isset($this->_schematic['ordering']['disable'])){
                
                $discol = $this->_schematic['ordering']['disable'];
                
                $tableinit .= '"columnDefs": [
                                { "orderable": false, "targets": '.$discol.' }
                              ],';
                
                unset($this->_schematic['ordering']['disable']);
            }        
            
            //dom
            if(isset($this->_schematic['dom'])){                
                $tableinit .= '"dom":'."'".$this->_schematic['dom']."'";
            }
            
            //page length
            if(isset($this->_schematic['rows_per_page'])){                
                $tableinit .= ', "lengthChange": true, "pageLength":'.$this->_schematic['rows_per_page'];
            }
            
            //ordering
            if(isset($this->_schematic['ordering'])){
                
                $col = array_keys($this->_schematic['ordering']);
                $colpos = array_search($col[0], $this->_schematic['columns']);
                
                $tableinit .= ', "order": [['.$colpos.', "'.$this->_schematic['ordering'][$col[0]].'"]]';
            }
            
            $tableinit .= "});";
            
            //add the table initialisation
            $this->table .= $tableinit;
            
            //build shorcut menus
            if($this->_contextmenu){
                
                $this->table .= '$("#'.$this->name.' .dropdown").shortcutMenu({
                                        menuSelector: "#shortcutMenu-",
                                        trigger: "'.$this->_contextmenuvars['trigger'].'",
                                        tablename: "'.$this->name.'"
                                });';
            }
            
            $rowcheck = $this->_getToolsRequiringRowData($this->_tools);
            if($rowcheck['present'] == TRUE){                
                
              //the delete section
              $this->table .= "function updateTextArea(fxn) {   
                                 var allvalls = [];
                                 var dchecked = ".$this->name."_table.$('.chkitem:checked', {'page': 'all'});
                                 
                                 dchecked.each(function() {                                   
                                    if($(this).is(':checked')){                                    
                                        allvalls.push($(this).val());
                                    }
                                 });
                                 
                                 $.ajax({
                                        url: '".SITE_PATH."/ajax/app/processrows', 
                                        type: 'POST', 
                                        data: {
                                            rows : allvalls, 
                                            selectfxn: fxn
                                        },
                                        success: function(response){";
              
                                            if(array_key_exists('confirm',$rowcheck)){
                                                foreach($rowcheck['confirm'] as $key=>$boolean){
                                                    if($boolean==true){
                                                        $this->table .= "$('#".$key.$this->_instancekey."Modal div.modal-body').html(response);";
                                                    }
                                                }
                                            }
                                            else{
                                                $this->table .= "$('#'+fxn+'".$this->_instancekey."Modal div.modal-body').html(response);";
                                            }
                                            
                                            $this->table .= "
                                        }
                                });
                              }
                              
                              $(function() {
                                $('.checkall').click(function(event){                        
                                    if(this.checked){
                                        $('.chkitem').each(function() { 
                                            this.checked = true;  
                                        });
                                    }
                                    else{
                                        //uncheck everything
                                        $('.chkitem').each(function() { 
                                            this.checked = false;               
                                        });
                                    }
                                });

                                $('.toolcell').on('click','.ajaxextensions',function(){
                                    var functionid = $(this).attr('id');
                                    updateTextArea(functionid);
                                });                                
                              });";
            }
            
            //the print sections
            if(isset($this->_tools) && array_key_exists('printer', $this->_tools)){
                
                $this->table .= "var info_one = ".$this->name."_table.page.info();"
                        . "var order_one = ".$this->name."_table.order();"
                        . "$('#print_current_page').html(' ('+(info_one.page+1)+' of '+info_one.pages+') pages.');"
                        . "$('#printer_current_page_details')"
                            . ".val('start-'+info_one.start+',length-'+info_one.length+',end-'+info_one.end+',order-'+order_one[0]);"
                        
                        . $this->name."_table.on('page.dt', function(){"
                        
                            . "var info = ".$this->name."_table.page.info();"
                            . "var order = ".$this->name."_table.order();"
                            . "$('#print_current_page').html(' ('+(info.page+1)+' of '+info.pages+') pages.');"
                            . "$('#printer_current_page_details')"
                                . ".val('start-'+info.start+',length-'+info.length+',end-'+info.end+',order-'+order[0]);"
                        
                        . "});";
            }
            
            //the export sections
            if(isset($this->_tools) && array_key_exists('export', $this->_tools)){
                
                $this->table .= "var info_one = ".$this->name."_table.page.info();"
                        . "var order_one = ".$this->name."_table.order();"
                        . "$('#export_current_page').html(' ('+(info_one.page+1)+' of '+info_one.pages+') pages.');"
                        . "$('#export_current_page_details')"
                            . ".val('start-'+info_one.start+',length-'+info_one.length+',end-'+info_one.end+',order-'+order_one[0]);"
                        
                        . $this->name."_table.on('page.dt', function(){"
                        
                            . "var info = ".$this->name."_table.page.info();"
                            . "var order = ".$this->name."_table.order();"
                            . "$('#export_current_page').html(' ('+(info.page+1)+' of '+info.pages+') pages.');"
                            . "$('#export_current_page_details').val('start-'+info.start+',length-'+info.length+',end-'+info.end+',order-'+order[0]);"
                        
                        . "});";
            }
            
            if(isset($this->_schematic['rowreorder'])){
                
                if(substr($this->table, -1) == ';'){
                    $table = rtrim($this->table, ';');
                    $this->table = $table;
                }
                
                //register the row reorder resourcee
                HTML::register('rowreorder');
                $this->table .= '
                          .rowReordering({';
                
                if(isset($this->_schematic['rowreorder']['url'])){                    
                    $this->table .= 'sURL:"'.$this->_schematic['rowreorder']['url'].'"';
                }
                
                if(isset($this->_schematic['rowreorder']['requesttype'])){                    
                    $this->table .= ',sRequestType:"'.$this->_schematic['rowreorder']['requesttype'].'"';
                }
                
                if(isset($this->_schematic['rowreorder']['debug'])
                        &&$this->_schematic['rowreorder']['debug']==true){ 
                    
                    $this->table .= ',fnAlert: function(message) { 
                                              alert(message);
                                       }';
                }
                
                $this->table .= '});';
            }
            
            //the page ordering section
            if(isset($this->_tools) && 
                    (array_key_exists('export', $this->_tools) || 
                    array_key_exists('printer', $this->_tools))){
                
                $this->table .= $this->name."_table.on('order.dt', function(){"
                        
                            . "var info = ".$this->name."_table.page.info();"
                            . "var order = ".$this->name."_table.order();";
                
                if(array_key_exists('export', $this->_tools)){
                    
                    $this->table .= "$('#export_current_page').html(' ('+(info.page+1)+' of '+info.pages+') pages.');"
                            . "$('#export_current_page_details')"
                            . ".val('start-'+info.start+',length-'+info.length+',end-'+info.end+',order-'+order[0]);";
                }
                
                if(array_key_exists('printer', $this->_tools)){
                    
                    $this->table .= "$('#print_current_page').html(' ('+(info.page+1)+' of '+info.pages+') pages.');"
                            . "$('#printer_current_page_details')"
                            . ".val('start-'+info.start+',length-'+info.length+',end-'+info.end+',order-'+order[0]);";
                }
                
                $this->table .= "});";
            }
            
            //the search form section
            if(isset($this->_tools) && array_key_exists('search', $this->_tools)){
                
                $this->table .= '$("#search_button").click(function() {
                                       $("#searchform").submit();
                                   });';
            }
            
            $this->table .= "});
              </script>";
        }
    }
    
    /**
     * Builds the table
     * 
     * @param type $schematic
     */
    public function buildTable(){
        
        $this->_rowcount = $this->_schematic['row_count'];
        
        if(isset($this->_schematic['rows_per_page'])){            
            $this->_pages = '';
        }
        
        //build the toolbar if set
        if($this->toolbar != '' && $this->_attachtools === true){            
            $this->table .= $this->toolbar;
        }
        
        if(isset($this->_schematic['table'])){
            
            if(array_key_exists('id', $this->_schematic['table'])){                
                unset($this->_schematic['table']['id']);
            }
            
            $attributes = $this->_processAttributes($this->_schematic['table']);
            
            if($this->toolbar != ''){            
                $this->table .= '<table id="'.$this->name.'" '.$attributes.'>';
            }
            else{                
                $this->table .= '<table id="'.$this->name.'" '.$attributes.'>';
            }
        }
        else{            
            $this->table .= '<table id="'.$this->name.'">';
        }
        
        if(isset($this->_schematic['columns'])){            
            $this->_columns = $this->buildColumns();
        }        
        
        if(isset($this->_schematic['row_variables'])){            
            $this->_rows = $this->buildRows();
        }
        
        //compile all the rows and columns
        $this->_columns .= '</thead>';
        $this->_rows .= '</tbody>';
        
        $this->table .= $this->_columns.  $this->_rows;
        $this->table .= '</table>';
    }
    
    /**
     * Gets the full count of all the rows
     * 
     * @return type
     */
    private function _getCount(){
        
        if(array_key_exists('object', $this->_schematic['row_source'])){
            
            $rowcount = count($this->_schematic['row_source']['object']);
        }
        elseif(array_key_exists('array', $this->_schematic['row_source'])){
            
            $rowcount = count($this->_schematic['row_source']['array']);
        } 
        
        return $rowcount;
    }
    
    /**
     * Create a toolbar based on the tools and settings assign in the tools schematic
     * 
     * @param type $tools
     */
    public function buildTools($tools, $return = false){
        
        if($this->_getCount() >= '1' && $this->_schematic['format'] != 'print'){
            
            $toolbar = new Tools($tools);
            $build = $toolbar->create();

            $this->toolbar = $build['bar'];
            $this->_tools = $build['tools'];
            $this->_instancekey = $build['key'];
            
            $this->_processTools($this->_tools);
        }
        else{
            
            if(isset($tools['tools']['add']) || isset($tools['tools']['import'])){
                
                $basictools['images_path'] = $tools['images_path'];
                $basictools['tools']['add'] = $tools['tools']['add'];
                
                if(array_key_exists('import', $tools['tools'])){
                    $basictools['tools']['import'] = $tools['tools']['import'];
                }
                
                if(isset($tools['settings'])){
                    $basictools['settings'] = $tools['settings'];
                }
                
                $toolbar = new Tools($basictools);
                $build = $toolbar->create();

                $this->toolbar = $build['bar'];
                $this->_tools = $build['tools'];
                
                if(!$return)
                    $this->table .= $this->toolbar;
            }
        }
        
        //return tools
        if($return){
            
            $this->_attachtools = FALSE;
            return $build['bar'];
        }
        
        return $this;
    }
    
    /**
     * Add a shortcut menu to apply to each of the table rows
     */
    public function buildShortcutMenu($handle, $trigger = 'click', $list = [], $align = 'bottom'){
        
        $this->_contextmenu = TRUE;
        
        //register context menu
        if(App::$shell->has('current_page')){
            
            $resource = App::$shell->get('current_page');
            
            if(!in_array('datatables_shortcut_menu', $resource)){
                //HTML::register('<script src="'. RELATIVE_APP_PATH .'/html/facade/DataTables/plugins/shortcutmenu/position-calculator.min.js"></script>');
                HTML::register('<script src="'. RELATIVE_APP_PATH .'/html/facade/DataTables/plugins/shortcutmenu/shortcutmenu.js"></script>');                
            }
        }
        
        //assign the menu vars
        $this->_contextmenuvars['handle'] = $handle;
        $this->_contextmenuvars['trigger'] = $trigger;
        $this->_contextmenuvars['list'] = $list;
        $this->_contextmenuvars['align'] = $align;
        
        return $this;
    }
    
    /**
     * Processes the added tools and adds the additional functionality to the table
     * 
     * @param array $tools
     */
    private function _processTools(array $tools){
        
        $rowtools = $this->_getToolsRequiringRowData($tools);
        
        if($rowtools['present'] == TRUE){
            
            //add additional checkbox column to table
            array_unshift($this->_schematic['columns'], '<input type="checkbox" class="checkall" name="item">');
            
            //add row data for each checkbox per row
            $info = json_decode($rowtools['using_info'],true);
            $usinginfo = $this->_processCheckboxData($info);
            
            array_unshift($this->_schematic['row_variables'], '{{<input type="checkbox" class="chkitem" value=\''.$usinginfo.'\' name="item[]">}}');
        }
    }
    
    /**
     * Processes the info to be inserted into the initial checkbox column
     * 
     * @param type $info
     */
    private function _processCheckboxData($info){ 
        
        foreach($info as $key => $cell){
            
            if($key != 'delete'){

                $using[] = ['function' => $key, 'params'=> join(',', $cell)];
            }
            elseif($key == 'delete'){
                
                $ckeys = array_keys($cell);
                $using[] = ['function' => $key, 'params'=> $ckeys[0].','.join(',', $cell)];
            }
        }
        
        return json_encode($using);
    }
    
    /**
     * Get the assigned tools to see if any require row information to work
     * 
     * @param type $tools
     */
    private function _getToolsRequiringRowData($tools){
        
        if(!is_null($tools)){
            
            foreach($tools as $key => $tool){
                
                $key = strtolower(str_replace(' ', '_', $key));
                $using_present = array_search('using', array_keys($tool));

                if($using_present != FALSE){

                    $rowtools['present'] = TRUE;
                    $using[$key] = $tool['using'];
                }
                else{

                    if(!isset($rowtools['present']))
                        $rowtools['present'] = FALSE;
                }

                if(array_search('confirm', array_keys($tool))){

                    $confirm[$key] = $tool['confirm'];
                }
            }

            //encode the sent row variables into json to allow easy sending to ajax processor
            $rowtools['using_info'] = json_encode($using);
            
            if(array_search('confirm', array_keys($tool)))
                $rowtools['confirm'] = $confirm;

            return $rowtools;
        }
    }
    
    /**
     * Builds the table columns based on schematic provided
     * 
     * @param type $schematic
     * @return string Fully rendered column
     */
    public function buildColumns(){
        
        if(isset($this->_schematic['column_attributes']['header_row'])){
            
            $properties = $this->_processAttributes($this->_schematic['column_attributes']['header_row']);
            unset($this->_schematic['column_attributes']['header_row']);
        }
        
        $columns = '<thead>'
                . '<tr '.$properties.'>';
        
        if(isset($this->_schematic['columns'])){
            
            $colcount = 1;
            foreach($this->_schematic['columns'] as $column){
                
                if(isset($this->_schematic['column_attributes'])){
                    
                    $spcolumn = array_search($colcount, array_keys($this->_schematic['column_attributes']));
                    
                    if($spcolumn != FALSE){
                        $position = $colcount;
                    }
                    else{
                        $position = null;
                    }
                    
                    $attr = $this->_processColumnAttributes($column,$position);
                }
                    
                $columns .= $this->_processColumn($column, $attr);
                $colcount++;
            }
        }
        
        $columns .= '</tr>'
                . '</thead>';
        
        return $columns;
    }
    
    /**
     * Builds the table rows based on the schematic provided
     * 
     * @param type $schematic
     * @return string Fully rendered rows
     */
    public function buildRows(){
        
        if(!isset($this->_rowcount))
            App::critical_error ('The \'row_count\' parameter must be specified');
        
        elseif(!isset($this->_schematic['row_variables']))
            App::critical_error ('The \'row_variables\' parameter must be specified');
        
        $rows .= '<tbody>';
        $rowcount = $this->_getCount();  
        
        for($count=0; $count<=($rowcount-1); $count++){ 
            
            $attr = $this->_processRowAttributes($count);
            
            $rowid = $count+1;
            
            $rows .= '<tr id="'.(isset($this->_schematic['rowreorder']['id']) 
                    ?  $this->_getVar($this->_schematic['rowreorder']['id'], $count) 
                    : $rowid).'" '.$attr.'>';       
            
            $rows .= $this->_processCells($count);            
            $rows .= '</tr>';
        }
        
        if(count($this->_addrows) != 0){            
            $rows .= $this->_additionalRow($this->_addrows);
        }
        
        return $rows;
    }
    
    /**
     * Limit the rows source based on parameters sent
     * 
     * @param type $start
     * @param type $length
     * @param type $end
     * 
     * @return mixed $this->_schematic['row_source']
     */
    public function limit($start, $length, $end=''){
        
        if(array_key_exists('object',$this->_schematic['row_source'])){            
            $source = $this->_schematic['row_source']['object'];
        }
        elseif(array_key_exists('array', $this->_schematic['row_source'])){            
            $source = $this->_schematic['row_source']['array'];
        }
        
        array_splice($source, $start, $length);
        
        if(array_key_exists('object',$this->_schematic['row_source'])){            
            $this->_schematic['row_source']['object'] = $source;
        }
        elseif(array_key_exists('array', $this->_schematic['row_source'])){            
            $this->_schematic['row_source']['array'] = $source;
        }
    }
    
    /**
     * Checks if sent name is a html, static name or has a variable or metavariable
     * 
     * @param type $string
     */
    private function _isEvalString($string){
        
        if($this->_isHtml($string)){            
            return 'html';
        }
        elseif(strstr($string, '{{')){            
            return 'metavar';
        }
        elseif(strstr($string, '{')){            
            return 'var';
        }
        else{            
            return 'static';
        }
    }
    
    private function _processColumn($column, $attr){
        
        $eval = $this->_isEvalString($column);
        $trcolumn = '<th '.$attr.'>';
        
        switch ($eval) {
            
            case 'html':
                
                $trcolumn .= $column;
                break;

            case 'static':
                
                $trcolumn .= $column;
                break;
            
            case 'var':
                
                $trcolumn .= $this->_processVar($column);
                break;
            
            case 'metavar':
                
                $trcolumn .= $this->_processMetaVar($column);
                break;
        }
        
        $trcolumn .= '</th>';
        
        return $trcolumn;
    }
    
    /**
     * Process all the relevant data for each cell and returns each cell's 
     * actual value
     * 
     * @param type $schematic
     * @param type $rowcount
     * @return string
     */
    private function _processCells($rowcount){
        
        $source = $this->_schematic['row_source'];        
        $colcount = 1;
        
        foreach($this->_schematic['row_variables'] as $cell){
            
            if($colcount == 1){
                $check = $this->_getToolsRequiringRowData($this->_tools);
                
                if($check['present']==true){
                    $this->_checkbox = true;
                }
            }
            
            $eval = $this->_isEvalString($cell);
            $attr = $this->_processCellAttributes($colcount ,$rowcount);
            
            //reduce row count due to separate counting of column coordinates and array positions
            if($rowcount === 0 && $colcount === 0){                
                $rowcount = $rowcount-1;
            }
            
            $trcell .= '<td '.$attr.'>';
        
            switch ($eval) {

                case 'html':
                case 'static':

                    $trcell .= $cell;
                    break;

                case 'var':

                    $pcell = $this->_processVar($cell);
                    
                    if(array_key_exists('object', $source)){
                       
                        $object = $this->_schematic['row_source']['object']; 
                        $rowcell = $object[$rowcount]->$pcell;  
                    }
                    elseif(array_key_exists('array', $source)){
                        
                        $array = $this->_schematic['row_source']['array'];
                        $rowcell = $array[$rowcount][$pcell];
                    }
                    
                    //replace the ID for the context menu element
                    if($this->_contextmenu){
                        
                        $handle = $this->_processVar($this->_contextmenuvars['handle']);
                        
                        if($pcell == $handle){
                            
                            $actions = $this->_returnShortcutMenuActions($this->_contextmenuvars['list'], $rowcount, $this->_schematic['row_source']);
                        
                            $trcell .= '<div class="dropdown" data-toggle="dropdown" style="width: 100%; text-align:center">';
                            $trcell .= $rowcell.'<span class="caret"></span>';
                            $trcell .= $actions;
                            $trcell .= '</div>';
                        }
                        else{
                            $trcell .= $rowcell;
                        }
                    }
                    else{
                        $trcell .= $rowcell;
                    }
                    
                    break;

                case 'metavar':
                    
                    if(array_key_exists('object', $source)){
                        
                        $datasource = $this->_schematic['row_source']['object'];
                        $type = 'object';
                    }
                    elseif(array_key_exists('array', $source)){
                        
                        $datasource = $this->_schematic['row_source']['array'];
                        $type = 'array';
                    }
                    
                    $trcell .= $this->_processMetaVar($cell, $datasource, $type, $rowcount);
                    break;
            }            
            
            $trcell .= '</td>';
            
            //set checkbox to false for the initial checkbox row
            if($colcount == 1){
                $this->_checkbox = false;
            }
            
            $colcount++;
        }
        
        return $trcell;
    }
    
    /**
     * Return shortcut menu actions
     * @param type $list
     */
    private function _returnShortcutMenuActions($list, $rowcount, $source){
        
        $actions = '<ul id="shortcutMenu-'.$this->name.($rowcount+1).'" class="dropdown-menu" role="shortcutmenu" >';
        
        foreach($list as $item){
            
            if(!Help::is_closure($item)){
                
                $var = $this->_processVar($item);

                if(is_string($var)){

                    if(array_key_exists('object', $source)){

                        $object = $this->_schematic['row_source']['object']; 
                        $cellvar = $object[$rowcount]->$var;  
                    }
                    elseif(array_key_exists('array', $source)){

                        $array = $this->_schematic['row_source']['array'];
                        $cellvar = $array[$rowcount][$var];
                    }

                    $completeitem .= str_replace('{'.$var.'}', $cellvar, $item);
                }
                //bypass the var
                else{
                    $completeitem .= $item;
                }
            }
            elseif(Help::is_closure($item)){
                
                //use reflection to get the parameters for each row
                $eval = new \ReflectionFunction($item);
                $params = $eval->getParameters();
                
                if(count($params) > 0){
                    
                    $object = $this->_schematic['row_source']['object']; 
                    foreach($params as $param){
                        
                        $name = $param->name;
                        $parameters[$name] = $object[$rowcount]->{$param->name};
                    }
                    
                    $completeitem .= call_user_func_array($item, $parameters);
                    unset($parameters);
                }
            }
        }
        
        $actions .= $completeitem;
        $actions .= '</ul>';
        
        return $actions;
    }
    
    private function _processCellAttributes($colcount ,$rowcount){
        
        if(isset($this->_schematic['cell_attributes'])){
            
            if(isset($this->_schematic['cell_attributes']['default'])){                
                $attributes = $this->_processAttributes($this->_schematic['cell_attributes']['default']);
            }
             
             $cood = $colcount.','.$rowcount;
             
             if(isset($this->_schematic['cell_attributes'][$cood])){                 
                 $attributes .= $this->_processAttributes($this->_schematic['cell_attributes'][$cood]);
             }
        }
        
        return $attributes;
    }
    
    /**
     * Gets variable from row source
     * @param type $var
     */
    private function _getVar($var, $rowcount){
        
        $source = $this->_schematic['row_source'];  
        if(array_key_exists('object', $source)){
                       
            $object = $this->_schematic['row_source']['object']; 
            $cellvar = $object[$rowcount]->{$this->_processVar($var)};  
        }
        elseif(array_key_exists('array', $source)){

            $array = $this->_schematic['row_source']['array'];
            $cellvar = $array[$rowcount][$this->_processVar($var)];
        }
        
        return $cellvar;
    }
    
    /**
     * Processes the {variable} and inserts the appropriate variable from the controller
     * 
     * @param type $data
     * @return type
     */
    private function _processVar($data){        
        return HTML::findInTags('{', '}', $data);
    }
    
    /**
     * Processes the {{meta-variable}} and inserts the appropriate variables from the controller
     * 
     * @param type $data
     * @return mixed
     */
    private function _processMetaVar($data, $source = NULL, $type = 'text', $rowcount = 0){
        
        if($this->_checkbox == false){
            
            $meta = HTML::findInTags('{{', '}}', $data);   
            $metavars = HTML::findInTags('{', '}', $meta, TRUE);
        }
        else{
            
            $meta = HTML::findInTags('{{', '}}', $data);
            $dom = new \DOMDocument();            
            
            $dom->loadHTML($data);
            $inputs = $dom->getElementsByTagName('input');
            
            $metavars = [];
            foreach ($inputs as $input){                
                
                $rowsjson = json_decode($input->getAttribute('value'));
                
                foreach($rowsjson as $rowjson){
                    
                    if(count($metavars) == 0){
                        $metavars = HTML::findInTags('{', '}', $rowjson->params, TRUE);
                    }
                    else{
                        $metavars = array_merge ($metavars, HTML::findInTags('{', '}', $rowjson->params, TRUE));
                    }
                }
            }
        }
        
        //if condition accounts for plain HTML being sent
        if($metavars != FALSE){

            foreach($metavars as $var){
                $varsholder[] = '{'.$var.'}';

                if($type == 'object'){
                    $actualvars[] = $source[$rowcount]->$var;
                }
                elseif($type == 'array'){
                    $actualvars[] = $source[$rowcount][ $var ];
                }
            }
        }
        
        return str_replace($varsholder, $actualvars, $meta);
    }
    
    /**
     * Processes the attributes and returns a string 
     * of the properties to be inserted
     * 
     * @param type $properties
     * @return string $properties_string
     */
    private function _processAttributes($properties){
        
        foreach($properties as $attr => $value){
            
            $properties_string .= $attr.' = "'.$value.'" ';
        }
        
        return $properties_string;
    }
    
    /**
     * Process column attributes
     * 
     * @param type $column
     * @return string Column Attributes
     */
    private function _processColumnAttributes($column, $position = NULL){
        
        if(is_string($column) && isset($this->_schematic[ 'column_attributes'][  $column ])){
            
            $attributes = $this->_processAttributes($this->_schematic[ 'column_attributes' ][ $column ]);
        }
        elseif(!is_null($position)){
            
            //$colkeys = array_keys($this->_schematic[ 'column_attributes' ]);
            
            if(isset($this->_schematic[ 'column_attributes' ][ $position ])){
                
                $spcolumn = $this->_schematic[ 'column_attributes' ][ $position ];  
                $attributes = $this->_processAttributes($spcolumn);
            }
        }
        
        //process the default column attributes first
        if(isset($this->_schematic['column_attributes']['default'])){
            
            $attributes .= $this->_processAttributes($this->_schematic['column_attributes']['default']);
        }  
        
        return $attributes;
    }
    
    private function _processRowAttributes($count){
        
        if($count==1 
                && isset($this->_schematic['row_attributes']['first_row'])){

            $attr = $this->_processAttributes($this->_schematic['row_attributes']['first_row']);
        }
        elseif(isset($this->_schematic['row_attributes']['last_row'])){

            $attr = $this->_processAttributes($this->_schematic['row_attributes']['last_row']);
        }
        elseif($count%2== 0 
                && isset($this->_schematic['row_attributes']['even_row'])){

            $attr = $this->_processAttributes($this->_schematic['row_attributes']['even_row']);
        }
        elseif($count%2== 1 
                && isset($this->_schematic['row_attributes']['odd_row'])){

            $attr = $this->_processAttributes($this->_schematic['row_attributes']['odd_row']);
        }
        
        if(isset($this->_schematic['row_attributes']['default'])){
            
            $attr .= $this->_processAttributes($this->_schematic['row_attributes']['default']);
        }
        
        if(isset($this->_schematic['row_attributes'][$count])){
            
            $attr .= $this->_processAttributes($this->_schematic['row_attributes'][$count]);
        }
        
        return $attr;
    }
    
    /**
     * Add the row varisbles for each column
     * 
     * @param type $row_variables
     */
    public function addRow($row_variables){
        
        $this->_addrowcount ++;        
        $this->_addrows[$this->_addrowcount]['row_variables'] = $row_variables;        
        
        return $this;
    }
    
    /**
     * Add the row attributes for the additional row
     * 
     * @param type $row_attributes
     */
    public function withAttributes($row_attributes){
        
        $this->_addrows[$this->_addrowcount]['row_attributes'] = $row_attributes;
        
        return $this;
    }
    
    /**
     * Adds the row cell attributes
     * 
     * @param type $cell_attributes
     */
    public function withCellAttributes($cell_attributes){
        
        $columns = array_keys($cell_attributes);
        
        foreach($columns as $column){
            
            if(is_int($column)){
                $this->_addrows[$this->_addrowcount]['cell_attributes'] = $cell_attributes;
            }
            else{
                App::critical_error('The cell attribute \'column\' must be an integer, while using this \''.__METHOD__.'\' method');
            }
        }
        
        return $this;
    }
    
    /**
     * Process the additional row data
     * 
     * @param array $addrows
     * @return string
     */
    private function _additionalRow(array $addrows){
        
        $this->_addrowcount = $this->_rowcount;
        $count = $this->_addrowcount; 
        
        foreach($addrows as $addrow){

            $this->_schematic = $addrow;
            
            if(isset($this->_schematic['row_attributes'])){

                $attr = $this->_processRowAttributes($count);
            }

            //rearrange the cell attributes for processing
            if($this->_schematic['cell_attributes']){
                
                $cellattr = $this->_schematic['cell_attributes']; 
                $columns = array_keys($this->_schematic['cell_attributes']);
                
                //destroy cell attributes
                unset($this->_schematic['cell_attributes']);
                
                //recreate again
                foreach($columns as $column){
                    
                    $cood = $column.','.$this->_rowcount;
                    $this->_schematic['cell_attributes'][$cood] = $cellattr[$column];
                }
            }
            
            $rows .= '<tr '.$attr.'>';
            $rows .= $this->_processCells($count);
            $rows .= '</tr>';
        }
        
        return $rows;
    }
    
    /**
     * Checks if sent string is html
     * 
     * @param type $string
     * @return boolean
     */
    private function _isHtml($string)
    {
        
        return preg_match("/^<[^<]+>/",$string,$m) != 0;
    }
    
    /**
     * Returns the fully rendered table
     * 
     * @param type $return
     * @return type
     */
    public function render($return = FALSE){
        
        $rowcount = $this->_getCount();
        
        if($rowcount >= '1'){
            
            $this->_init();
        
            //build the table
            $this->buildTable($this->_schematic);
            
            //attach the shortcut menus
            if($this->_contextmenu){                 
                $this->table .= $this->_contextmenuscript;
            }
        }
        else{
            $this->table .= '<div class="clearfix"></div>'.Views\Notifications::Alert('No records found', 'info', TRUE, TRUE);
        }
        
        if($return == FALSE){            
            echo $this->table;
        }
        else{            
            return $this->table;
        }
    }
    
    /**
     * Disables all the Data tables functionality to make it print friendly
     */
    public function printOutput($return = FALSE){
        
        $rowcount = $this->_getCount();
        
        if($rowcount >= '1'){        
            //build the table
            $this->buildTable($this->_schematic);
        }
        else{            
            $this->table .= Views\Notifications::Alert('No records found', 'info', TRUE, TRUE);
        }
        
        if($return == FALSE){            
            echo $this->table;
        }
        else{            
            return $this->table;
        }
    }
    
    /**
     * Returns simple table based on single ORM object properties
     * 
     * @param string $type either bootstrap or raw
     * @param object $contents_object
     * @param array $table_attrs Any attributes to be added to the table
     * 
     * @return html Fully rendered table
     */
    public static function fromObject($type, $contents_object, $table_attrs = array()){
        
        if($type == 'raw'){
            
            if(count($table_attrs) >= 1){
            
                $attrs = self::_parseAttributes($table_attrs);
            }
        }
        elseif($type == 'bootstrap'){
            
            if(isset($table_attrs['class'])){
                
                $attrs = 'class="table '.$table_attrs['class'].'"';
                unset($table_attrs['class']);
            }
            
            if(count($table_attrs) >= 1){
            
                $attrs .= self::_parseAttributes($table_attrs);
            }
        }
        
        $table = '<table '.$attrs.'>';
        $table .= '<tbody>';
        
        if(!is_null($contents_object)){
            
            $properties = get_object_vars($contents_object);

            foreach($properties as $property => $value){

                if(is_null($value)){

                    $value = 'Not Specified';
                }

                $table .= '<tr>'
                        . '<td><strong>'.str_replace('_',' ',ucwords($property)).'</strong></td>'
                        . '<td>'.$value.'</td>'
                        . '</tr>';

            }

            $table .= '</tbody>'
                    . '</table>';
        }
        else{
            
            $table = Notifications::Alert('No data provided', 'info', TRUE, TRUE);
        }
        
        return $table;
    }
}