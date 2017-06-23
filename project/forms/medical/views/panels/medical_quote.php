<table class="table table-condensed table-striped table-bordered">
    <thead>
        <tr>
            <th colspan="2">Name: <?=$quote['name']; ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Core Plan Premium</td>
            <td>Ksh. <?=number_format($quote['core_premium'],2); ?></td>
        </tr>
        <tr>
            <td>Optional Benefits Premuim</td>
            <td>Ksh. <?=number_format($quote['core_optional_benefits'],2); ?></td>
        </tr>
        <?=$dependants; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Training levy (0.2%)</td>
            <td>Ksh. <?=number_format($quote['levy'],2); ?></td>
        </tr>
        <tr>
            <td>P.H.C.F (0.25%)</td>
            <td>Ksh. <?=number_format($quote['phcf'],2); ?></td>
        </tr>
        <tr>
            <td>Stamp Duty (40)</td>
            <td>Ksh. <?=number_format($quote['stamp_duty'],2); ?></td>
        </tr>
        <tr class="active">
            <th>Total</th>
            <th>Ksh. <?=number_format($quote['grand_total'],2); ?></th>
        </tr>
    </tfoot>
</table>