<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
    
                $("#users_footer").ready(function(){
                    $("#users_table_paginate").appendTo($("#users_footer"));
                });
                
                $(".toolholder").ready(function(){
                    $(".toolbar").appendTo($(".toolholder"));
                });
                
            } );');

echo $loginmodal;
echo $deletemodal;

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">User Management ('.$count.')</h4>
                    <p class="small"><strong>Manage</strong> Your System Users</p>
            </div>
            <div class="right toolholder">';
echo '</div>
    </div>
</div>
</div>';

echo $alerts;

echo '<div class="row show-grid">'

. '<div class="col-md-12 panel">
    <div class="dash-head clearfix mt15 mb20">
    '.$users_table.'
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
. '<div id="users_footer">'
. '</div>'
. '</div>';

echo '</div>'
. '</div>';