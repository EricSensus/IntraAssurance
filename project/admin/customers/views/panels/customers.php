<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
    
                $("#customers_footer").ready(function(){
                    $("#customers_table_paginate").appendTo($("#customers_footer"));
                });
                
                $(".toolholder").ready(function(){
                    $(".toolbar").appendTo($(".toolholder"));
                });
                
            } );');

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Customer Management ('.$count.')</h4>
                    <p class="small"><strong>Manage</strong> Your Customers</p>
            </div>
            <div class="right toolholder">
            </div>
    </div>
</div>
</div>';

echo $alerts;

echo '<div class="row show-grid">'

. '<div class="col-md-12 panel">
    <div class="dash-head clearfix mt15 mb20">
    '.$customers_table.'
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
. '<div id="customers_footer">'
. '</div>'
. '</div>';

echo '</div>'
. '</div>';
