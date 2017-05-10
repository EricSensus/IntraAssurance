<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;

// don't forget about this for custom templates, or errors will not show for server-side validation
// $zf_error is automatically created by the library and it holds messages about SPAM or CSRF errors
// $error is the name of the variable used with the set_rule method
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));

HTML::script('$(document).ready(function () {   
                    
                    $("#entity_field_type").change(function(){
                        var $form = $(\'#editfieldform\').data(\'Zebra_Form\');
                            
                        if($form.validate()){
                            var formid = $(\'hidden[name="formid"]\').val();
                            var name = $(\'input[name="entity_field_name"]\').val();
                            var ftype = $(\'select[name="entity_field_type"]\').val();
                            
                            $(".section_two").html(\''.HTML::AddPreloader().'\');

                            $.ajax({
                                method: "GET",
                                url: "'.Url::base().'/ajax/admin/forms/getfieldbytype",
                                data: {
                                    form_id: formid,
                                    field_name: name,
                                    field_type: ftype
                                },
                                success: function(response){
                                    $(".section_two").html(response);
                                },
                                error: function(response){
                                    $(".section_two").html(response);
                                }
                            });
                        }
                    });

                    $("button#save_edit_field_button").bind(\'click\',function(event) { 
                        event.preventDefault(); //prevent default form submit
                        
                        var $form = $(\'#editfieldform\').data(\'Zebra_Form\');
                        var fdata = $(\'#editfieldform\').serialize();
                        
                        if ($form.validate()) {
                            $(".server_response").html(\''.HTML::AddPreloader().'\');
                            $(".section_one").hide();
                            $(".section_two").hide();
                            $(".section_three").hide();
                            
                            $.ajax({
                                method: "GET",
                                url: "'.Url::base().'/ajax/admin/entities/saveentityfield",
                                data: {
                                    formdata: fdata
                                },
                                success: function(response){
                                    $(".server_response").html(response);
                                    $(".section_one").show();
                                    $(".section_two").show().html(\'\');
                                    $(".section_three").show();
                                },
                                error: function(response){
                                    $(".server_response").html(response);
                                }
                            });
                        }
                   });
                });');

echo '<div class="server_response"></div>';
echo '<div class="section_one">';
    echo '<table cellspacing="0" cellpadding="0">'
        . '<tr class="row">'
            . '<td width="30%">'.$label_entity_field_name.'</td>'
            . '<td width="70%">'.$entity_field_name.'</td>'
        . '</tr>';
    echo '<tr class="row">'
            . '<td>'.$label_entity_field_type.'</td>'
            . '<td>'.$entity_field_type.'<span class="small" style="font-size:10px;color:red;"><strong>*to change field type, please delete this field and recreate again<strong></span></td>'
        . '</tr>';
echo '</table>'
    . '</div>';

echo '<div class="row section_two">';
echo $attributes;
echo '</div>';

echo '<div class="section_three">';
    echo '<table cellspacing="0" cellpadding="0">'
        . '<tr class="row">'
            . '<td width="30%">'.$label_required.'</td>'
            . '<td width="70%"><div class="cell">'.$required_yes.'</div> '
            . '<div class="cell"><strong>This would be a required field</strong></div></td>'
        . '</tr>';
echo '</table>'
    . '</div>';
