<?php
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

// don't forget about this for custom templates, or errors will not show for server-side validation
// $zf_error is automatically created by the library and it holds messages about SPAM or CSRF errors
// $error is the name of the variable used with the set_rule method
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));

echo '<script>'
. "$(function() {
        $('select[name=\"insurers\"]').attr('disabled','disabled');
        $('button.addinsurer').attr('disabled','disabled');

        $('button.addentity').hide();

        //process customer details
        $('input[name=\"customer\"]').devbridgeAutocomplete({
            serviceUrl: '".SITE_PATH."/ajax/admin/quotes/getcustomer',
            minChars: 1,
            onSearchStart: function (query){
                var searchinput = $(this).val();
                $('.autocomplete-suggestions').html('Searching: '+searchinput);                   
            },
            onSelect: function(suggestion){   
                var selection = $(this).val(suggestion.value);  

                $('input[name=\"customerid\"]').val(suggestion.data);
                $.get('".SITE_PATH."/ajax/admin/quotes/getcustomerdetails',{id: suggestion.data},
                    function(response){         
                        var obj = $.parseJSON(response);
                        $.each(obj, function(key, value){
                            $('#'+key).val(value);
                        });
                    });
            }
        });

        //process the product details
        $('select[name=\"product\"]').on('change',function(){
        
            var accept = confirm('This action will erase all the information in the fields below. Proceed?');
            
            if(accept == true){
            
                var productid = $(this).val();
                var customerid = $('input[name=\"customerid\"]').val();

                if(productid != ''){

                    $('div.ajaxresponse1').html('".HTML::AddPreloader()."');
                    $('div.ajaxresponse2').html('".HTML::AddPreloader('left','40px','40px')."');

                    $.ajax({
                        url: '".SITE_PATH."/ajax/admin/products/getfullproductform/',
                        method: 'post',
                        data: {
                            id: productid                        
                        },
                        success: function(response){
                            $('div.ajaxresponse1').empty();
                            $('tr.extra_form_fields').remove();

                            $('table.product-details tr:last').after(response);
                        },
                        error: function(response){
                            $('div.ajaxresponse1').html(response);
                        }
                    });

                    $.ajax({
                        url: '".SITE_PATH."/ajax/admin/entities/selectformfromproductid/',
                        method: 'post',
                        data: {
                            productid: productid,
                            customerid: customerid
                        },
                        success: function(response){

                            $('div.ajaxresponse2').html(response);
                        }
                    });
                }
            }
        });

        //get the entity selection
        $('div.ajaxresponse2').on('change','select',function(){

            var enaccept = confirm('This action will result in creation of a new entity or assigning a different entity to this quotation. Proceed?');
            
            if(enaccept == true){
            
                var selectvalue = $('select[name=\"entities\"]').val();

                var defaultvalue = $('input[name=\"entityformid_'+selectvalue+'\"]').val();                
                var newentity = $('input[name=\"newentity\"]').val();

                $('select[name=\"insurers\"]').removeAttr('disabled');

                if(selectvalue == newentity){

                    $('button.addentity').hide();
                    $('table.entity-details tr.extra_form_fields').remove();
                    $('div.ajaxresponse3').html('".HTML::AddPreloader()."');

                    $.ajax({
                        url: '".SITE_PATH."/ajax/admin/entities/getfullentityform',
                        method: 'post',
                        data: {
                            defaultval: defaultvalue
                        },
                        success: function(response){
                            $('div.ajaxresponse3').empty();
                            $('table.entity-details tr:last').after(response);
                        }
                    });
                }
                else{

                    if(selectvalue != newentity && selectvalue != ''){
                        $('button.addentity').show();
                    }

                    $('table.entity-details tr.extra_form_fields').remove();
                }
            }
        });

        //add saved entity
        $('button.addentity').on('click',function(){

            $('div.ajaxresponse3').html('".HTML::AddPreloader()."');
            var selectvalue = $('select[name=\"entities\"]').val();

            $.ajax({
                    url: '".SITE_PATH."/ajax/admin/entities/returnentityentries',
                    method: 'post',
                    data: {
                        ajax: 'yes',
                        entityval: selectvalue
                    },
                    success: function(response){

                        $('#insurers option[value=\"'+selectvalue+'\"]').remove();

                        $('div.ajaxresponse3').empty();
                        $('div.ajaxresponse3').html(response);
                    }
                });
        });

        //remove stored entity
        $('div.ajaxresponse3').on('click','a',function(){

            var accept = confirm('This will delete this entity. Proceed?'); 

            if(accept == true){
                var entityid = $(this).attr('id');
                $('.'+entityid).remove();
            }
        });
        
        //create pricing table
        $('select[name=\"insurers\"]').on('change',function(){

            $('button.addinsurer').removeAttr('disabled');
        });

        $('button.addinsurer').on('click',function(){

            var insurerid = $('select[name=\"insurers\"]').val();
            var tableprice = $('table.insurer-price');

            if(insurerid != ''){

                //check if another table already exists
                if(tableprice.parent().length == 0){
                    $('div.insurer-prices').html('".HTML::AddPreloader('left','40px','40px')."');
                }
                else{
                    tableprice.last().after('".HTML::AddPreloader('left','40px','40px')."');
                }

                $.ajax({
                    url: '".SITE_PATH."/ajax/admin/quotes/createinsurerpricetable',
                    method: 'post',
                    data: {
                        insid: insurerid
                    },
                    success: function(response){

                        $('#insurers option[value=\"'+insurerid+'\"]').remove();
                        $('div.showpreload').remove();

                        //check if another table already exists
                        if(tableprice.parent().length == 0){
                            $('div.insurer-prices').html(response);
                        }
                        else{
                            tableprice.last().after(response);
                        }
                    }
                });
            }
        });
        
        ".$productform['script']."
            
        ".$entityform['script']."
    });
    "
