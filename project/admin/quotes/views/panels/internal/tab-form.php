<?php

use Jenga\App\Views\HTML;
?>
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
<script src='<?= RELATIVE_PROJECT_PATH . "/admin/quotes/assets/js/quote-internal.js" ?>'></script>