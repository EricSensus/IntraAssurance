<?php

use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;

?>
<div class="row">
    <div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light">Edit Quote #<?= sprintf("%'.05d\n", $customer_data['quote_id']) ?></h4>
                <p class="small"><strong>Product</strong> <?= ucfirst($customer_data['product']) ?></p>
            </div>
            <div class="right toolholder">
                <?php
                if ($_quote->status != 'Accepted') {
                    ?>
                    <a data-toggle="modal" data-target="#confirmquotemodal" class="btn btn-default"
                       href="<?= SITE_PATH . '/ajax/admin/quotes/internalacceptquote/' . $_quote->id ?>"> Mark Customer
                        Response</a>
                    <?php
                } ?>
                <a href="<?= Url::link('/admin/quotes') ?>" class="btn btn-warning">Cancel</a>
                <button type="button" name="btnSubmit" class="button btn btn-success">Save Quote</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="row margin-top-10">
        <div class="col-md-12">
            <form method="post" id="mainform">
                <input type="hidden" name="quote_id" id="quote_id"/>
                <div class="dash-head clearfix mt15 mb20 shadow">
                    <div>
                        <?= $form ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= Overlays::Modal(['id' => 'confirmquotemodal', 'size' => 'large']); ?>
<script>
    var SITE_PATH = "<?= SITE_PATH ?>";
    var PRELOADER1 = '<?= HTML::AddPreloader() ?>';
    var PRELOADER2 = '<?= HTML::AddPreloader('left', '40px', '40px') ?>';
    var customer_data = <?= json_encode($customer_data) ?>;
</script>
<script src='<?= RELATIVE_PROJECT_PATH . "/admin/quotes/assets/js/quote-internal-edit.min.js" ?>'></script>