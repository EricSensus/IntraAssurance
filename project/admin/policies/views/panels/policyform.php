<?php

    // don't forget about this for custom templates, or errors will not show for server-side validation
    // $zf_error is automatically created by the library and it holds messages about SPAM or CSRF errors
    // $error is the name of the variable used with the set_rule method
    echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));

            echo '<script>'
            . "$(function() {";

            if(!isset($quotes)){
                echo "$('input[name=\"customer\"]').val('');";
            }

            //to disable Submit Button By Default
            echo "$(\"input[type=submit]\").attr('disabled','disabled');

            $('input[name=\"customer\"]').devbridgeAutocomplete({
                serviceUrl: '".SITE_PATH."/ajax/admin/policies/getoldcustomer',
                minChars: 1,
                noCache: true,
                onSearchStart: function (query){
                    var searchinput = $(this).val();
                    $('.autocomplete-suggestions').html('Searching: '+searchinput);                   
                },
                onSelect: function(suggestion){   
                    var selection = $(this).val(suggestion.value);  
                    $('.select-quote').addClass('row');
                    $('.select-quote').addClass('even');
                        
                    $.ajax({
                        url: '".SITE_PATH."/ajax/admin/policies/getquotes?id='+suggestion.data,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response){
                            var suggest = suggestion.value.split('('); 
                                                        
                            if(response.status){
                                $(\"input[type=submit]\").show().removeAttr('disabled').removeClass('disabled');
                                
                                $('#embedded_quote').html('');
                                $('.select-quote').show();
                                $('.select-quote').html(
                                    '<label id=\"label_quote\" for=\"select-quote\">Select a Quote for '+suggest[0]+' to use for the New Policy</label>'+response.content
                                );
                            }else{
                                $('#embedded_quote').html(
                                    '<label id=\"label_quote\" for=\"select-quote\">No quotes found for the selected customer. Please create a quote to proceed</label>' +
                                    '<div><a href=\"".SITE_PATH."/admin/quotes/add?policy=true&id=' + response.customer_id + '\" class=\"btn btn-primary\">Create Quote</a></div>'
                                );
                                $('.select-quote').hide();
                                $(\"input[value='Create Policy']\").hide();
                            }
                        }
                    });
                        
                    $.get('".SITE_PATH."' + '/ajax/admin/quotes/getcustomerdetails', {id: suggestion.data},
                        function (response) {
                            var obj = $.parseJSON(response);
                            $.each(obj, function (key, value) {
                                $(document).find('#' + key).val(value);
                            });
                        });
                    }
            });
        });
        "
    . '</script>';
?>

<div class="row">
    <div class="form-group">
        <?php
            echo $label_customer . $customer
        ?>
    </div>
</div>
<?php
if(!isset($quotes)){
?>
    <div class="select-quote">
    </div>
<?php
}
else{
?>
    <div class="select-quote row even">
        <?php
            echo $label_quotes . $quotes;
        ?>
    </div>
<?php
}
?>
<div class="row last">
    <?php
        echo $btnsubmit
    ?>
</div>

<script>
    $(function(){
        $(document).on('change', '#quotes', function(){
            var option = $(this).val();
            var customer_id = $(this).find(':selected').attr('cust_id');

            if(option == 'new_quote'){
                window.location.href = '<?=SITE_PATH . '/admin/quotes/add?policy=true&id='; ?>' + customer_id;
            }
        });
    });
</script>