<?php
use Jenga\App\Views\HTML;

HTML::head(TRUE, TRUE);
HTML::css('admin/css/admin_css.css', FALSE, TRUE);
HTML::css('preview/css/preview.css', FALSE, TRUE);
?>
<h2>Insurance Quotation No. <?php echo sprintf("%'.05d\n", $quote->id) ?>
    dated <?php echo date('d F Y', $quote->datetime) ?></h2>
<hr class="greenborder"/>
<p><strong>Dear <?php echo ucwords(strtolower($customer->customer)) ?>,</strong></p>
<p>Please confirm the proposal information detailed below for you by <strong><?php echo $own_company->name ?></strong>
</p>
<hr/>
<h4><strong>Product: <?php echo $product_name ?></strong></h4>
<hr/>
<?php
echo $product_details_list;
echo $recommendations;
?>
<p><strong><?php echo $agent->names ?></strong> recommends the package offered by
    <strong><?php echo $recom_company ?></strong></p>
<hr/>
<div class="pull-right" style="text-align: right">
    <p>
        <small><strong><?php echo $own_company->name ?></strong></small>
        <br/>
        <small><strong>Email: </strong> <?php echo $own_company->email_address ?></small>
        <br/>
        <strong>Telephone: </strong><?php echo $own_company->telephone ?></small><br/>
        <small><strong>Location: </strong><?php echo $location ?></small>
        <br/>
        <small><strong>Postal Address: </strong><?php echo $own_company->postal_address ?></small>
    </p>
</div>