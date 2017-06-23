<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;

$use = array_first($data->payments)->main_entity;?>
<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Domestic Insurance</h2>
    </div>
</div>
<table class="wizard-heading row">
    <tr class="heading row">
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/domestic/step/1') ?>"><h5>
                    Stage 1</h5></a>
            <a href="<?= Url::link('/domestic/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/domestic/step/2') ?>"><h5>
                    Stage 2</h5></a>
            <a href="<?= Url::link('/domestic/step/2') ?>">
                <h6>Property Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/domestic/step/3') ?>"><h5>
                    Stage 3</h5></a>
            <a href="<?= Url::link('/domestic/step/3') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active" href="<?= Url::link('/domestic/step/4') ?>"><h5>
                    Stage 4</h5></a>
            <a class="active" href="<?= Url::link('/domestic/step/4') ?>">
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
<div class="well">
    <p><strong>Plot:</strong> <?= $use->plotno; ?></p>
    <p><strong>Town:</strong> <?= $use->town; ?></p>
    <p><strong>Road:</strong> <?= $use->road; ?></p>
</div>
<div class="row setup-content" id="step-4">
    <div class="col-md-12">
        <?php

        foreach ($data_array as $data) {
            $quotation = $data;
            $count = 0;
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
                                <td> <?= $data->customer->name ?></td>
                            </tr>
                            <tr>
                                <td>Section A: Buildings (Kshs <?= number_format($quotation->tsi_a) ?>)</td>
                                <td>Ksh <?= number_format($quotation->section_a, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Section B: Contents (Kshs <?= number_format($quotation->tsi_b) ?>)</td>
                                <td>Ksh <?= number_format($quotation->section_b, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Section C: All Risks (Kshs <?= number_format($quotation->tsi_c) ?>)</td>
                                <td>Ksh <?= number_format($quotation->section_c, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Section D: Workmens compensation</td>
                                <td>Ksh <?= number_format($quotation->workmen, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Section E: Owners liability - extra cover
                                    (Kshs <?= $quotation->step3->owner_liabilty ?>)
                                </td>
                                <td>Ksh <?= number_format($quotation->owner_liability, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Section F: Occupier Liability - extra cover
                                    (Kshs <?= $quotation->step3->occupiers_liabilty ?>)
                                </td>
                                <td>Ksh <?= number_format($quotation->occupier_liability, 2) ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Gross Premium</th>
                                <th>Ksh <?= number_format($quotation->gross_premium, 2) ?></th>
                            </tr>
                            <tr>
                                <td>Training Levy (<?= $quotation->training_rate ?> )</td>
                                <td>Ksh <?= number_format($quotation->training_levy, 2) ?></td>
                            </tr>
                            <tr>
                                <td>P.H.C.F (<?= $quotation->levy_value ?> )</td>
                                <td>Ksh <?= number_format($quotation->policy_levy, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Stamp Duty</td>
                                <td>Ksh <?= number_format($quotation->stamp_duty, 2) ?></td>
                            </tr>
                            <tfoot>
                            <tr>
                                <th>Total Premium</th>
                                <th>Ksh <?= number_format($quotation->total, 2) ?></th>
                            </tr>
                            </tfoot>
                        </table>
                        <? include('proceed_to_policy.php'); ?>
                        <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
