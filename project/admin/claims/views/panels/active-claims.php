<?php

use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Views\Notifications;
use Jenga\App\Views\Overlays;

use Jenga\MyProject\Elements;

HTML::script('$(document).ready( function () {
                $("#claims_table").ready(function(){
                    $("#claims_table_paginate").appendTo($(".claims-pagination"));
                });
            } );');

$url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'policies']);
?>

    <div class="mini-panel">
        <div class="float-left">
            <h4 class="mb5 text-light">Active Claims (<?php echo $count; ?>)</h4>
        </div>
        <div class="float-right">
            <div class="toolbar unprocessedpolicies">
                <div class="toolcell">
                    <div class="toolicon redirect">
                        <a id="unprocessedpolicies_redirect" class="redirect toolsbutton" href="<?php echo Url::base().$url?>" <?php echo Notifications::tooltip('Go to main page') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/redirect_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell">
                    <div class="toolicon maximize">
                        <a id="unprocessedpolicies_maximize" onclick="maximizePanel('unprocessedpolicies_panel')" class="maximize toolsbutton" <?php echo Notifications::tooltip('Maximize Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/maximize_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell">
                    <div class="toolicon minimize">
                        <a id="unprocessedpolicies_minimize" onclick="minimizePanel('unprocessedpolicies_panel')" class="minimize toolsbutton" <?php echo Notifications::tooltip('Minimize Panel') ?>>
                            <img src="http://localhost/esurance/project/templates/admin/images/icons/small/minimize_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell">
                    <div class="toolicon power">
                        <a id="unprocessedpolicies_power" onclick="removePanel('unprocessedpolicies')" class="power toolsbutton" <?php echo Notifications::tooltip('Remove Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/power_icon.png">
                        </a>
                    </div>
                </div>
                <!--
                <div class="toolcell">
                    <div class="toolicon">
                        <a id="unprocessedpolicies_more" class="more toolsbutton">
                            <img src="http://localhost/esurance/project/templates/admin/images/icons/small/more_icon.png">
                        </a>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="unprocessedpolicies_panel">
    <?php
        echo $claims_table;
    ?>
    </div>
    <div class="dataTables_wrapper panel-footer">
        <div class="float-left">
            <p><strong>Shows</strong> the are the <b>New</b> Claims.</p>
        </div>
        <div class="float-right claims-pagination">
        </div>
    </div>
