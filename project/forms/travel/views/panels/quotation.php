<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th colspan="2">Name: <?=$quote['name']; ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Chosen Cover Plan:</td>
            <td><?=$quote['cover_plan']; ?></td>
        </tr>
        <tr>
            <td>Days of Travel:</td>
            <td><?=$quote['days_of_travel']; ?> day(s)</td>
        </tr>
        <tr>
            <td>Basic Premium:</td>
            <td>Ksh. <?=number_format($quote['basic_premium'], 2); ?></td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <?=$companions; ?>
        <tr>
            <td>Training Levy (0.2%)</td>
            <td>Ksh. <?=$quote['training_levy']; ?></td>
        </tr>
        <tr>
            <td>P.H.C.F (0.25%)</td>
            <td>Ksh. <?=$quote['phcf']; ?></td>
        </tr>
        <tr>
            <td>Stamp Duty (0.5)</td>
            <td>Ksh. <?=$quote['stamp_duty']; ?></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th>Full Total</th>
            <th>Ksh. <?=number_format($quote['full_total'],2); ?></th>
        </tr>
    </tfoot>
</table>