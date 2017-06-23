<?php

use Jenga\App\Views\HTML;

?>
<div class="row">
    <div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light"><?= $product ?> Quote for <strong><?= $customer_data['name'] ?></strong></h4>
                <p class="small"><strong>New quote</strong> Generating new <?= strtolower($product) ?> quotation</p>
            </div>
            <div class="right toolholder">
                <button type="button" class="btn btn-primary" id="prev"><i class="fa fa-arrow-left"></i> Previous
                </button>
                <button type="button" class="btn btn-primary" id="next"><i class="fa fa-arrow-right"></i> Next</button>
                <button type="button" class="btn btn-success" id="btnSubmit">Generate Quote</button>
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

<script>
    var SITE_PATH = "<?= SITE_PATH ?>";
    var PRELOADER1 = '<?= HTML::AddPreloader() ?>';
    var PRELOADER2 = '<?= HTML::AddPreloader('left', '40px', '40px') ?>';
    var customer_data = <?= json_encode($customer_data) ?>;
</script>
<script src='<?= RELATIVE_PROJECT_PATH . '/admin/quotes/assets/js/quote-internal.min.js' ?>'></script>