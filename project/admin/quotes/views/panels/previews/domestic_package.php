<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;

/** @var stdClass $product */
extract($info);
//dump(get_defined_vars());exit;
$quotation = array_first($quotation);

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
                                    <th> <?= $info->plotno ?> <i>(In <?= $info->town ?>
                                            road <?= $info->road ?>)</i></th>
                                </tr>
                                <tr>
                                    <td>Dwelling Type</td>
                                    <td><?= $info->dwelling_type ?></td>
                                </tr>
                                <tr>
                                    <td>Wall Materials</td>
                                    <td><?= $info->wall_materials ?></td>
                                </tr>
                                <tr>
                                    <td>Roof Materials</td>
                                    <td><?= $info->roof_material ?></td>
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
                                <td>Section A premium</td>
                                <td><?= $product->a_premium ?></td>
                            </tr>
                            <tr>
                                <td>Section B premium</td>
                                <td><?= $product->b_premium ?></td>
                            </tr>
                            <tr>
                                <td>Section C premium</td>
                                <td><?= $product->c_premium ?></td>
                            </tr>
                            <tr>
                                <td>Owner Liability</td>
                                <td><?= $product->owner_liabilty ?></td>
                            </tr>
                            <tr>
                                <td>Occupiers Liability</td>
                                <td><?= $product->occupiers_liabilty ?></td>
                            </tr>
                            <tr>
                                <td>Domestic Servant Number</td>
                                <td><?= $product->domestic_servants ? $product->domestic_servants : 'N/A' ?></td>
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
                        <?php foreach ($_quote as $quote) { ?>
                            <table class="table table-condensed table-stripped">
                                <tbody>
                                <thead>
                                <tr>
                                    <th>Insurer</th>
                                    <th><?= $quote->insurer->name ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Section A</td>
                                    <td><?= number_format($quote->section_a, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Section B</td>
                                    <td><?= number_format($quote->section_b, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Section C</td>
                                    <td><?= number_format($quote->section_c, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Workmen Compensation</td>
                                    <td><?= number_format($quote->workmen, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Gross Premium</td>
                                    <td><?= number_format($quote->gross_premium, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>P.H.C.F</td>
                                    <td><?= number_format($quote->policy_levy, 2) ?></td>
                                </tr>

                                <tr>
                                    <td>Training</td>
                                    <td><?= number_format($quote->training_levy, 2) ?></td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th><?= number_format($quote->total, 2) ?></th>
                                </tr>
                                </tfoot>
                            </table>
                        <?php } ?>
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
