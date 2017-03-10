<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
    
                $("#companies_footer").ready(function(){
                    $("#companies_table_paginate").appendTo($("#companies_footer"));
                });
                
                $(".toolholder").ready(function(){
                    $(".toolbar").appendTo($(".toolholder"));
                });
                
            } );');

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Insurance Companies Management ('.$count.')</h4>
                    <p class="small"><strong>Manage</strong> Your Insurance Companies</p>
            </div>
            <div class="right toolholder">';
echo '</div>
    </div>
</div>
</div>';

echo '<div class="row show-grid">'

. '<div class="col-md-12 panel">
    <div class="dash-head clearfix mt15 mb20">
    '.$companies_table.'
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
. '<div id="companies_footer">'
. '</div>'
. '</div>';

echo '</div>'
. '</div>';

