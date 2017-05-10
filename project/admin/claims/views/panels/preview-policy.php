<div class="row">
    <div class="col-xs-12">
        <table width="100%" border="0" cellpadding="10" class="policy table-striped">
            <tr>
                <td colspan="2" class="heading">
                    <h2 class="mb5 text-light">Policy Overview: <?= strtoupper($meta->policy->policy_number) ?></h2>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <p><strong>Quotation Reference Number:</strong> #<?= $meta->quote->id ?></p>
                </td>
                <td width="50%" align="right">
                    <p><strong>Date Generated:</strong> <?= date('jS F Y', $meta->policy->issue_date) ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><strong>Customer:</strong> <?= $meta->customer->name ?></p>
                </td>
                <td align="right">
                    <p><strong>Policy Number: </strong> <?= $meta->policy->id ?></p>
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
            <tr>
                <td align="left" class="premium">
                    <strong>Policy Status:</strong> <?= strtoupper($meta->policy->status) ?>
                </td>
                <td align="right" class="premium">
                    <strong>Premium Amount:</strong> <?= $meta->policy->amount ?>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>*Start Date:</strong> <?= date('jS F Y', $meta->policy->start_date) ?></td>
                <td align="right" class="validity">
                    <strong>*End Date:</strong> <?= date('jS F Y', $meta->policy->end_date) ?></td>
            </tr>
            <tr>
                <td>
                    <button type="button" class="btn btn-success" id="proceed"><i class="fa fa-arrow-circle-right"></i>
                        Proceed
                    </button>
                </td>
            </tr>
        </table>
    </div>
</div>