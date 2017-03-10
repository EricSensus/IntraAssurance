<?php
use Jenga\App\Request\Url;
use Jenga\MyProject\Elements;

$action = Elements::call('Navigation/NavigationController')->getUrl('setup');

echo '<div class="setup-left">' 
        . '<h4 class="mb5 text-light">Commissions Configuration</h4>'
        . '<p>This section allows you to set the commissions for each product as per the insurance company. '
        . 'Also configure the means of collection</p>'
    . '</div>';

echo '<div class="setup-right">';

echo '<form class="Zebra_form commissions" method="post" action="'.Url::base().'/admin/insurers/savecommissions">'
        . '<input type="hidden" value="'.$action.'#commissions" name="destination">';

foreach($commissions as $insurer => $products){
    
    $inskeys = array_keys($products);
    
    echo '<div class="panel">';
    echo '<div class="panel-heading"><h3 class="panel-title">'.$insurer.'</h3></div>';
    
    echo '<table class="commtable">'
            . '<tbody>'
                . '<tr class="field">'
                    . '<td width="20%">'
                        . '<div class="label">Means of Collection</div>'
                    . '</td>'
                    . '<td width="80%">'
                        . '<div class="element">'
                            . '<select name="collection['.$inskeys[0].']">'
                                . '<option value="0" '.($collections[$inskeys[0]] == 0 ? 'selected="selected"' : '').'>-- please select from here --</option>'
                                . '<option value="1" '.($collections[$inskeys[0]] == 1 ? 'selected="selected"' : '').'>Directly by you</option>'
                                . '<option value="2"'.($collections[$inskeys[0]] == 2 ? 'selected="selected"' : '').'>Payments collected by insurer</option>'
                            . '</select>'
                        . '</div>'
                    . '</td>'
                . '</tr>'
            . '</tbody>'
        . '</table>';
    
    echo '<table class="commtable table-striped">'
        . '<tbody>';
            echo '<tr>'
                    . '<td width="70%" class="heading">'
                    . '<strong>PRODUCTS</strong>'
                    . '</td>'
                    . '<td width="30%" class="heading">'
                    . '<strong>COMMISSION</strong>'
                    . '</td>'
            . '</tr>';
            
            foreach($products as $insurerid => $productlist){
                
                foreach($productlist as $product => $commission){
                    
                    echo '<input type="hidden" value="'.$commission[0].'" name="id[]">';
                    echo '<tr>'
                        . '<td class="productrow">'
                            . $productslist[$product]
                        . '</td>'
                        . '<td class="productrow">'
                            . '<input value="'.($commission[1] == '' ? 0 : $commission[1]).'" style="width: 40%; border-radius: 4px; border: 1px solid #999; text-align: right; padding-right: 8px;" maxlength="6" class="numberfield" name="commission['.$insurerid.']['.$product.']" id="commission_'.$insurerid.'_'.$product.'"> %'
                        . '</td>'
                    . '</tr>';
                }
            }
            
    echo '</tbody>'
        . '</table>';
    echo '</div>';
}

echo '<table cellspacing="0" cellpadding="0">'
    . '<tbody>'
        . '<tr class="toprow">'
            . '<td width="50%" align="left">'
            . '</td>'
            . '<td width="50%" align="right">'
                . '<input type="submit" class="submit" value="Save Commissions" id="btnsubmit" name="btnsubmit">'
            . '</td>'
        . '</tr>'
    . '</tbody>'
    . '</table>';

echo '</form>';
echo '</div>';
