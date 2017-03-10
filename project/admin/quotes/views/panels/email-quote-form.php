<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;

HTML::script(PLUGIN_PATH.'/tinymce/tinymce/tinymce.min.js', 'file');
HTML::script(PLUGIN_PATH.'/tinymce/tinymce/jquery.tinymce.min.js', 'file');

HTML::script("$(function() {    

            //get the entity selection
            $('#emailform').on('change','select',function(){

                var sendmode = $('#sendmode').val();
                
                if(sendmode == '2'){    
                    $('.email_content').html(\"".$pdf_content."\").tinymce({theme:\"modern\"});
                    $('#content').after('".HTML::AddPreloader('left','40px','40px')."');
                        
                    $.ajax({
                        url: '".Url::base()."/ajax/admin/quotes/createattachment',
                        method: 'post',
                        data: {
                            id: '$id'
                        },
                        success: function(response){
                        
                            $('.email_attachment').remove();
                            
                            $('.showpreload').remove();
                            $('#content').after(response);
                        }
                    });
                }
                else if(sendmode == '1'){
                    $('#content').after('');
                    $('.email_attachment').remove();
                    $('.email_content').html(\"".$preview_link_content."\").tinymce({theme:\"modern\"});
                }
            });

            $('#save_button').on('click',function(evt){
            
                // stop default action
                evt.preventDefault();
                var isFormValid = true;
                
                //process the entire form
                var qform = $('#emailform').data('Zebra_Form');
                
                if(qform.validate()){
                
                    $('input.modal_required').each(function(){
                        if ($.trim($(this).val()).length == 0){
                            $(this).addClass('redline');
                            isFormValid = false;
                        }
                        else{
                            $(this).removeClass('redline');
                        }
                    });
                    $('select.modal_required').each(function(){
                        if ($('select.modal_required').val() == ''){
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
                    
                        qform.submit();
                    }
                }
            });
    });");

echo $addform;



