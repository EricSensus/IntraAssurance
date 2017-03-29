<?php
use Jenga\App\Request\Url;

include_once PROJECT_PATH .DS. 'forms' .DS. 'wizard' .DS. 'wizard.php';

use Jenga\App\Request\Session;

//add the commercial title
if(Session::has('motor_commercial') 
                && Session::get('motor_commercial') === TRUE){
   
    $title_addon = ' Commercial';
}
?>
<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Motor and MotorCycle<?= $title_addon ?> Insurance</h2>
    </div>
</div>
<?php
    echo wizardHTML('motor','2');
?>

<div class="row setup-content" id="step-2">
    <div class="col-xs-12">
        <div class="col-md-12">
            <?= $form ?>
        </div>
    </div>
</div>
