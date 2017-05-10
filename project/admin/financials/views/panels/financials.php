<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
    
                $("#finances_footer").ready(function(){
                    $("#finances_table_paginate").appendTo($("#finances_footer"));
                });
                
                $(".toolholder").ready(function(){ 
                    $(".toolbar").appendTo($(".toolholder"));
                });
                
            } );');

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Financials Management ('.$count.')</h4>
                    <p class="small"><strong>Manage payments</strong> made by your Customers</p>
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
    '.$finances_table.'
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
. '<div id="finances_footer">'
. '</div>'
. '</div>';

echo '</div>'
. '</div>';