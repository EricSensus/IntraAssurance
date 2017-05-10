<?php
use Jenga\App\Views\HTML;

HTML::script('$(document).ready( function () {   
    
                $("#name").focusout(function(){
                
                    var name = $(this).val();
                    var alias = name.toLowerCase().replace(/ /g,"-");
                    
                    $("#alias").val(alias);
                });                
            });');

echo $editform;
