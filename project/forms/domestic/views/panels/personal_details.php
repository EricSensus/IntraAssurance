<?php
use Jenga\App\Request\Url;

?>
<?php
include_once PROJECT_PATH .DS. 'forms' .DS. 'wizard' .DS. 'wizard.php';
?>

<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Fire and Domestic Insurance</h2>
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
        $("#dob").datepicker({dateFormat: 'yy-dd-mm', changeYear: true, changeMonth: true, maxDate: '-15Y'});
    });
</script>