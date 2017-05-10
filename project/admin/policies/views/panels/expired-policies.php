<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

HTML::script('$(document).ready( function () {
                $("#expiringtable").ready(function(){
                    $("#expiringtable_paginate").appendTo($(".expiry-pagination"));
                });
            } );');

$url = Elements::load('Navigation/NavigationController@getUrl', ['alias'=>'policies']);
?>

    <div class="mini-panel">
        <div class="float-left">
            <h4 class="mb5 text-light">Expiring Policies (<?php echo $count; ?>)</h4>
        </div>
        <div class="float-right">
            <div class="toolbar expiringpolicies">
                <div class="toolcell">
                    <div class="toolicon redirect">
                        <a id="expiringpolicies_redirect" class="redirect toolsbutton" href="<?php echo Url::base().$url?>" <?php echo Notifications::tooltip('Go to main page') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/redirect_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell">
                    <div class="toolicon maximize">
                        <a id="expiringpolicies_maximize" onclick="maximizePanel('expiringpolicies_panel')" class="maximize toolsbutton" <?php echo Notifications::tooltip('Maximize Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/maximize_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell">
                    <div class="toolicon minimize">
                        <a id="expiringpolicies_minimize" onclick="minimizePanel('expiringpolicies_panel')" class="minimize toolsbutton" <?php echo Notifications::tooltip('Minimize Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/minimize_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell">
                    <div class="toolicon power">
                        <a id="expiringpolicies_power" onclick="removePanel('expiringpolicies')" class="power toolsbutton" <?php echo Notifications::tooltip('Remove Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/power_icon.png">
                        </a>
                    </div>
                </div>
                <!--
                <div class="toolcell">
                    <div class="toolicon">
                        <a id="expiringpolicies_more" class="more toolsbutton">
                            <img src="http://localhost/esurance/project/templates/admin/images/icons/small/more_icon.png">
                        </a>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="expiringpolicies_panel">
    <?php
        echo $expiringtable;
    ?>
    </div>
    <div class="dataTables_wrapper panel-footer">
        <div class="float-left">
            <p><strong>Shows</strong> the policies which are within a month of expiring</p>
        </div>
        <div class="float-right expiry-pagination">
        </div>
    </div>
