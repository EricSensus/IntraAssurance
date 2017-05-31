<div class="col-md-12">
    <?php

    foreach ($data_array as $data) {

        $count = 0;
        $quote_id = $data->quote->id;
        ?>
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
                                Ksh <?= number_format((isset($data->main->minimum) ? $data->main->minimum : $data->main->basic_premium), 2) ?></td>
                        </tr>
                        <tr>
                            <td>NCD Amount</td>
                            <td>
                                Ksh <?= empty($data->main->minimum) ? number_format($data->main->ncd_amount, 2) : '' ?></td>
                        </tr>
                        <tr>
                            <td>Premium less NCD</td>
                            <td>Ksh <?= number_format($data->main->basic_premium2, 2) ?></td>
                        </tr>
                        <tr>
                            <th colspan="2">Additional Covers</th>
                        </tr>
                        <tr>
                            <td>Riots and Strikes</td>
                            <td>Ksh <?= number_format($data->main->riotes, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Windscreen</td>
                            <td>Ksh <?= number_format($data->main->windscreen, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Audio System</td>
                            <td>Ksh <?= number_format($data->main->audio, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Passenger Liability</td>
                            <td>Ksh <?= number_format($data->main->passenger, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Terrorism</td>
                            <td>Ksh <?= number_format($data->main->terrorism, 2) ?></td>
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
                                    <td>Ksh <?= empty($car->minimum) ? number_format($car->ncd_amount, 2) : '' ?></td>
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
        <?php
    }
    ?>
</div>
