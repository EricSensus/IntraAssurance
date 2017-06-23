<?php
use Jenga\App\Request\Url;

$quotation = $data->quotation;
//ss($quotation);
?>
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
<div class="row setup-content" id="step-4">
    <div class="col-xs-12">
        <div class="col-md-12 well">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Name: <?= $quotation->customer->name ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-condensed">
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
                    <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
