<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;

/** @var stdClass $product */
extract($info);
$quotation = array_first($quotation);
$best = $_quote[0];
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
                            <?php
                            $tr = null;
                            foreach ($best->companions as $companion) {
                                $tr .= '<tr>';
                                $tr .= '<th colspan="2">Name: ' . $companion->name . '</th>';
                                $tr .= '</tr>';

                                $tr .= '<tr>';
                                $tr .= '<td>Chosen Cover Plan: </td>';
                                $tr .= '<td>' . $best->cover . '</td>';
                                $tr .= '</tr>';

                                $tr .= '<tr>';
                                $tr .= '<td>Days of Travel:</td>';
                                $tr .= '<td>' . $best->travel_days . ' day(s)</td>';
                                $tr .= '</tr>';

                                $tr .= '<tr>';
                                $tr .= '<td>Basic Premium: </td>';
                                $tr .= '<td>Ksh. ' . number_format($companion->basic_premium, 2) . '</td>';
                                $tr .= '</tr>';

                                $tr .= '<tr><td colspan="2">&nbsp;</td></tr>';

                            }
                            echo $tr;
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
                    <h3 class="panel-title"><strong>Quotation</strong></h3>
                </div>
                <div class="panel-body">
                    <?php foreach ($_quote as $quote) { ?>
                        <table class="table table-condensed table-stripped">
                            <thead>
                            <th colspan="2">#</th>
                            <th><?= $quote->insurer->name ?></th>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2">Basic Premium</td>
                                <td><?= number_format($quote->basic_premium, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">Other Cover Premium</td>
                                <td><?= number_format($quote->other_total, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">P.H.C.F</td>
                                <td><?= number_format($quote->policy_levy, 2) ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">Stamp Duty</td>
                                <td><?= number_format($quote->stamp_duty, 2) ?></td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th><?= number_format($quote->total, 2) ?></th>
                            </tr>
                            </tfoot>
                        </table>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
