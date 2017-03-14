<?php
use Jenga\App\Request\Url;

include_once PROJECT_PATH .DS. 'forms' .DS. 'wizard' .DS. 'wizard.php';
?>

<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Motor and MotorCycle Insurance</h2>
    </div>
</div>

<?php
    echo wizardHTML('1');
?>
<div class="row setup-content" id="step-1">
    <div class="col-xs-12">
        <div class="col-md-12">
            <?= $form ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".datepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true});
    });
</script>
