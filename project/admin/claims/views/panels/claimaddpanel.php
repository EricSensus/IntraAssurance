<?php
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;
use Jenga\App\Request\Url;

?>
<div class="row">
    <div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light">File a new Insurance Claim</h4>
                <p class="small"><strong>File</strong> New Claim</p>
            </div>
        </div>
    </div>
</div>

<div class="row margin-top-10">
    <div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <!--Steps-->
            <div class="col-xs-12">
                <?php
                if (isset($current_step)) {
                    $url = Url::route('/admin/claims/upload/{id}',
                        ['id' => $id]);
                    ?>
                    <div class="panel-heading">
                        <h4 class="mb5 text-light" style="width: auto; float:left">Claim Timeline</h4>
                        <div class="toolicon" style="width: auto; float:right">
                            <a data-target="#adddocument" href="<?= $url; ?>" data-backdrop="static" data-toggle="modal"
                               class="new-entity add toolsbutton">
                                <img src="<?= RELATIVE_PROJECT_PATH ?>/templates/admin/images/icons/small/add_icon.png">
                            </a>
                        </div>
                    </div>
                <?php }
                echo $claim_add;
                if (!empty($show_timeline)) {
                    include_once $show_timeline;
                }
                echo $uploadform;
                if (isset($current_step)) {
                    ?>
                    <p></p>
                    <a href="<?= Url::link('/admin/claims/closepolicy/' . $id); ?>"
                       class="btn btn-success pull-right">
                        Proceed to close claim >
                    </a>
                <?php } ?>
            </div>
            <div class="col-md-12" id="embedded_quote"></div>
            <div class="col-md-12" id="policy_preview"></div>
            <div class="col-md-12" id="claim-details">
                <?= $claim_form ?>
            </div>
        </div>
    </div>
</div>

<?= Overlays::confirm(); ?>

<script>
    var SITE_PATH = "<?= SITE_PATH ?>";
    var PRELOADER1 = '<?= HTML::AddPreloader()?>';
    var PRELOADER2 = '<?= HTML::AddPreloader('left', '40px', '40px')?>';
</script>
