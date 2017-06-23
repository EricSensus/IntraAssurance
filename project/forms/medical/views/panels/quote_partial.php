<input type="hidden" id="input_quote_id" value="<?= $quote['quote_id']; ?>"/>
<div>
    <?php
    foreach ($_quote as $quote) {
//        dump($quote);
//        exit;
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
                    <td>Core Plan Premium</td>
                    <td>Ksh. <?= number_format($quote->core_premium, 2); ?></td>
                </tr>
                <tr>
                    <td>Optional Benefits Premium</td>
                    <td>Ksh. <?= number_format($quote->optional_total, 2); ?></td>
                </tr>
                <?= $dependants; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td>Training levy (0.2%)</td>
                    <td>Ksh. <?= number_format($quote->training_levy, 2); ?></td>
                </tr>
                <tr>
                    <td>P.H.C.F (0.25%)</td>
                    <td>Ksh. <?= number_format($quote->policy_levy, 2); ?></td>
                </tr>
                <tr>
                    <td>Stamp Duty (40)</td>
                    <td>Ksh. <?= number_format($quote->stamp_duty, 2); ?></td>
                </tr>
                <tr class="active">
                    <th>Total</th>
                    <th>Ksh. <?= number_format($quote->total, 2); ?></th>
                </tr>
                </tfoot>
            </table>


        </div> <?php
    } ?>
</div>