<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;
HTML::script("$(function() { 
    
    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('a[data-toggle=\"tab\"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
    });

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        $('[href=\"' + lastTab + '\"]').tab('show');
    }
});");

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Customer: '.$customer_name.'</h4>
                    <p class="small"><strong>Customer</strong> Details</p>
            </div>
            <div class="right toolholder">';
echo '</div>
    </div>
</div>
</div>';

echo '<div class="tabs row-padding">
    <ul role="tablist" class="nav nav-pills" id="myTabs">
      <li class="active" role="presentation" ><a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#personal-details">Personal Details</a></li>
      <li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#policies" aria-expanded="true">Policies ('.$policycount.')</a></li>
      <li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#claims" aria-expanded="true">Claims ('.$claims_count.')</a></li>
      <li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#generated-quotes" aria-expanded="true">Generated Quotations ('.$quote_count.')</a></li>'
    .'<li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#entities" aria-expanded="true">Entities ('.$entitycount.')</a></li>';
echo '<li class=""><a aria-controls="profile" data-toggle="tab" role="tab" href="#tasks" aria-expanded="false">Related Tasks and Remainders ('.$taskcount.')</a></li>
    </ul>
    </div>';

echo '<div class="row show-grid">';
echo '<div class="tab-content shadow col-md-12">';

//personal details tab
echo '<div role="tabpanel" class="tab-pane active" id="personal-details">';

    echo '<div class="col-md-6">'
        .'<div class="shadow">';
    echo '<div class="mini-panel">';

    echo '<div class="left">';
    echo HTML::heading('h4', 'Personal Details', ['class' => 'mb5 text-light']);
    echo '</div>';

    echo '<div class="right">'
        . '<a data-toggle="modal" data-target="#'.$personalmodal['id'].'" '
        . 'href="'.SITE_PATH.'/ajax/admin/customers/edit/'.$customer->id.'">';
    echo '<img '.Notifications::popover('Click to edit content', ['data-placement' => 'top']).' src="'.TEMPLATE_URL.'/admin/images/icons/edit_icon.png" width="25px" height="25px">'
        . '</a>';

    echo '</div>';

    echo Overlays::Modal($personalmodal);
    echo HTML::simpleTable('bootstrap', $customer, ['class' => 'table-striped']);

    echo '</div>';

    echo  '<div class="dataTables_wrapper panel-footer">'
    . '<p><strong>Shows</strong> the customer\'s personal details</p>'
    . '</div>';

    echo '</div>
        </div>';

    echo '<div class="col-md-6">'
        .'<div class="shadow">';
    echo '<div class="mini-panel">';

    echo '<div class="left">';
    echo HTML::heading('h4', 'Linked Agent', ['class' => 'mb5 text-light']);
    echo '</div>';

    echo '<div class="right">'
        . '<a data-toggle="modal" data-target="#'.$agentmodal['id'].'" '
        . 'href="'.SITE_PATH.'/ajax/admin/customers/addagent/'.$customer->id.'">';
    echo '<img '.Notifications::popover('Click to add a linked agent', ['data-placement' => 'top']).' src="'.TEMPLATE_URL.'/admin/images/icons/edit_icon.png" width="25px" height="25px">'
        . '</a>';
    echo '</div>';
    echo '<div class="clearfix"></div>';

    echo Overlays::Modal($agentmodal);
    echo HTML::simpleTable('bootstrap', $agent, ['class' => 'table-striped']);

    echo '</div>';

    echo  '<div class="dataTables_wrapper panel-footer">'
    . '<p><strong>Shows</strong> the linked insurance agent</p>'
    . '</div>';

    echo '</div>
        </div>';

echo '</div>';

//policies tab
HTML::script('$(document).ready( function () {
                $("#mypolicies").ready(function(){
                    $("#policiestable_paginate").appendTo($("#mypolicies"));
                });
            } );');

echo '<div role="tabpanel" class="tab-pane" id="policies">';
echo '<div class="col-md-12">'
     .'<div class="shadow">'
    . '<div class="mini-panel">';

echo HTML::heading('h4', 'Customer Policies', ['class' => 'mb5 text-light']);
echo $policiestable;

echo  '</div>'
. '<div class="dataTables_wrapper panel-footer">'
. '<div id="mypolicies">'
. '</div>'
. '</div>';

echo '</div>
    </div>
    </div>';
//claims
HTML::script('$(document).ready( function () {
                $("#myclaims").ready(function(){
                    $("#claimstable_paginate").appendTo($("#myclaims"));
                });
            } );');
echo '<div role="tabpanel" class="tab-pane" id="claims">';

echo '<div class="col-md-12">'
    .'<div class="shadow">'
    . '<div class="mini-panel">';

echo HTML::heading('h4', 'Customer Claims', ['class' => 'mb5 text-light']);
echo $claimstable;

