<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {
                $("#unpaid").ready(function(){
                    $("#policiestable_paginate").appendTo($("#unpaid"));
                });
            } );');

echo '<div class="mini-panel">';

echo '<div class="panel-heading">'
. '<h4 class="mb5 text-light">Unpaid Quotes ('.$unpaid_count.')</h4>'
. '</div>';

echo $minitable;

echo '</div>'
. '<div class="dataTables_wrapper panel-footer">'
. '<div id="unpaid">'
. '</div>'
. '</div>';
