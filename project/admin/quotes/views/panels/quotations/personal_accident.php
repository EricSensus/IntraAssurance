<div class="col-md-12">
    <?php

    foreach ($data_array as $data) {
        $pesa = $data;
        $quote_id = $data->quote->id;
        ?>
        <div class="col-md-5 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Name: <?= $pesa->customer->name ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-condensed">
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
                                    <td>Ksh <?= number_format($item->total, 2) ?></td>
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
                    <? include('proceed_to_policy.php'); ?>
                </div>
            </div>
        </div>
        <?php
    } ?>
</div>

