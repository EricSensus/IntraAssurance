<?php
//    dump(get_defined_vars());exit;
?>
<table class="policy table-striped">
    <tr>
        <td class="heading" colspan="2"><h2 class="mb5 text-light">Reports</h2></td>
    </tr>
    <tr>
        <td><h4><?=$label_report_type; ?></h4></td>
        <td class="field"><h4><?=$report_type; ?></h4></td>
    </tr>
    <tr>
        <td width="25%"><?='<h4>' . $label_from_date . '</h4>'; ?></td>
        <td class="field" width="75%"><?='<div class="data">' . $from_date . '</div>'; ?></td>
    </tr>
    <tr>
        <td><?='<h4>' . $label_to_date . '</h4>'; ?></td>
        <td class="field"><?='<div class="data">' . $to_date . '</div>'; ?></td>
    </tr>
</table>

<div class="buttons">
    <div class="pull-right"><?=$btnsubmit; ?></div>
</div>