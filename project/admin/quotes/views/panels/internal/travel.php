<?php
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;
use Jenga\App\Request\Session;

?>
    <div class="row">
        <div class="col-md-12">
            <div class="dash-head clearfix mt15 mb20 shadow">
                <div class="left">
                    <h4 class="mb5 text-light"><?= $product ?> Quote for <strong><?= $customer_data['name'] ?></strong>
                    </h4>
                    <p class="small"><strong>New quote</strong> Generating new <?= strtolower($product) ?> quotation</p>
                </div>
                <div class="right toolholder">
                    <button type="button" class="btn btn-primary" id="prev"><i class="fa fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn btn-primary" id="next"><i class="fa fa-arrow-right"></i> Next
                    </button>
                    <button type="button" class="btn btn-success" id="btnSubmit">Generate Quote</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="row margin-top-10">
            <div class="col-md-12">
                <div class="dash-head clearfix mt15 mb20 shadow">
                    <div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1" data-toggle="tab">Proposer Personal Details</a></li>
                            <li><a href="#tab2" data-toggle="tab">Cover Details</a></li>
                            <li><a href="#tab3" data-toggle="tab">More Details</a></li>
                            <li class="tab4 hide"><a href="#tab4" data-toggle="tab">Quote</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab1">
                                <?= $tab1; ?>
                            </div>
                            <div class="tab-pane fade" id="tab2">
                                <?= $tab2; ?>
                            </div>
                            <div class="tab-pane fade" id="tab3">
                                <?= $tab3; ?>
                            </div>
                            <div class="tab-pane fade hide tab4" id="tab4">
                                <div class="panel">
                                    <div class="panel-title">Travel Insurance</div>
                                    <div class="panel-body travel"></div>
                                    <div>
                                        <?php
                                        if (isset($_POST['policy'])) {
                                            ?>
                                            <a href="" data-toggle="modal" data-target="#confirmquotemodal"
                                               id="proceed_with_policy" class="btn btn-success pull-right">
                                                Continue with Policy creation <i class="fa fa-arrow-right"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
if (isset($_POST['policy'])) {
    $confirm_quote_modal = [
        'formid' => 'confirmquoteform',
        'id' => 'confirmquotemodal',
        'role' => 'dialog',
        'title' => 'Confirm Quote',
        'buttons' => [
            'Cancel' => [
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal'
            ],
            'Attach' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'id' => 'save_button'
            ]
        ]
    ];
    echo Overlays::Modal($confirm_quote_modal);
}
?>
    <span class="hide" id="preloader"><?= HTML::AddPreloader(); ?></span>
    <input type="hidden" id="site_path" value="<?= SITE_PATH; ?>"/>

    <script>
        var customer_data = <?= json_encode($customer_data)?>;
        <?php
        if(isset($product_info)){
        ?>
        var product_info = <?= $product_info; ?>;
        var entity_data = <?= json_encode($entity_data_arr); ?>;
        <?php } ?>
    </script>
    <script src="<?= RELATIVE_PROJECT_PATH . '/forms/travel/assets/js/common.js'; ?>"></script>
    <script src="<?= RELATIVE_PROJECT_PATH . '/admin/quotes/assets/js/custom-travel-quote.min.js'; ?>"></script>

<?php if (!isset($_POST['product'])) { ?>
    <script>
        // swith to the quote tab
        Travel.switchToQuote();

        var site_path = $('#site_path').val();
        Travel.generateQuote(site_path);
    </script>
<?php } ?>