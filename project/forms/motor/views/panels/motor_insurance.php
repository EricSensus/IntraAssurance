<?php

use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;

?>
<div class="insuranceheader row">
    <div class="insurance col-md-4 col-sm-12 col-xs-12">
        <h2>Motor Insurance</h2>
    </div>
</div>
<table class="wizard-heading row hidden-print">
    <tbody>
    <tr>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?=

            Url::link('/motor/step/1') ?>"><h5>Stage 1</h5></a>
            <a href="<?= Url::link('/motor/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/motor/step/2') ?>"><h5>Stage 2</h5></a>
            <a href="<?= Url::link('/motor/step/2') ?>">
                <h6>Car Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/motor/step/3') ?>"><h5>Stage 3</h5></a>
            <a href="<?= Url::link('/motor/step/3') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active" href="<?= Url::link('/motor/step/4') ?>"><h5>Stage 4</h5></a>
            <a class="active" href="<?= Url::link('/motor/step/4') ?>">
                <h6>Quotation &amp; Payment</h6></a>
        </th>
    </tr>
    </tbody>
</table>

<?=Overlays::Modal(['id' => 'emailmodal']); ?>
<div class="btn-group btn-group-xs hidden-print">
    <a href="<?=Url::link('/ajax/customer/quote/emailquote'); ?>" class="btn btn-info" id="email-frontquote"
       data-toggle="modal" data-target="#emailmodal" title="Email Quote">
        <i class="fa fa-envelope fa-3x"> Email Quote</i>
    </a>
    <a href="<?=Url::link('/ajax/customer/quote/pdfquote'); ?>" class="btn btn-info" id="gen-pdf" data-toggle="tooltip" title="Save as PDF" data-placement="bottom">
        <i class="fa fa-file-pdf-o fa-3x"> Pdf</i>
    </a>
    <a class="btn btn-info" onclick="print();" data-toggle="tooltip" title="Print Quote" data-placement="bottom">
        <i class="fa fa-print fa-3x"> Print</i>
    </a>
</div>
<div class="formheading row">
    <div class="formheadingcaption col-md-12 col-sm-12 col-xs-12">
        <p>Below are your quotation details</p>

        <div class="col-md-12 row">
            <?php
            $count = 0;
            $quote_id = $data->quote->id;
            $data = $data->payments[0];
            ?>
            <div class="well">
                <dl class="dl-horizontal">
                    <dt>Product</dt>
                    <dd><?= $data->product->name ?>
                    </dd>
                    <dt>Car</dt>
                    <dd><?= $data->main_entity->regno ?></dd>
                </dl>
            </div>
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $data->insurer->name ?></h3>
                    </div>
                    <div class="panel-body">

                        <table class="table table-striped table-condensed">
                            <thead>
                            <tr>
                                <th colspan="2">Name: <?= $data->customer->name ?></th>
                            </tr>
                            </thead>
                            <tr>
                                <td>Total Sum Insured</td>
                                <td>Ksh <?= number_format($data->main->tsi, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Type of Cover</td>
                                <td><?= $data->main->cover_type ?></td>
                            </tr>
                            <tr>
                                <td><?= (isset($data->main->minimum) ? 'Minimum' : 'Basic') ?> premium</td>
                                <td>
                                    Ksh <?= number_format((isset($data->main->minimum) ? $data->main->minimum : $data->basic_premium), 2) ?></td>
                            </tr>
                            <tr>
                                <th colspan="2">Additional Covers</th>
                            </tr>
                            <tr>
                                <td>Windscreen (upto ksh 30000)</td>
                                <td>Ksh <?= number_format($data->main->windscreen, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Entertainment System (upto ksh 30000)</td>
                                <td>Ksh <?= number_format($data->main->audio, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Political Violence</td>
                                <td>Ksh <?= number_format($data->main->terrorism, 2) ?></td>
                            </tr>
                            <tr>
                                <td>SRCC (Strikes, Riotes and Civil Commotion)</td>
                                <td>Ksh <?= number_format($data->main->riotes, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Excess Protector</td>
                                <td>Ksh <?= number_format($data->main->excess_protector, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Loss of Use</td>
                                <td>Ksh <?= number_format($data->main->loss_of_use, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Net Premium</td>
                                <td>Ksh <?= number_format($data->main->net_premium, 2) ?></td>
                            </tr>
                            <?php
                            if (!empty($data->cars)):
                                foreach ($data->cars as $car):
                                    ?>
                                    <tr>
                                        <th colspan="2">
                                            <?= $car->reg ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>Total Sum Insured</td>
                                        <td>Ksh <?= number_format($car->tsi, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Type of Cover</td>
                                        <td><?= $data->cover_type ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= (isset($car->minimum) ? 'Minimum' : 'Basic') ?> premium</td>
                                        <td>
                                            Ksh <?= number_format((isset($car->minimum) ? $car->minimum : $car->basic_premium), 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>NCD Amount</td>
                                        <td>
                                            Ksh <?= empty($car->minimum) ? number_format($car->ncd_amount, 2) : '' ?></td>
                                    </tr>
                                    <tr>
                                        <td>Premium less NCD</td>
                                        <td>Ksh <?= number_format($car->basic_premium2, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Additional Covers</th>
                                    </tr>
                                    <tr>
                                        <td>Riots and Strikes</td>
                                        <td>Ksh <?= number_format($car->riotes, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Windscreen</td>
                                        <td>Ksh <?= number_format($car->windscreen, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Audio System</td>
                                        <td>Ksh <?= number_format($car->audio, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Passenger Liability</td>
                                        <td>Ksh <?= number_format($car->passenger, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Terrorism</td>
                                        <td>Ksh <?= number_format($car->terrorism, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Net Premium</td>
                                        <td>Ksh <?= number_format($car->net_premium, 2) ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                            <tr>
                                <th colspan="2">Other levies</th>
                            </tr>
                            <tr>
                                <td>Training Levy</td>
                                <td>Ksh <?= number_format($data->training_levy, 2) ?></td>
                            </tr>
                            <tr>
                                <td>P.H.C.F</td>
                                <td>Ksh <?= number_format($data->policy_levy, 2) ?></td>
                            </tr>
                            <tr>
                                <td>Stamp Duty</td>
                                <td>Ksh <?= number_format($data->stamp_duty, 2) ?></td>
                            </tr>
                            <tfoot>
                            <tr>
                                <th>Total Premium</th>
                                <th>Ksh <?= number_format($data->total, 2) ?></th>
                            </tr>
                            </tfoot>
                        </table>
                        <? include('proceed_to_policy.php'); ?>
                        <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>