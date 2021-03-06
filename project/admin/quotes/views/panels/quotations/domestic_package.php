<?php
    use Jenga\App\Views\Overlays;
    use Jenga\App\Request\Session;
?>
<div class="col-md-12">
    <?php

    foreach ($data_array as $data) {
        $quotation = $data;
        $count = 0;
        $quote_id = $data->quote->id;
        ?>
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $data->insurer->name ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-condensed">
                        <tr>
                            <td>Name</td>
                            <td><?= $quotation->customer->name ?></td>
                        </tr>
                        <tr>
                            <td>Section A: Buildings (Kshs <?= number_format($quotation->tsi_a) ?>)</td>
                            <td>Ksh <?= number_format($quotation->section_a, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Section B: Contents (Kshs <?= number_format($quotation->tsi_b) ?>)</td>
                            <td>Ksh <?= number_format($quotation->section_b, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Section C: All Risks (Kshs <?= number_format($quotation->tsi_c) ?>)</td>
                            <td>Ksh <?= number_format($quotation->section_c, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Section D: Workmens compensation</td>
                            <td>Ksh <?= number_format($quotation->workmen, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Section E: Owners liability - extra cover
                                (Kshs <?= $quotation->step3->owner_liabilty ?>)
                            </td>
                            <td>Ksh <?= number_format($quotation->owner_liability, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Section F: Occupier Liability - extra cover
                                (Kshs <?= $quotation->step3->occupiers_liabilty ?>)
                            </td>
                            <td>Ksh <?= number_format($quotation->occupier_liability, 2) ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Gross Premium</th>
                            <th>Ksh <?= number_format($quotation->gross_premium, 2) ?></th>
                        </tr>
                        <tr>
                            <td>Training Levy (<?= $quotation->training_rate ?> )</td>
                            <td>Ksh <?= number_format($quotation->training_levy, 2) ?></td>
                        </tr>
                        <tr>
                            <td>P.H.C.F (<?= $quotation->levy_value ?> )</td>
                            <td>Ksh <?= number_format($quotation->policy_levy, 2) ?></td>
                        </tr>
                        <tr>
                            <td>Stamp Duty</td>
                            <td>Ksh <?= number_format($quotation->stamp_duty, 2) ?></td>
                        </tr>
                        <tfoot>
                        <tr>
                            <th>Total Premium</th>
                            <th>Ksh <?= number_format($quotation->total, 2) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                    <? include('proceed_to_policy.php'); ?>
                    <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
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
