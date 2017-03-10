<?php
$pesa = $data['quote'];
$count = 0;
?>
<table class="policy pricing-details table-striped table-condensed">
    <tr>
        <td class="heading" colspan="2">
            <h2 class="mb5 text-light">Quotation Preview</h2>
        </td>
    </tr>
    <?php
    if (!empty($pesa->cars)):
        foreach ($pesa->cars as $car):
            ?>
            <tr>
                <th colspan="2">
                    Car <?= ++$count ?> : <?= $car->reg ?>
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
                <th colspan="2">Additional Covers</th>
            </tr>
            <tr class="mysmall">
                <td>Riots and Strikes</td>
                <td>Ksh <?= number_format($car->riotes, 2) ?></td>
            </tr>
            <tr class="mysmall">
                <td>Windscreen</td>
                <td>Ksh <?= number_format($car->windscreen, 2) ?></td>
            </tr>
            <tr class="mysmall">
                <td>Audio System</td>
                <td>Ksh <?= number_format($car->audio, 2) ?></td>
            </tr>
            <tr class="mysmall">
                <td>Passenger Liability</td>
                <td>Ksh <?= number_format($car->passenger, 2) ?></td>
            </tr>
            <tr class="mysmall">
                <td>Terrorism</td>
                <td>Ksh <?= number_format($car->terrorism, 2) ?></td>
            </tr>
            <tr class="mysmall">
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
<style>
    .mysmall td:first-child {
        padding-left: 20px;
    }
</style>
<input type="hidden" value='<?= $data['total'] ?>' name="my_total_see"/>