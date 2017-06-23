<?php
use Jenga\App\Request\Session;

?>

<!--    <div class="row">-->
<!--        <div class="col-md-12">-->
<!--            <div class="dash-head clearfix mt15 mb20 shadow">-->
<!--                <div class="left">-->
<!--                    <h4 class="mb5 text-light">Rates Manager</h4>-->
<!--                    <p class="small"><strong>Manage</strong> Rates</p>-->
<!--                </div>-->
<!--                <div class="right toolholder">-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <div class="row show-grid">
        <div class="col-md-12 panel">
            <div class="dash-head clearfix mt15 mb20">
                <?php
                if (Session::has('status')) {
                    $status = Session::get('status');
                    $message = Session::get('message');
                    ?>
                    <div class="alert alert-<?= $status; ?>">
                        <button class="close" data-dismiss="alert">&times;</button>
                        <strong><?= ($status == 'success') ? 'Success' : 'Failed'; ?></strong>
                        <?= $message; ?>
                    </div>
                <?php } ?>

                <?= $rates_table?>
            </div>

            <div class="dataTables_wrapper panel-footer">
                <div id="rates_footer">
                </div>
            </div>
        </div>
    </div>
<?php
echo $editmodal;