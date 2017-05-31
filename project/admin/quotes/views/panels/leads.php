<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

//HTML::script('$(document).ready( function () {
//                $("#active_quotes_table").ready(function(){
//                    $("#active_quotes_table_paginate").appendTo($(".active-quotes-pagination"));
//                });
//            });');

$url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'quotes']);
?>
<div class="mini-panel">
    <div class="float-left">
        <h4 class="mb5 text-light">New Customer Leads (<?=$count; ?>)</h4>
    </div>
    <div class="float-right">
        <div class="toolbar leads">
            <div class="toolcell">
                <div class="toolicon redirect">
                    <a id="activequotes_redirect" class="toolsbutton" href="<?php echo Url::base().$url?>" <?php echo Notifications::tooltip('Go to main page') ?>>
                        <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/redirect_icon.png">
                    </a>
                </div>
            </div>
            <div class="toolcell">
                <div class="toolicon maximize">
                    <a id="activequotes_maximize" onclick="maximizePanel('leads_panel')" class="toolsbutton" <?php echo Notifications::tooltip('Maximize Panel') ?>>
                        <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/maximize_icon.png">
                    </a>
                </div>
            </div>
            <div class="toolcell">
                <div class="toolicon minimize">
                    <a id="activequotes_minimize" onclick="minimizePanel('leads_panel')" class="toolsbutton" <?php echo Notifications::tooltip('Minimize Panel') ?>>
                        <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/minimize_icon.png">
                    </a>
                </div>
            </div>
            <div class="toolcell">
                <div class="toolicon power">
                    <a id="activequotes_power" onclick="removePanel('leads')" class="toolsbutton" <?php echo Notifications::tooltip('Remove Panel') ?>>
                        <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/power_icon.png">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="leads_panel"> 
    <?= $leads ?>
</div>
<div class="dataTables_wrapper panel-footer">
    <div class="float-left">
        <p>Shows <strong>quotations</strong> which need to be processed</p>
    </div>
    <div class="float-right active-quotes-pagination">

    </div>
</div>