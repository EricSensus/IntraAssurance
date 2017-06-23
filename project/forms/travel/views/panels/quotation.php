<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;

?>
<div class="insuranceheader row hidden-print">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Travel Insurance</h2>
    </div>
</div>

<table class="wizard-heading row hidden-print">
    <tr class="heading row">
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/travel/step/1') ?>"><h5>
                    Stage 1</h5></a>
            <a href="<?= Url::link('/travel/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/travel/step/2') ?>"><h5>
                    Stage 2</h5></a>
            <a href="<?= Url::link('/travel/step/2') ?>">
                <h6>Travel Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/travel/step/3') ?>"><h5>
                    Stage 3</h5></a>
            <a href="<?= Url::link('/travel/step/3') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active" href="<?= Url::link('/travel/step/4') ?>"><h5>
                    Stage 4</h5></a>
            <a class="active" href="<?= Url::link('/travel/step/4') ?>">
                <h6>Quotation & Payment</h6></a>
        </th>
    </tr>
</table>


<?= Overlays::Modal(['id' => 'emailmodal']); ?>
<div id="quote-toolbar" class="row">
    <div class="btn-group btn-group-xs hidden-print">
        <a href="<?= Url::link('/ajax/customer/quote/emailquote'); ?>"  id="email-frontquote"
           data-toggle="modal" data-target="#emailmodal" title="Email Quote">
            <button class="btn btn-default">
                <i class="fa fa-envelope-o fa-2x"></i><br/> Email
            </button>
        </a>
        <a href="<?= Url::link('/ajax/customer/quote/pdfquote'); ?>" target="_blank" id="gen-pdf"
           data-toggle="tooltip" title="Save as PDF" data-placement="bottom">
            <button class="btn btn-default">
                <i class="fa fa-file-pdf-o fa-2x"></i><br/> PDF
            </button>
        </a>
        <a onclick="print();" data-toggle="tooltip" title="Print Quote" data-placement="bottom">
            <button class="btn btn-default" >
                <i class="fa fa-print fa-2x"></i><br/> Print
            </button>
        </a>
    </div>
</div>
<input type="hidden" id="input_quote_id" value="<?= $quote->quote_id ?>"/>
<div class="row">
    <?php
    foreach ($_quote as $quote) {
        ?>
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th colspan="2">Name: <?= $quote->insurer->name; ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="2">Name: <?= $quote->customer->name; ?></td>
                </tr>
                <tr>
                    <td>Chosen Cover Plan:</td>
                    <td><?= $quote->cover ?></td>
                </tr>
                <tr>
                    <td>Days of Travel:</td>
                    <td><?= $quote->travel_days ?> day(s)</td>
                </tr>
                <tr>
                    <td>Basic Premium:</td>
                    <td>Ksh. <?= number_format($quote->basic_premium, 2); ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <?= $companions; ?>
                <tr>
                    <td>Training Levy (0.2%)</td>
                    <td>Ksh. <?= $quote->training_levy ?></td>
                </tr>
                <tr>
                    <td>P.H.C.F (0.25%)</td>
                    <td>Ksh. <?= $quote->policy_levy ?></td>
                </tr>
                <tr>
                    <td>Stamp Duty (0.5)</td>
                    <td>Ksh. <?= $quote->stamp_duty ?></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th>Full Total</th>
                    <th>Ksh. <?= number_format($quote->total, 2); ?></th>
                </tr>
                </tfoot>
            </table>

            <script>
                $(document).ready(function () {
                    // initialize bootstrap tooltips
                    $('[data-toggle="tooltip"]').tooltip();

                    // email frontquote
//        $('#email-frontquote').on('click', function (e) {
//            e.preventDefault();

//            // show email modal
//            $('#emailmodal').modal('show');
//        });
                });
            </script>
        </div>
    <?php } ?>
</div>
