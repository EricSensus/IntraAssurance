<input type="hidden" id="input_quote_id" value="<?= $quote['quote_id']; ?>"/>
<div>
    <?php
    foreach ($_quote as $quote) {
//    dump($quote);
//    exit;
        ?>
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th colspan="2">Company: <?= $quote->insurer->name; ?></th>
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
                <?php
                $tr = null;
                foreach ($quote->companions as $companion) {

                    $tr .= '<tr>';
                    $tr .= '<th colspan="2">Name: ' . $companion->name . '</th>';
                    $tr .= '</tr>';

                    $tr .= '<tr>';
                    $tr .= '<td>Chosen Cover Plan: </td>';
                    $tr .= '<td>' . $quote->cover . '</td>';
                    $tr .= '</tr>';

                    $tr .= '<tr>';
                    $tr .= '<td>Days of Travel:</td>';
                    $tr .= '<td>' . $quote->travel_days . ' day(s)</td>';
                    $tr .= '</tr>';

                    $tr .= '<tr>';
                    $tr .= '<td>Basic Premium: </td>';
                    $tr .= '<td>Ksh. ' . number_format($quote->basic_premium, 2) . '</td>';
                    $tr .= '</tr>';

                    $tr .= '<tr><td colspan="2">&nbsp;</td></tr>';
                }
                echo $tr;
                ?>
                <tr>
                    <td>Training Levy (0.2%)</td>
                    <td>Ksh. <?= $quote->training_levy; ?></td>
                </tr>
                <tr>
                    <td>P.H.C.F (0.25%)</td>
                    <td>Ksh. <?= $quote->policy_levy; ?></td>
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
        </div>
        <?php
    } ?>
</div>
