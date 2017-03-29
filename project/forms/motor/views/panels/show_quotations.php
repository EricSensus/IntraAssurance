<?php
use Jenga\App\Request\Url;

$pesa = $data->payments;
include_once PROJECT_PATH .DS. 'forms' .DS. 'wizard' .DS. 'wizard.php';

use Jenga\App\Request\Session;

//add the commercial title
if(Session::has('motor_commercial') && Session::get('motor_commercial') === TRUE){   
    $title_addon = ' Commercial';
}
?>

<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Motor and MotorCycle<?= $title_addon ?> Insurance</h2>
    </div>
</div>

<?php
    echo wizardHTML('motor','4');
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Name: <?= $pesa->customer->name ?></h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th colspan="2"><h2 style="font-weight: bold">Name: <?= $pesa->customer->name ?></h2></th>
            </tr>
            </thead>
            <tr>
                <td>Total Sum Insured</td>
                <td>Ksh <?= number_format($pesa->tsi, 2) ?></td>
            </tr>
            <tr>
                <td>Type of Cover</td>
                <td><?= $pesa->cover_type ?></td>
            </tr>
            <tr>
                <td>
                    <span style="font-size: 18px; font-weight: bold"><?= (isset($pesa->minimum) ? 'Minimum' : 'Basic') ?> premium</span>
                </td>
                <td>
                    <span style="font-size: 18px; font-weight: bold">Ksh <?= number_format((isset($pesa->minimum) ? $pesa->minimum : $pesa->basic_premium), 2) ?></span>
                    </td>
            </tr>
            <tr>
                <th colspan="2"><span style="font-weight: bold">Additional Covers</span></th>
            </tr>
            <tr>
                <td>Windscreen (upto ksh 30000)</td>
                <td>Ksh <?= number_format($pesa->windscreen, 2) ?></td>
            </tr>
            <tr>
                <td>Entertainment System (upto ksh 30000)</td>
                <td>Ksh <?= number_format($pesa->audio, 2) ?></td>
            </tr>
            <tr>
                <td>Political Violence</td>
                <td>Ksh <?= number_format($pesa->terrorism, 2) ?></td>
            </tr>
            <tr>
                <td>SRCC (Strikes, Riotes and Civil Commotion)</td>
                <td>Ksh <?= number_format($pesa->riotes, 2) ?></td>
            </tr>
            <tr>
                <td>Excess Protector</td>
                <td>Ksh <?= number_format($pesa->excess_protector, 2) ?></td>
            </tr>
            <tr>
                <td>Loss of Use</td>
                <td>Ksh <?= number_format($pesa->loss_of_use, 2) ?></td>
            </tr>
            <tr>
                <td><span style="font-size: 18px; font-weight: bold">Net Premium</span></td>
                <td><span style="font-size: 18px; font-weight: bold">Ksh <?= number_format($pesa->net_premium, 2) ?></span></td>
            </tr>
            <?php
            if (!empty($pesa->cars)):
                foreach ($pesa->cars as $car):
                    ?>
                    <tr>
                        <th colspan="2">
                            <?= $car->reg ?>
                        </th>
                    </tr>
                    <tr>
                        <td>Total Sum Insured</td>
                        <td>Ksh <?= number_format($car->tsi, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Type of Cover</td>
                        <td><?= $pesa->cover_type ?></td>
                    </tr>
                    <tr>
                        <td><?= (isset($car->minimum) ? 'Minimum' : 'Basic') ?> premium</td>
                        <td>
                            Ksh <?= number_format((isset($car->minimum) ? $car->minimum : $car->basic_premium), 2) ?></td>
                    </tr>
                    <tr>
                        <td>Premuim less NCD</td>
                        <td>Ksh <?= number_format($car->basic_premium2, 2) ?></td>
                    </tr>
                    <tr>
                        <th colspan="2">Additional Covers</th>
                    </tr>
                    <tr>
                        <td>Windscreen</td>
                        <td>Ksh <?= number_format($car->windscreen, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Entertainment System</td>
                        <td>Ksh <?= number_format($car->audio, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Passenger Liability</td>
                        <td>Ksh <?= number_format($car->passenger, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Terrorism</td>
                        <td>Ksh <?= number_format($car->terrorism, 2) ?></td>
                    </tr>
                    <tr>
                        <td>Net Premium</td>
                        <td>Ksh <?= number_format($car->net_premium, 2) ?></td>
                    </tr>
                    <?php
                endforeach;
            endif;
            ?>
            <tr>
                <th colspan="2"><span style="font-weight: bold">Levies</span></th>
            </tr>
            <tr>
                <td>Training Levy</td>
                <td>Ksh <?= number_format($pesa->training_levy, 2) ?></td>
            </tr>
            <tr>
                <td>P.H.C.F</td>
                <td>Ksh <?= number_format($pesa->policy_levy, 2) ?></td>
            </tr>
            <tr>
                <td>Stamp Duty</td>
                <td>Ksh <?= number_format($pesa->stamp_duty, 2) ?></td>
            </tr>
            <tfoot>
            <tr>
                <th><span style="font-size: 24px; font-weight: bold">Total Premium</span></th>
                <th><span style="font-size: 24px; font-weight: bold">Ksh <?= number_format($pesa->total, 2) ?></span></th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
