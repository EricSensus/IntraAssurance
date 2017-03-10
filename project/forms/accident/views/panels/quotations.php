<?php
use Jenga\App\Request\Url;

$pesa = $data->quotation;
//ss($pesa);
?>
<div class="row form-group">
    <div class="col-xs-12">
        <ul class="nav nav-pills nav-justified thumbnail setup-panel">
            <li class="disabled"><a href="<?= Url::link('/accident/step/1') ?>">
                    <h4 class="list-group-item-heading">Step 1</h4>
                    <p class="list-group-item-text">Personal details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('/accident/step/2') ?>">
                    <h4 class="list-group-item-heading">Step 2</h4>
                    <p class="list-group-item-text">Personal Accident Details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('/accident/step/3') ?>">
                    <h4 class="list-group-item-heading">Step 3</h4>
                    <p class="list-group-item-text">Cover Details</p>
                </a>
            </li>
            <li class="active"><a href="<?= Url::link('/accident/step/4') ?>">
                    <h4 class="list-group-item-heading">Step 4</h4>
                    <p class="list-group-item-text">Quotation and Payment</p>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="row setup-content" id="step-4">
    <div class="col-xs-12">
        <div class="col-md-12 well">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Name: <?= $pesa->customer->name ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-condensed">
                        <tr>
                            <td>Basic Premium</td>
                            <td>Ksh <?= number_format($pesa->premium_rate, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Training levy (<?= $pesa->levy_rate ?>)</td>
                            <td>Ksh <?= number_format($pesa->levy, 2) ?></td>
                        </tr>
                        <?php
                        if (!empty($pesa->others)):
                            foreach ($pesa->others as $item):
                                ?>
                                <tr>
                                    <th colspan="2">
                                        <?= $item->name ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Basic Premium</td>
                                    <td>Ksh <?= number_format($item->premium_rate, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Training levy (<?= $pesa->levy_rate ?>)</td>
                                    <td>Ksh <?= number_format($item->levy, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Sub Total</td>
                                    <td>Ksh <?= number_format($item->total, 2) ?></td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                        <tr>
                            <th colspan="2">Other levies</th>
                        </tr>
                        <tr>
                            <td>P.H.C.F</td>
                            <td>Ksh <?= number_format($pesa->policy_fund, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Stamp Duty</td>
                            <td>Ksh <?= number_format($pesa->stamp_duty, 2) ?></td>
                        </tr>
                        <tfoot>
                        <tr>
                            <th>Total Premium</th>
                            <th>Ksh <?= number_format($pesa->total, 2) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                    <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
