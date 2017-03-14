<?php
/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */
use Jenga\App\Request\Url;
include_once PROJECT_PATH .DS. 'forms' .DS. 'wizard' .DS. 'wizard.php';
?>

<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Motor and MotorCycle Insurance</h2>
    </div>
</div>

<?php
    echo wizardHTML('3');
?>

<?= $form ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#coverstart").datepicker({
                minDate: 0, dateFormat: 'yy-mm-dd', changeMonth: true,
                onSelect: function (date) {
                    var date2 = new Date(date);
                    date2.setFullYear(date2.getFullYear() + 1);
                    $('#coverend').datepicker('setDate', date2);
                }
            }
        );
        $("#coverend").datepicker({dateFormat: 'yy-mm-dd', disabled: true});
    });
</script>