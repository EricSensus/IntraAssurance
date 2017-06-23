<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
    
                $("#customers_footer").ready(function(){
                    $("#customers_table_paginate").appendTo($("#customers_footer"));
                });
                
            } );');

echo '<div class="row">'
    . '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Customer Management (' . $count . ')</h4>
                    <p class="small"><strong>Manage</strong> Your Customers</p>
            </div>
            <div class="right toolholder">
            ' . $customers_tools . '
            </div>
    </div>
</div>
</div>';
if (!empty($deleted)) {
    echo '<div class="alert alert-info">' . $deleted . '</div>';
}
echo $alerts;

echo '<div class="row show-grid">'

    . '<div class="col-md-12 panel">
    <div class="dash-head clearfix mt15 mb20">
    ' . $customers_table . '
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
    . '<div id="customers_footer">'
    . '</div>'
    . '</div>';

echo '</div>'
    . '</div>';

echo \Jenga\App\Views\Overlays::Modal(['id' => 'credetialsModal']);