<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;
?>
<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Personal Accident Insurance</h2>
    </div>
</div>
<table class="wizard-heading row">
    <tr class="heading row">
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/1') ?>"><h5>
                    Stage 1</h5></a>
            <a href="<?= Url::link('/accident/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/2') ?>"><h5>
                    Stage 2</h5></a>
            <a href="<?= Url::link('/accident/step/2') ?>">
                <h6>Personal Accident Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/3') ?>"><h5>
                    Stage 3</h5></a>
            <a href="<?= Url::link('/accident/step/3') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active" href="<?= Url::link('/accident/step/4') ?>"><h5>
                    Stage 4</h5></a>
            <a class="active" href="<?= Url::link('/accident/step/4') ?>">
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
<div class="row setup-content" id="step-4">
    <div class="col-md-12">
        <?php

        foreach ($data_array as $data) {
            $pesa = $data;
            $quote_id = $data->quote->id;
            ?>
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Insurer Name: <?= $data->insurer->name ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-condensed">
                            <tr>
                                <td>Name:</td>
                                <td> <?= $pesa->customer->name ?></td>
                            </tr>
                            <tr>
                                <td>Basic Premium</td>
                                <td>Ksh <?= number_format($pesa->premium_rate, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Training levy (<?= $pesa->levy_rate ?>)</td>
                                <td>Ksh <?= number_format($pesa->training_levy, 2) ?></td>
                            </tr>
                            <?php
                            if (!empty($pesa->others)):
                                foreach ($pesa->others as $item):
                                    ?>
                                    <tr>
                                        <th colspan="2">
                                            <?= $item->name ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Basic Premium</td>
                                        <td>Ksh <?= number_format($item->premium_rate, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Training levy (<?= $pesa->levy_rate ?>)</td>
                                        <td>Ksh <?= number_format($item->training_levy, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Sub Total</td>
                                        <td>Ksh <?= number_format($item->basic_premium, 2) ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                            <tr>
                                <th colspan="2">Other levies</th>
                            </tr>
                            <tr>
                                <td>P.H.C.F</td>
                                <td>Ksh <?= number_format($pesa->policy_fund, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Stamp Duty</td>
                                <td>Ksh <?= number_format($pesa->stamp_duty, 2) ?></td>
                            </tr>
                            <tfoot>
                            <tr>
                                <th>Total Premium</th>
                                <th>Ksh <?= number_format($pesa->total, 2) ?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        } ?>
    </div>
</div>

