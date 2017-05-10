<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
    
                $("#claims_footer").ready(function(){
                    $("#claims_table_paginate").appendTo($("#claims_footer"));
                });
                
                $(".toolholder").ready(function(){
                    $(".toolbar").appendTo($(".toolholder"));
                });
                
            } );');
echo '<div class="row">'
    . '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Claim Management (' . $count . ')</h4>
                    <p class="small"><strong>Manage</strong> claims</p>
            </div>
            <div class="right toolholder">
            ' . $claims_tools . '
            </div>
    </div>
</div>
</div>';

echo $alerts;

echo '<div class="row show-grid">'

    . '<div class="col-md-12 panel">
    <div class="dash-head clearfix mt15 mb20">
    ' . $claims_table . '
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
    . '<div id="claims_footer">'
    . '</div>'
    . '</div>';

echo '</div>'
    . '</div>';

echo $mailmodal;

?>