echo  '</div>'
    . '<div class="dataTables_wrapper panel-footer">'
    . '<div id="myclaims">'
    . '</div>'
    . '</div>';

echo '</div>
    </div>
    </div>';

//generated quotes tab
HTML::script('$(document).ready( function () {
                $("#myquotes").ready(function(){
                    $("#quotestable_paginate").appendTo($("#myquotes"));
                });
            } );');

echo '<div role="tabpanel" class="tab-pane" id="generated-quotes">';

echo '<div class="col-md-12">'
     .'<div class="shadow">'
    . '<div class="mini-panel">';

echo HTML::heading('h4', 'Customer Quotations', ['class' => 'mb5 text-light']);
echo $quotes;

echo  '</div>'
. '<div class="dataTables_wrapper panel-footer">'
. '<div id="myquotes">'
. '</div>'
. '</div>';

echo '</div>
    </div>
    </div>';

//entities tab
HTML::script('$(document).ready( function () {
                $("#myentities").ready(function(){
                    $("#entitiestable_paginate").appendTo($("#myentities"));
                });
                $("a.new-entity").popover({
                    title: "Add New Customer Entity<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>",
                    content: "'.$generic_entity_links.'",
                    html: true,
                    placement: "left"
                });
                $(document).on("click", ".popover .close" , function(){
                    $(this).parents(".popover").popover("hide");
                });
            });');

echo '<div role="tabpanel" class="tab-pane" id="entities">';
echo '<div class="col-md-12">'
     .'<div class="shadow">'
    . '<div class="mini-panel">';

echo '<div class="panel-heading">'
    . '<h4 class="mb5 text-light" style="width: auto; float:left">Customer Registered Entities</h4>'
        . '<div class="toolicon" style="width: auto; float:right" >
                <a tabindex="0" class="new-entity add toolsbutton" role="button" data-trigger="focus">
                    <img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/add_icon.png">
                </a>
            </div>'
    . '</div>';

echo $entitiestable;
echo $addnewentity;

echo  '</div>'
. '<div class="dataTables_wrapper panel-footer">'
. '<div id="myentities">'
. '</div>'
. '</div>';

echo '</div>
    </div>
    </div>';

//tasks tab
HTML::script('$(function(){'
                 . '$(".taskicons").hide();
                    $("ul.tasks li").mouseover(function(){
                        $(this).find(".taskicons").show();
                     });
                     
                     $("ul.tasks li").mouseout(function(){
                        $(this).find(".taskicons").hide();
                     });
                     
                     $("ul.tasks li").click(function(){
                        
                        var taskid = $(this).attr("id");

                        $("ul.tasks li").removeClass(\'selected\');
                        $(".task-preview-panel").html(\''.HTML::AddPreloader('center').'\');
                            
                        $(this).addClass(\'selected\');
                            
                        $.ajax({
                            url: "'.Url::base().'/ajax/admin/tasks/preview",
                            method: "get",                            
                            data: {
                                taskid: taskid
                            },
                            cache: false,
                            success: function(response){                            
                                $(".task-preview-panel").empty();
                                $(".task-preview-panel").html(response);
                            }
                        });
                    });
             });');

echo '<div role="tabpanel" class="tab-pane" id="tasks">';

echo '<div class="col-md-6">'
     . '<div class="shadow">'
     . '<div class="mini-panel">';

echo HTML::heading('h4', 'Tasks & Reminders'
        . '<div class="toolicon" style="width: auto; float:right" >
                <a href="'.Url::base().'/ajax/admin/tasks/addtask/'.$customer->id.'"
                tabindex="0" class="add toolsbutton" role="button" data-target="#addtaskmodal" data-backdrop="static" data-toggle="modal" >
                    <img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/add_icon.png">
                </a>
            </div>', ['class' => 'mb5 text-light']);
echo $tasks;
echo Overlays::Modal(['id' => 'addtaskmodal','size'=>'large']);

echo  '</div>'
    . '<div class="dataTables_wrapper panel-footer">'
    . '<p>Shows the <strong>tasks and remainders</strong> linked to the customer</p>'
    . '</div>';

echo '</div>
    </div>';

echo '<div class="col-md-6">'
     . '<div class="shadow">'
        . '<div class="mini-panel">';

echo HTML::heading('h4', 'Preview Pane', ['class' => 'mb5 text-light']);

echo '<div class="task-preview-panel">'
        . '<h5 style="'
        . 'color:grey; opacity: 0.3; margin-top: 16px; margin-bottom: 27px; font-size: 50px; width: 100%; text-align:center'
        . '">No task selected</h5>';

echo  '</div>
        </div>
            <div class="dataTables_wrapper panel-footer">
                <p>Shows the <strong>Task Preview</strong> Pane</p>
            </div>        
        </div>
        </div>';

echo '</div>';

echo $deletemodal;
echo $quoteModal;