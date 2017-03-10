<?php
$pesa = $data['quote'];
$count = 0;
?>
<table class="table table-striped table-condensed">
    <tbody>
    <?php
    if (!empty($pesa->properties)):
        foreach ($pesa->properties as $item):
            ?>
            <tr>
                <td>Section A: Buildings (Kshs <?= number_format($item->tsi_a) ?>)</td>
                <td>Ksh <?= number_format($item->section_a, 2) ?></td>
            </tr>
            <tr>
                <td>Section B: Contents (Kshs <?= number_format($item->tsi_b) ?>)</td>
                <td>Ksh <?= number_format($item->section_b, 2) ?></td>
            </tr>
            <tr>
                <td>Section C: All Risks (Kshs <?= number_format($item->tsi_c) ?>)</td>
                <td>Ksh <?= number_format($item->section_c, 2) ?></td>
            </tr>
            <tr>
                <td>Section D: Workmens compensation</td>
                <td>Ksh <?= number_format($item->workmen, 2) ?></td>
            </tr>
            <tr>
                <td>Section E: Owners liability - extra cover
                    (Kshs <?= $item->owner_liabilty ?>)
                </td>
                <td>Ksh <?= number_format($item->owner_liability, 2) ?></td>
            </tr>
            <tr>
                <td>Section F: Occupier Liability - extra cover
                    (Kshs <?= $item->occupiers_liabilty ?>)
                </td>
                <td>Ksh <?= number_format($item->occupier_liability, 2) ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
            <?php
        endforeach;
    endif;
    ?>
    <tr>
        <th>Gross Premium</th>
        <th>Ksh <?= number_format($pesa->gross_premium, 2) ?></th>
    </tr>
    <tr>
        <td>Training Levy (<?= $pesa->training_rate ?> )</td>
        <td>Ksh <?= number_format($pesa->training_levy, 2) ?></td>
    </tr>
    <tr>
        <td>P.H.C.F (<?= $pesa->levy_value ?> )</td>
        <td>Ksh <?= number_format($quotation->policy_levy, 2) ?></td>
    </tr>
    <tr>
        <td>Stamp Duty</td>
        <td>Ksh <?= number_format($pesa->stamp_duty, 2) ?></td>
    </tr>
    </tbody>
    <tfoot>
    <tr>
        <th>Total Premium</th>
        <th>Ksh <?= number_format($pesa->total, 2) ?></th>
    </tr>
    </tfoot>
</table>
<input type="hidden" value='<?= $data['total'] ?>' name="my_total_see"/>