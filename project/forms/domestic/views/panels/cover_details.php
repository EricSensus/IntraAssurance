<?php
use Jenga\App\Request\Url;

include_once PROJECT_PATH .DS. 'forms' .DS. 'wizard' .DS. 'wizard.php';
?>
<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Fire and Domestic Insurance</h2>
    </div>
</div>
<?php
    echo wizardHTML('3');
?>

<div class="row setup-content" id="step-2">
    <div class="col-xs-12">
        <div class="col-md-12">
            <?= $form ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#insurancefrom").datepicker({
                minDate: 0, dateFormat: 'yy-mm-dd', changeMonth: true,
                onSelect: function (date) {
                    var date2 = new Date(date);
                    date2.setFullYear(date2.getFullYear() + 1);
                    $('#insuranceto').datepicker('setDate', date2);
                }
            }
        );
        $("#insuranceto").datepicker({dateFormat: 'yy-mm-dd', disabled: true});
    });
</script>