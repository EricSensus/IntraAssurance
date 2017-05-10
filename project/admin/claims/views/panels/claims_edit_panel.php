<?php
use Jenga\App\Request\Url;

echo '<div class="row">'
    . '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light">Edit Claims</h4>
                <p class="small"><strong>Edit</strong> Claim</p>
            </div>
            <div class="right">
                <a href="' . Url::link('/admin/claims') . '" class="btn btn-default"><i class="fa fa-close"></i> Cancel</a>
                <button class="btn btn-primary" id="save_edits"><i class="fa fa-save"></i> Save Edits</button>
            </div>
            ';
echo '</div>
</div>
</div>';

?>
<!--Tabs-->
<div class="tabs row-padding">
    <ul role="tablist" class="nav nav-pills" id="myTabs">
        <li class="active" role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#claim-details">
                <i class="fa fa-eye"></i> Claim Overview</a>
        </li>
        <li role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#quote-details">
                <i class="fa fa-magnet"></i> Policy Overview</a>
        </li>
        <li role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#claim-timeline">
                <i class="fa fa-gavel"></i> Claim Timeline</a>
        </li>
        <li role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#edit-claim">
                <i class="fa fa-pencil"></i> Update Claim</a>
        </li>
    </ul>
</div>
<!--End Tabs-->
<?php
//print_r($meta);
?>
<!--Tab Content-->
<div class="row show-grid">
    <div class="dash-head clearfix mt15 mb20 shadow">
        <div class="tab-content col-xs-12">
            <div role="tabpanel" class="tab-pane" id="quote-details">
                <table width="100%" border="0" cellpadding="10" class="policy table-striped">
                    <tr>
                        <td colspan="2" class="heading">
                            <h2 class="mb5 text-light">Policy Overview: <?= $meta->policy->policy_number ?></h2></td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <p><strong>Quotation Reference Number:</strong> <?= $meta->quote->id ?></p>
                        </td>
                        <td width="50%" align="right">
                            <p><strong>Date Generated:</strong> <?= date('jS F Y', $meta->policy->datetime) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><strong>Customer:</strong><?= $meta->customer->name ?></p>
                        </td>
                        <td align="right">
                            <p><strong>Policy Number: </strong> <?= $meta->policy->policy_number ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><strong>Email Address:</strong> <?= $meta->customer->email ?></p>
                        </td>
                        <td align="right">
                            <p><strong>Product: </strong> <?= $meta->product->name ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><strong>Insurer: </strong> <?= $meta->insurer->name ?></p>
                        </td>
                        <td align="right">
                        </td>
                    </tr>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="edit-claim">
                <?= $claimedit; ?>
            </div>

            <div role="tabpanel" class="tab-pane active" id="claim-details">
                <table width="100%" border="0" cellpadding="10" class="policy table-striped">
                    <tr>
                        <td colspan="2" class="heading">
                            <h2 class="mb5 text-light">Claim Overview: <?= $meta->policy->policy_number ?></h2></td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <p><strong>Claim id:</strong> <?= $claim->id ?></p>
                        </td>
                        <td width="50%" align="right">
                            <p><strong>Date Started:</strong> <?= date('jS F Y', $claim->created_at) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><strong>Customer:</strong><?= $meta->customer->name ?></p>
                        </td>
                        <td align="right">
                            <p><strong>Policy Number: </strong> <?= $meta->policy->policy_number ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><strong>Email Address:</strong> <?= $meta->customer->email ?></p>
                        </td>
                        <td align="right">
                            <p><strong>Product: </strong> <?= $meta->product->name ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p><strong>Insurer: </strong> <?= $meta->insurer->name ?></p>
                        </td>
                        <td align="right">
                        </td>
                    </tr>
                </table>

            </div>

            <div role="tabpanel" class="tab-pane" id="claim-timeline">
                <?php
                $url = Url::route('/admin/claims/upload/{id}',
                    ['id' => $claim->id]);
                include_once 'timeline.php'; ?>
            </div>

        </div>
    </div>
</div>
<!--End Tab Content-->

<?= $uploadform; ?>
<script>
    $('button#save_edits').on('click', function () {
        $('form#confirmform').submit();
    });
</script>