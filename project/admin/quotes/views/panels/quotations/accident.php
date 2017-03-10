<?php
$pesa = $data['quote'];
$count = 0;
?>
<table class="table table-striped table-condensed">
    <?php
    if (!empty($pesa->people)):
        foreach ($pesa->people as $item):
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
                <td>Ksh <?= number_format($item->levy, 2) ?></td>
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
<input type="hidden" value='<?= $data['total'] ?>' name="my_total_see"/>