<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
    
                $("#navgroups_footer").ready(function(){
                    $("#navgroups_table_paginate").appendTo($("#navgroups_footer"));
                });
                
                $(".toolholder").ready(function(){
                    $(".toolbar").appendTo($(".toolholder"));
                });
                
            } );');

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Navigation Management ('.$navcount.')</h4>
                    <p class="small"><strong>Manage</strong> Your System Menu Groups</p>
            </div>
            <div class="right toolholder">';
echo '</div>
    </div>
</div>
</div>';

echo $alerts;
echo $groupmodal;

echo '<div class="row show-grid">'

. '<div class="col-md-12 panel">
    <div class="dash-head clearfix mt15 mb20">
    '.$navgroups.'
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
. '<div id="navgroups_footer">'
. '</div>'
. '</div>';

echo '</div>'
. '</div>';