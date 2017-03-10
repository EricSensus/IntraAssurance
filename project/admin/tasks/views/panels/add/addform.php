<?php
use Jenga\App\Views\HTML;

echo '<link rel="stylesheet" href="'. RELATIVE_PROJECT_PATH .'/tools/Autocomplete-master/jquery.autocomplete.css">';
echo '<script src="'. RELATIVE_PROJECT_PATH .'/tools/Autocomplete-master/jquery.autocomplete.js"></script>';

// don't forget about this for custom templates, or errors will not show for server-side validation
// $zf_error is automatically created by the library and it holds messages about SPAM or CSRF errors
// $error is the name of the variable used with the set_rule method
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));

HTML::script("$(function(){"
        . "//process customer details
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
                }
            });"
        . "});")
?>
<table class="table">
    <thead>
        <tr>
            <td colspan="2">
                <p>Enter the new task details below:</p>
            </td>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td width="25%">
            <?php 
                echo '<strong>' .$label_customer .'</strong>';
            ?>
        </td>
        <td class="field" width="75%">
            <?php
                echo '<div class="data">'. $customer.'</div>';
            ?>
            <span style="font-size: 11px; color: red">Type to search for customer</span>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>' .$label_agent.'</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$agent.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>' .$label_dategen.'</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$dategen.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>' .$label_tasktype.'</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$tasktype.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>' .$label_subject.'</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$subject.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>' .$label_description.'</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$description.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>' .$label_priority.'</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$priority.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>' .$label_remainder.'</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$remainder.'</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php 
                echo '<strong>Mark as Complete</strong>';
            ?>
        </td>
        <td class="field">
            <?php 
                echo '<div class="data">'.$completed_yes.'</div>';
            ?>
        </td>
    </tr>
    </tbody>
</table>

