<?php
use Jenga\App\Request\Url;

$pesa = $data->payments;
?>

<div class="insuranceheader row">
    <div class="insurance col-md-4 col-sm-12 col-xs-12">
        <h2>Motor Insurance</h2>
    </div>
</div>
<table class="wizard-heading row">
    <tbody>
    <tr>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/motor/step/1') ?>"><h5>Stage 1</h5></a>
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
<div class="formheading row">
    <div class="formheadingcaption col-md-12 col-sm-12 col-xs-12">
        <p>Below are your quotation details</p>
    </div>
</div>
<div class="panel-body" id="step-1">
    <div class="col-xs-12">
        <div class="col-md-12 well">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Name: <?= $pesa->customer->name ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-condensed">
                        <thead>
                        <tr>
                            <th colspan="2">Name: <?= $pesa->customer->name ?></th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Total Sum Insured</td>
                            <td>Ksh <?= number_format($pesa->main->tsi, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Type of Cover</td>
                            <td><?= $pesa->main->cover_type ?></td>
                        </tr>
                        <tr>
                            <td><?= (isset($pesa->main->minimum) ? 'Minimum' : 'Basic') ?> premium</td>
                            <td>
                                Ksh <?= number_format((isset($pesa->main->minimum) ? $pesa->main->minimum : $pesa->main->basic_premium), 2) ?></td>
                        </tr>
                        <tr>
                            <td>NCD Amount</td>
                            <td>
                                Ksh <?= empty($pesa->main->minimum) ? number_format($pesa->main->ncd_amount, 2) : '' ?></td>
                        </tr>
                        <tr>
                            <td>Premuim less NCD</td>
                            <td>Ksh <?= number_format($pesa->main->basic_premium2, 2) ?></td>
                        </tr>
                        <tr>
                            <th colspan="2">Additional Covers</th>
                        </tr>
                        <tr>
                            <td>Riots and Strikes</td>
                            <td>Ksh <?= number_format($pesa->main->riotes, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Windscreen</td>
                            <td>Ksh <?= number_format($pesa->main->windscreen, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Audio System</td>
                            <td>Ksh <?= number_format($pesa->main->audio, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Passenger Liability</td>
                            <td>Ksh <?= number_format($pesa->main->passenger, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Terrorism</td>
                            <td>Ksh <?= number_format($pesa->main->terrorism, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Net Premium</td>
                            <td>Ksh <?= number_format($pesa->main->net_premium, 2) ?></td>
                        </tr>
                        <?php
                        if (!empty($pesa->cars)):
                            foreach ($pesa->cars as $car):
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
                                    <td><?= $pesa->cover_type ?></td>
                                </tr>
                                <tr>
                                    <td><?= (isset($car->minimum) ? 'Minimum' : 'Basic') ?> premium</td>
                                    <td>
                                        Ksh <?= number_format((isset($car->minimum) ? $car->minimum : $car->basic_premium), 2) ?></td>
                                </tr>
                                <tr>
                                    <td>NCD Amount</td>
                                    <td>Ksh <?= empty($car->minimum) ? number_format($car->ncd_amount, 2) : '' ?></td>
                                </tr>
                                <tr>
                                    <td>Premuim less NCD</td>
                                    <td>Ksh <?= number_format($car->basic_premium2, 2) ?></td>
                                </tr>
                                <tr>
                                    <th colspan="2">Atditional Covers</th>
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
                            <td>Ksh <?= number_format($pesa->training_levy, 2) ?></td>
                        </tr>
                        <tr>
                            <td>P.H.C.F</td>
                            <td>Ksh <?= number_format($pesa->policy_levy, 2) ?></td>
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
                    <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>