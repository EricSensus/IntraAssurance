<?php
use Jenga\App\Request\Url;

$pesa = $data->payments;
//show($pesa);
?>

<div class="row form-group">
    <div class="col-xs-12">
        <ul class="nav nav-pills nav-justified thumbnail setup-panel">
            <li class="disabled"><a href="<?= Url::link('motor/step') ?>">
                    <h4 class="list-group-item-heading">Step 1</h4>
                    <p class="list-group-item-text">Proper personal details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('motor/step/2') ?>">
                    <h4 class="list-group-item-heading">Step 2</h4>
                    <p class="list-group-item-text">Car Details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('motor/step/3') ?>">
                    <h4 class="list-group-item-heading">Step 3</h4>
                    <p class="list-group-item-text">Cover Details</p>
                </a>
            </li>
            <li class="active"><a href="<?= Url::link('motor/step/4') ?>">
                    <h4 class="list-group-item-heading">Step 4</h4>
                    <p class="list-group-item-text">Quotation and Payment</p>
                </a>
            </li>
        </ul>
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
                            <td>Ksh <?= number_format($pesa->tsi, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Type of Cover</td>
                            <td><?= $pesa->cover_type ?></td>
                        </tr>
                        <tr>
                            <td><?= (isset($pesa->minimum) ? 'Minimum' : 'Basic') ?> premium</td>
                            <td>
                                Ksh <?= number_format((isset($pesa->minimum) ? $pesa->minimum : $pesa->basic_premium), 2) ?></td>
                        </tr>
                        <tr>
                            <td>NCD Amount</td>
                            <td>Ksh <?= empty($pesa->minimum) ? number_format($pesa->ncd_amount, 2) : '' ?></td>
                        </tr>
                        <tr>
                            <td>Premuim less NCD</td>
                            <td>Ksh <?= number_format($pesa->basic_premium2, 2) ?></td>
                        </tr>
                        <tr>
                            <th colspan="2">Additional Covers</th>
                        </tr>
                        <tr>
                            <td>Riots and Strikes</td>
                            <td>Ksh <?= number_format($pesa->riotes, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Windscreen</td>
                            <td>Ksh <?= number_format($pesa->windscreen, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Audio System</td>
                            <td>Ksh <?= number_format($pesa->audio, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Passenger Liability</td>
                            <td>Ksh <?= number_format($pesa->passenger, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Terrorism</td>
                            <td>Ksh <?= number_format($pesa->terrorism, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Net Premium</td>
                            <td>Ksh <?= number_format($pesa->net_premium, 2) ?></td>
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