<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Session;

HTML::script('
        //this maximizes the selected panel
        function maximizePanel(panel){
            $("."+panel).slideToggle();
            
            var elementid = panel.split("_");
            $("div."+elementid[0]+" .maximize").hide();
            $("div."+elementid[0]+" .minimize").show();
        }

        //this minimizes the selected panel
        function minimizePanel(panel){
            $("."+panel).slideToggle();
            
            var elementid = panel.split("_");
            $("div."+elementid[0]+" .minimize").hide();
            $("div."+elementid[0]+" .maximize").show();
        }
        
        //this removes the selected panel
        function removePanel(panel){
            $("."+panel).remove();
        }
                
        //this displays the icons with the show_on_more class attribute
        function toggleHiddenIcons(){
            $(".show_on_more").toggle();
        }
        
        $(document).ready( function () {
                
            $(".toolcell").css({"opacity":"0.5","background-color":"#f5f5f5"});
            $(".toolbar img").css({"width":"20px"});
            $(".toolcell").mouseover(function(){
                $(this).css({"opacity":"1.0"});
            });
            $(".toolcell").mouseout(function(){
                $(this).css({"opacity":"0.5"});
            });

            $("div.maximize").hide();
            $(".show_on_more").hide();
        } );');


if (Session::has('status')) {
    switch (Session::get('status')) {
        case 'agent_attached':
            $quote_id = Session::get("quote_id");

            HTML::script('
                        $(function(){
                            if(confirm("Would you wish to create a task for the attached agent?")){
                                $("#quo_' . $quote_id . '").click();
                            }
                        });
                    ');
            break;
    }
}
?>

<div class="row show-grid">

    <div class="col-md-8">
        <div class="shadow white-bg leads margin-bottom-20px">
            <?php $this->loadPanel('leads'); ?>
        </div>
        <div class="shadow white-bg unfinishedquotes margin-bottom-20px">
            <?php $this->loadPanel('unfinished-quotes'); ?>
        </div>
        <div class="shadow white-bg expiredpolicies margin-bottom-20px">
            <?php $this->loadPanel('expired-policies'); ?>
        </div>

        <div class="shadow white-bg unprocessedpolicies margin-bottom-20px">
            <?php $this->loadPanel('unprocessed-policies'); ?>
        </div>

        <div class="shadow white-bg activeclaims">
            <?php $this->loadPanel('active-claims'); ?>
        </div>


    </div>

    <div class="col-md-4">
        <div class="shadow">
            <?php
            $this->loadPanel('tasks');
            ?>
        </div>

    </div>

</div>