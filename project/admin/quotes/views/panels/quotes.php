<?php
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;

HTML::script('$(document).ready( function () {
    
                $("#quotes_footer").ready(function(){
                    $("#quotes_table_paginate").appendTo($("#quotes_footer"));
                });
                
                $(".toolholder").ready(function(){
                    $(".toolbar").appendTo($(".toolholder"));
                });
                
            } );');

echo '<div class="row">'
    . '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Quotation Management (' . $count . ')</h4>
                    <p class="small"><strong>Manage</strong> Your Customer Quotes</p>
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
    ' . $quotes_table . '
    </div>';

echo '<div class="dataTables_wrapper panel-footer">'
    . '<div id="quotes_footer">'
    . '</div>'
    . '</div>';

echo '</div>'
    . '</div>';

echo Overlays::Modal(['id' => 'emailmodal']);
echo Overlays::Modal(['id'=>'quotemodal','size'=>'large']);
echo Overlays::Modal(['id' => 'confirmquotemodal', 'title' => 'Mark Customer Response']); 

echo Overlays::confirm();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>
