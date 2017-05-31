<?php
use Jenga\App\Request\Url;

$url = Url::route('/admin/claims/upload/{id}',
    ['id' => $id]);
?>
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
        <td></td>
        <td><a data-target="#adddocument" href="<?= $url; ?>" data-backdrop="static" data-toggle="modal"
               class="btn btn-info">
                <i class="fa fa-file-o"></i> Upload Document</a></td>
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


