<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;

HTML::script('$( document ).ready(function() {
                        $("button#'.$submit.'").bind(\'click\',function() { 
                           $("form#'.$settings['formid'].'").submit();
                       });
                    });','script',TRUE);

HTML::script("$(function(){
    
                $('button#create_button').bind('click',function() { 
                     $('form#statusform').submit();
                });

                function ajaxSaveStatus(response, offer){
                    
                    $('#label_confirm_create').html('Create Policy Now? ".HTML::AddPreloader('left','20px','20px')."');
                    $.ajax({
                        url: '".Url::base().'/ajax'.Url::route('/admin/{element}/{action}/{id}',
                                        ['element'=>'quotes','action'=>'savestatus','id'=>$id])."',
                        method: 'post',
                        data: {
                            response: response,
                            offer: offer
                        },
                        success: function(reply){
                        
                            $('#label_confirm_create').html('Create Policy Now?');
                            $('#create_button').removeAttr('disabled');
                            $('#save_button').attr('disabled','disabled');
                            
                            $('#statusform').attr('action', '".Url::base().Url::route('/admin/{element}/{action}/{id}',
                                ['element'=>'policies','action'=>'createpolicy'])."');
                        }
                    });
                }

                //disable offers list
                $('#offer').attr('disabled', 'disabled');
                $('#confirm_create_yes').attr('disabled', 'disabled');
                
                //mark the customer response
                $('#response').on('change',function(){
                
                    var resp = $(this).val();
                    
                    if(resp == 'policy_pending'){
                        $('#offer').removeAttr('disabled');
                        $('#confirm_create_yes').removeAttr('disabled');
                    }
                    else if(resp == 'rejected'){
                        $('#offer').attr('disabled', 'disabled');
                    }
                });
                
                $('#confirm_create_yes').on('click',function(){
                
                    if($(this).prop('checked')){
                        
                        var response = $('#response').val();
                        var offer = $('#offer').val();

                        if(offer != ''){
                            $('#offer').removeClass('redline');
                            ajaxSaveStatus(response, offer);
                        }
                        else{
                            $('#offer').addClass('redline');
                        }
                    }
                    else{
                        $('#create_button').attr('disabled','disabled');
                        $('#save_button').removeAttr('disabled');
                    }
                    
                });
                
                $('#save_button').on('click',function(evt){
            
                // stop default action
                evt.preventDefault();
                var isFormValid = true;
                
                //process the entire form
                var qform = $('#statusform').data('Zebra_Form');
                
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

echo $statusform;

