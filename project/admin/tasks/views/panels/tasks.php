<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

HTML::script('$(function(){'
                 . '$(".taskicons").hide();
                    $("ul.tasks li").mouseover(function(){
                        $(this).find(".taskicons").show();
                     });
                     
                     $("ul.tasks li").mouseout(function(){
                        $(this).find(".taskicons").hide();
                     });
             });');
?>
<div class="tasks">
    <div class="mini-panel">
        <div class="float-left">
            <h4 class="mb5 text-light">Tasks & Reminders</h4>
        </div>
        <div class="float-right" style="position: absolute; top: 22px; right: 30px;">
            <div class="toolbar">
                <div class="toolcell">
                    <div class="toolicon add">
                        <a id="tasks_add" class="toolsbutton" href="<?php echo Url::base().'/ajax/admin/tasks/addtask' ?>" data-toggle="modal" data-backdrop="static" data-target="#<?php echo $addtaskmodal['id'] ?>" >
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/add_icon.png" <?php echo Notifications::tooltip('Add New Task') ?>>
                        </a>
                    </div>
                </div>
                <div class="toolcell show_on_more">
                    <div class="toolicon redirect">
                        <a id="tasks_redirect" class="toolsbutton" <?php echo Notifications::tooltip('Show Tasks & Reminders Calendar') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/redirect_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell show_on_more">
                    <div class="toolicon maximize">
                        <a id="tasks_maximize" onclick="maximizePanel('tasks_panel')" class="toolsbutton" <?php echo Notifications::tooltip('Maximize Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/maximize_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell show_on_more">
                    <div class="toolicon minimize">
                        <a id="tasks_minimize" onclick="minimizePanel('tasks_panel')" class="toolsbutton" <?php echo Notifications::tooltip('Minimize Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/minimize_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell show_on_more">
                    <div class="toolicon power">
                        <a id="tasks_power" onclick="removePanel('tasks')" class="toolsbutton" <?php echo Notifications::tooltip('Remove Panel') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/power_icon.png">
                        </a>
                    </div>
                </div>
                <div class="toolcell">
                    <div class="toolicon more">
                        <a id="tasks_more" class="toolsbutton" onclick="toggleHiddenIcons()" <?php echo Notifications::tooltip('Click to show icons') ?>>
                            <img src="<?php echo TEMPLATE_URL ?>admin/images/icons/small/more_icon.png">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tasks_panel">
    <?php
        echo $tasks;
        
        echo Overlays::Modal($addtaskmodal);
        echo Overlays::confirm();
    ?>
    </div>
    <div class="panel-footer">
        <p><strong>Shows</strong> the tasks and reminders</p>
    </div>
</div>