<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

// don't forget about this for custom templates, or errors will not show for server-side validation
// $zf_error is automatically created by the library and it holds messages about SPAM or CSRF errors
// $error is the name of the variable used with the set_rule method
echo (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));

$url = Elements::call('Navigation/NavigationController')->getUrl('setup');

HTML::script("$(document).ready(function () {"
                . "$('#addformfield').on('hidden.bs.modal', function () {
                        window.location.reload();
                    });"
                . "$('#editformfield').on('hidden.bs.modal', function () {
                        window.location.reload();
                    });
                    $('.reset').on('click', function(event) {
                        window.location.href='".Url::link($url)."#products';
                    });
                });");

echo '<table width="100%" border="0" cellpadding="10" class="policy product table-striped">
        <tr>
            <td colspan="2" class="heading">
            <h2 class="mb5 text-light">Product Overview</h2></td>
        </tr>
        <tr>
            <td width="20%">
                <p><strong>Product Name:</strong></p>
            </td>
            <td width="80%">
                '.$name.'
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>Alias:</strong></p>
            </td>
            <td>
                '.$alias.'
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>Product Type:</strong></p>
            </td>
            <td>
                '.$producttype.'
            </td>
        </tr>
      </table>';

echo '<div class="greenheading"><h2 class="mb5 text-light">Product Form Fields</h2>'
        . '<a href="'.Url::base().'/ajax/admin/products/createproductfield/'.$productalias.'" data-toggle="modal" data-backdrop="static" data-target="#addformfield" >'
        . '<span '.Notifications::tooltip('Click to add product field').' class="glyphicon glyphicon-plus pull-right" aria-hidden="true" ></span>'
        . '</a>'
    . '</div>';

echo Overlays::Modal(['id'=>'addformfield','title'=>'Add Product Form Field']);

echo $productfields;

echo '<table width="100%" border="0" cellpadding="10" class="policy product table-striped">
        <tr>
            <td colspan="2" class="heading">
            <h2 class="mb5 text-light">* Linked Entities</h2></td>
        </tr>
        <tr>
            <td width="20%">
                <p><strong>Entity Type lnked to Product *</strong></p>
            </td>
            <td width="80%">
                '.$entitytype.'
            </td>
        </tr>
        <tr>
            <td>
                <p><strong>Allow multiple entities under one product? *</strong></p>
            </td>
            <td>
                '.$multiple_entities.'
            </td>
        </tr>
      </table>';

echo '<table cellspacing="0" cellpadding="0">'
        . '<tr class="toprow">'
            . '<td width="50%" align="left">'.$btnreset.'</td>'
            . '<td width="50%" align="right">'.$btnsubmit.'</td> '
        . '</tr>';
echo '</table>';