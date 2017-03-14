<?php
use Jenga\App\Request\Url;

function wizardHTML($product,$step, $additional = false){
    
    switch ($step) {
        case '1':
            $step1active = 'class="active"';
            break;
        
        case '2':
            $step2active = 'class="active"';
            break;
        
        case '3':
            $step3active = 'class="active"';
            break;
        
        case '4':
            $step4active = 'class="active"';
            break;
    }
    
    return '<table class="wizard-heading row">
            <tr>
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a '.$step1active.' href="'.Url::link('/'.$product.'/step/1').'"><h5>
                            Stage 1</h5></a>
                    <a '.$step1active.' href="'.Url::link('/'.$product.'/step/1').'">
                        <h6>Proposer Personal Details</h6></a>
                </th>
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a  '.$step2active.' href="'.Url::link('/'.$product.'/step/2').'"><h5>
                            Stage 2 '.($additional === TRUE ? 'B' : '').'</h5></a>
                    <a  '.$step2active.' href="'.Url::link('/'.$product.'/step/2').'">
                        <h6>'.($additional === TRUE ? 'Additional' : '').' Insurance Entity Details</h6></a>
                </th>
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a  '.$step3active.' href="'.Url::link('/'.$product.'/step/3').'"><h5>
                            Stage 3</h5></a>
                    <a  '.$step3active.' href="'.Url::link('/'.$product.'/step/3').'">
                        <h6>Cover Details</h6></a>
                </th>
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a '.$step4active.' href="'.Url::link('/'.$product.'/step/4').'"><h5>
                            Stage 4</h5></a>
                    <a '.$step4active.' href="'.Url::link('/'.$product.'/step/4').'">
                        <h6>Quotation & Payment</h6></a>
                </th>
            </tr>
        </table>';
    
}

