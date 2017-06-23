<?php
use Jenga\App\Request\Url;

$pesa = $data->quotation;
//ss($pesa);
?>
<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Personal Accident Insurance</h2>
    </div>
</div>
<table class="wizard-heading row">
    <tr class="heading row">
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/1') ?>"><h5>
                    Stage 1</h5></a>
            <a href="<?= Url::link('/accident/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/2') ?>"><h5>
                    Stage 2</h5></a>
            <a href="<?= Url::link('/accident/step/2') ?>">
                <h6>Personal Accident Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/3') ?>"><h5>
                    Stage 3</h5></a>
            <a href="<?= Url::link('/accident/step/3') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active" href="<?= Url::link('/accident/step/4') ?>"><h5>
                    Stage 4</h5></a>
            <a class="active" href="<?= Url::link('/accident/step/4') ?>">
                <h6>Quotation & Payment</h6></a>
        </th>
    </tr>
</table>
<div class="row setup-content" id="step-4">
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
