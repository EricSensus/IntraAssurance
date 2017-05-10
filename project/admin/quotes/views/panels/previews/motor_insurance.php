<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;

/** @var stdClass $product */
extract($info);

HTML::head(TRUE, TRUE);
HTML::css('admin/css/admin_css.css', FALSE, TRUE);
HTML::css('preview/css/preview.css', FALSE, TRUE);
/** @var stdClass $quote */
?>
<div class="container" style="background-color: #fff;">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2 style="display: inline-block;">Quotation: <span>#<?= sprintf("%'.05d\n", $quote->id) ?>
                        [ <?= date('jS F Y', $quote->datetime) ?>]</span></h2>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <p><strong>Name :</strong> <?= $customer->name ?></p>
                        <p><strong>Mobile No :</strong> <?= $customer->mobile_no ?></p>
                        <p><strong>Email :</strong> <?= $customer->email ?></p>
                        <p><strong>Postal Address :</strong> <?= $customer->address ?></p>
                    </address>
                </div>
                <div class="col-xs-6 <?= (!empty($class)) ? '' : 'text-right'; ?>">
                    <address>
                        <p><strong>Reg Date:</strong> <?= date('jS F Y', $customer->regdate) ?></p>
                        <p><strong>ID Number:</strong> <?= $customer->id_number ?></p>
                        <p><strong>Date of Birth :</strong> <?= date('dS F Y', $customer->date_of_birth) ?></p>
                        <p><strong>Occupation :</strong> <?= $customer->occupation ?></p>
                    </address>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <h4>Quote Duration</span></h4>
                        <blockquote>
                            <span style="font-size: <?= (empty($class)) ? '15px' : 'small'; ?>">
                                Start Date: <b> <?= date('jS F Y', strtotime($product->coverstart)) ?></b>
                                End Date: <b><?= date('jS F Y', strtotime($product->coverend)) ?></b>
                            </span>
                        </blockquote>
                    </address>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Product(s) being insured</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-stripped">
                            <tbody>
                            <?php foreach ($entities as $index => $info): ?>
                                <tr>
                                    <th colspan="2"> <?= $info->makes ?> : <?= $info->regno ?></th>
                                </tr>
                                <tr>
                                    <td>Registration Number</td>
                                    <td><?= $info->regno ?></td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td><?= $info->model ?></td>
                                </tr>
                                <tr>
                                    <td>Make</td>
                                    <td><?= $info->makes ?></td>
                                </tr>
                                <tr>
                                    <td>Estimated Value</td>
                                    <td><?= $info->valueestimate ?></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Cover Information</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-stripped">
                            <tbody>

                            <tr>
                                <td>Start Date</td>
                                <td><?= $product->coverstart ?></td>
                            </tr>
                            <tr>
                                <td>End Date</td>
                                <td><?= $product->coverend ?></td>
                            </tr>
                            <tr>
                                <td>Cover Type</td>
                                <td><?= $product->covertype ?></td>
                            </tr>
                            <tr>
                                <td>Additional Covers</td>
                                <td><?= $additional_covers ?></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Quotation</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-stripped">
                            <tbody>
                            <?php
                            foreach ($quotation as $index => $info):
                                if (is_array($info) || is_object($info)) {
                                    foreach ($info as $i => $v):
                                        ?>
                                        <tr>
                                            <th colspan="3"><?= $i ?></th>
                                        </tr>
                                        <?php
                                        foreach ($v as $e => $t):
                                            if (empty($t))
                                                continue;
                                            ?>
                                            <tr>
                                                <td>-</td>
                                                <td><?= ucwords($e) ?></td>
                                                <td><?= number_format($t, 2) ?></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endforeach;
                                }
                            endforeach;
                            ?>
                            <tr>
                                <td colspan="2">Total Net Premiums</td>
                                <td><?= number_format($quotation->total_net_premiums, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">P.H.C.F</td>
                                <td><?= number_format($quotation->policy_levy, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">Stamp Duty</td>
                                <td><?= number_format($quotation->stamp_duty, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">Total</td>
                                <td><?= number_format($quotation->total, 2) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    /** @var bool $confirm */
    if ($confirm):
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Next steps ... </strong></h3>
                    </div>
                    <div class="panel-body">
                        <p>Please confirm the above proposal</p>
                        <div class="col-md-12">
                            <div class="pull-left">
                                <button type="button" class="btn btn-success" id="accept">Confirm Quotation</button>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#rejectModal">
                                    Reject
                                    Quotation
                                </button>
                            </div>
                        </div>
                        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content panel-danger">
                                    <div class="modal-header panel-heading">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title">Reject Quotation?</h4>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to reject this quotation?<br/>
                                        Meanwhile, if you are absolutely sure. Please tell us why:<br/>
                                        <form>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="expensive">The quotation is
                                                    expensive</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="error">There is an error in this
                                                    quotation</label>
                                            </div>
                                            <div class="checkbox disabled">
                                                <label><input type="checkbox" value="other" disabled>Other/Personal
                                                    reasons</label>
                                            </div>
                                        </form>
                                        <blockquote>
                                            We will keep this quote for 1 month, you can still come back and accept it
                                        </blockquote>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">No, Wait
                                        </button>
                                        <button type="button" class="btn btn-danger" id="reject">Yes, Reject this Quote!
                                        </button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <script>
                            $(function () {
                                var SITE_URL = '<?= SITE_PATH ?>';
                                var to_send = null;
                                $('#accept').click(function () {
                                    to_send = "Yes";
                                    sendResponse();
                                });
                                $('#reject').click(function () {
                                    to_send = "No";
                                    sendResponse();
                                });
                                function sendResponse() {
                                    var newForm = $('<form>', {
                                        'action': SITE_URL + '/quote/acceptreject',
                                        'method': 'post'
                                    }).append($('<input>', {
                                        'name': 'action',
                                        'value': to_send,
                                        'type': 'hidden'
                                    })).append($('<input>', {
                                        'name': 'quote',
                                        'value': '<?= $quote->id ?>',
                                        'type': 'hidden'
                                    }));
                                    $(document.body).append(newForm);
                                    console.log(newForm);
                                    newForm.submit();
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>
<style>
    /* .gray {
         background-color: #e6e6e6;
     }

     .border {
         background-color: #dddddd;
     }*/
</style>
