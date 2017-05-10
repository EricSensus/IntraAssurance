<?php
use Jenga\App\Views\HTML;

echo '<link href="'.RELATIVE_APP_PATH.'/html/facade/Zebra_Form/public/css/zebra_form.css" rel="stylesheet">';
echo $script;

echo $eform;

HTML::script("$(function() {       

            $('button[type=\"submit\"]').on('click',function(e){
            
                // stop default action
                e.preventDefault();
                var isFormValid = true;
                
                $('input.required').each(function(){
                    if ($.trim($(this).val()).length == 0 || $.trim($(this).val())=='0.00'){
                        $(this).addClass('redline');
                        isFormValid = false;
                    }
                    else{
                        $(this).removeClass('redline');
                    }
                });
                
                $('select.required').each(function(){
                    if ($.trim($(this).val()).length == 0){
                        $(this).addClass('redline');
                        isFormValid = false;
                    }
                    else{
                        $(this).removeClass('redline');
                    }
                });   

                if(isFormValid == false){
                    $('html, body').animate({
                        scrollTop: $('.redline').first().offset().top
                    }, 1000);
                }
                else{
                    $('form#entityform').submit();
                }
            });
    });");