. '</script>';

if($rawstatus == 'policy_pending' || $rawstatus == 'policy_created'){
    
    HTML::script('$(function() {'
            . '$("#quoteform :input").prop("disabled", true);'
            . '});');
    
    Notifications::Alert('This offer has already been accepted by the customer, so changes have been disabled', 'info', FALSE, TRUE);
}

?>
<table class="policy table-striped">
    <tr>
          <td class="heading" colspan="2">
            <h2 class="mb5 text-light">Quote Generation</h2>
          </td>
      </tr>
      <tr>
        <td>
            <h4 class="status">
                Status
            </h4>
        </td>
        <td class="field">
            <h4><strong>
            <?php 
                echo $status;
            ?>
            </strong></h4>
        </td>
    </tr>
    <tr>
        <td width="25%">
            <?php 
                echo '<h4>' .$label_dategen .'</h4>';
            ?>
        </td>
        <td class="field" width="75%">
            <?php
                echo '<div class="data">'. $dategen.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<h4>' .$label_agent.'</h4>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$agent.'</div>';
            ?>
        </td>
    </tr>
</table>
<table class="policy table-striped">
    <tr>
      <td class="heading" colspan="2">
        <h2 class="mb5 text-light">Customer Information</h2>
      </td>
    </tr>
    <tr>
        <td width="25%">
            <?php 
                echo '<h4>' .$label_customer. '</h4>';
            ?>
        </td>
        <td class="field" width="75%">
            <?php 
                echo '<div class="data">'.$customer.'</div>';
            ?>
            <span class="small" style="color: red;">Type to search for customer's name</span>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<h4>' .$label_email. '</h4>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$email.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<h4>' .$label_phone. '</h4>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$phone.'</div>';
            ?>
        </td>
    </tr>
</table>
<table class="policy product-details table-striped">
    <tr>
      <td class="heading" colspan="2">
        <h2 class="mb5 text-light">Product Information</h2>
      </td>
    </tr>
    <tr>
        <td width="25%">
            <?php 
                echo '<h4>' .$label_product. '</h4>';
            ?>
        </td>
        <td class="field" width="75%">
            <?php 
                echo '<div class="data">'.$product.'</div>';
            ?>
        </td>
    </tr>
    <?php
        echo $productform['form'];
    ?>
</table>
<div class="ajaxresponse1" style="width:100%; text-align: center;"></div>

<table class="policy entity-details table-striped">
    <tr>
      <td class="heading" colspan="3">
        <h2 class="mb5 text-light">Customer Entity Information</h2>
      </td>
    </tr>
    <tr>
      <td width="25%">
        <label id="label_entity">Select Entity<span class="required">*</span></label>
      </td>
      <td width="75%" class="field">
          <div class="ajaxresponse2" style="width:80%; text-align: left; float: left;">
              <?php
              echo $entityform['select'];
              ?>
          </div>
        <button type="button" class="btn btn-default addentity">Add Entity</button>
      </td>
    </tr>
    <?php
        echo $entityform['form'];
    ?>
</table>
<div class="ajaxresponse3" style="width:100%; text-align: left;">
    <?php
        echo $entityform['table'];
    ?>
</div>
<table class="policy pricing-details table-striped">
    <tr>
      <td class="heading" colspan="3">
        <h2 class="mb5 text-light">Price Comparison Information</h2>
      </td>
    </tr>
    <tr>
      <td width="25%">
        <label id="label_entity">Select Insurance companies to add to quotation<span class="required">*</span></label>
      </td>
      <td width="60%" class="field">
        <?php
            echo $insurers;
        ?>
      </td>
      <td width="15%" class="field">
        <button type="button" class="btn btn-default addinsurer">Add Insurer</button>
      </td>
    </tr>
</table>
<div class="insurer-prices" style="display: table; width: 100%; clear: both;">
    <?php
        echo $pricing;
    ?>
</div>
<div class="row last pull-right">
    <?php 
    if($rawstatus == 'new' || $rawstatus == 'pending'){
        echo $btnsubmit;
    }
    ?>
</div>
<?php
echo '<script>'
    . "$(function() {            
            $('input[name=\"btnsubmit\"]').on('click',function(e){
            
                // stop default action
                e.preventDefault();
                var isFormValid = true;
                
                //process the entire form
                var qform = $('#quoteform').data('Zebra_Form');
                
                if(qform.validate()){
                
                    $('input.required').each(function(){
                        if ($.trim($(this).val()).length == 0){
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
                        qform.submit();
                    }
                }
            });
    });
    </script>";

echo Overlays::Modal(['id'=>'emailmodal']);
