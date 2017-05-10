<table width="100%" class="policy table-striped">
    <tr>
        <td class="heading" colspan="2">
            <h2 class="mb5 text-light">Update claim status</h2>
        </td>
    </tr>
    <tr>
        <td>
            Claim Number: # <?= $id ?>
        </td>
        <td>
            Status <?= $status ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>
            Message <?= $message ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="cell"> <?= $sendemail_yes ?></div>
            <div class="cell"> <?= $label['label'] ?></div>
        </td>
    </tr>
</table>
<div class="row last">
    <div class="cell"> <?= $btnsubmit ?></div>
</div>


