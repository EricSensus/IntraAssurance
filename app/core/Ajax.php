<?php
/**
 * This is a helper class to return some useful functions for ajax requests 
 * which are declared by adding the ajax keyword to the start of any URL
 */
namespace Jenga\App\Core;

use Jenga\App\Request\Input;
use Jenga\App\Views\Notifications;

use Symfony\Component\HttpFoundation\Response;

class Ajax {
    
    public function __construct() {
        //$this->processRows();
    }
    
    /**
     * This is designed to bypass invocation of the __construct() 
     * which hinders Symfony argument resolution in Jenga
     * @return type
     */
    function __invoke() {
        
        $ctrl = new \ReflectionClass(get_class($this));
        return $ctrl->newInstanceWithoutConstructor();
    }
    
    /**
     * Creates the deletion table from the rows sent
     */
    function processRows(){     
        
        if(is_null(Input::post('rows'))){
            
            Notifications::Alert('No records have been sent', 'info',FALSE, TRUE);
        }
        else{
            
            $rows = Input::post('rows');
            $fxn = Input::post('selectfxn');           
            
            $table = '<p style="font-size:small">Uncheck to remove from '.  ucwords(str_replace('_', ' ', $fxn)).' list.</p>'
                    . '<table class="table table-striped">'
                    . '<tbody>';
            
            foreach($rows as $row){
                
                //split each row into its individual function parameters
                $rowobjects = json_decode($row);
                
                foreach($rowobjects as $rowobject){
                    
                    $selectfxn = $rowobject->function;
                    
                    if($selectfxn==$fxn){
                        
                        $table .= '<tr>';

                        $splcolumns = explode(',', $rowobject->params);  
                        $table .= '<td width="10%">'
                                . '<input type="checkbox" name="ids[]" value="'.$splcolumns[0].'" checked>'
                                . '</td>';

                        foreach($splcolumns as $column){
                            $table .= '<td>'.$column.'</td>';
                        }

                        $table .= '</tr>';
                    }
                }
            }
            
            $table .= '</tbody>
                    </table>';
            
            echo $table;
        }
    }
}