<?php
use Jenga\App\Views\HTML;

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
            <h4 class="mb5 text-light">Linked Agent</h4>
        </div>
        <div class="float-right" style="position: absolute; top: 22px; right: 30px;">
            <div class="toolbar"></div>
        </div>
    </div>
    <div class="tasks_panel">
        <?php
            if($agent_attached){
        ?>
            <ul class="list-group" style="margin-bottom: 0px;">
                <li class="list-group-item">Name: <strong><?=$agent->names; ?></strong> </li>
                <li class="list-group-item">Telephone No: <strong><?=$agent->telephone_number; ?></strong></li>
                <li class="list-group-item">Email Address: <strong><?=$agent->email_address; ?></strong></li>
            </ul>
        <?php } else { ?>
            <div class="alert alert-info">
                <strong>No Attached Agent!</strong>
            </div>
        <?php } ?>
    </div>
    <div class="panel-footer">
        <p><strong>Shows</strong> the linked agent details</p>
    </div>
</div>

