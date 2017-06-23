<?php
use Jenga\App\Views\Overlays;
use Jenga\App\Request\Session;
?>
<div class="col-md-12">
    <?php

    foreach ($data_array as $data) {
        $pesa = $data;
        $quote_id = $data->quote->id;
        ?>
        <div class="col-md-12">
            <div class="panel panel-info">
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
                            <td>Ksh <?= number_format($pesa->training_levy, 2) ?></td>
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
                                    <td>Ksh <?= number_format($item->training_levy, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Sub Total</td>
                                    <td>Ksh <?= number_format($item->basic_premium, 2) ?></td>
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
                    <? include('proceed_to_policy.php'); ?>
                </div>
            </div>
        </div>
        <?php
    } ?>
</div>
<?php
if(Session::has('policy')){
    ?>
    <a href="" data-toggle="modal" data-target="#confirmquotemodal"
       id="proceed_with_policy" class="btn btn-success pull-right">
        Continue with Policy creation <i class="fa fa-arrow-right"></i>
    </a>
    <?php
    $confirm_quote_modal = [
        'formid' => 'confirmquoteform',
        'id' => 'confirmquotemodal',
        'role' => 'dialog',
        'title' => 'Confirm Quote',
        'buttons' => [
            'Cancel' => [
                'class' => 'btn btn-default',
                'data-dismiss' => 'modal'
            ],
            'Attach' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
                'id' => 'save_button'
            ]
        ]
    ];
    echo Overlays::Modal($confirm_quote_modal);
}
?>

