<?php
use Jenga\App\Views\HTML;

?>
<div class="row">
    <div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light">Add New Insurance Quote</h4>
                <p class="small"><strong>Add</strong> New Quote</p>
            </div>
        </div>
    </div>
    <div class="row margin-top-10">
        <div class="col-md-12">
            <div class="dash-head clearfix mt15 mb20 shadow">
                <?= $quoteadd ?>
            </div>
        </div>
    </div>
</div>
<script>
    var SITE_PATH = "<?= SITE_PATH ?>";
    var PRELOADER1 = '<?= HTML::AddPreloader()?>';
    var PRELOADER2 = '<?= HTML::AddPreloader('left', '40px', '40px')?>';
</script>