<?php
/*
 * =================================
 * Author: Samuel Okoth
 * Email: samuel@sensussystems.com
 * Company: Sensus Systems
 * Website: http://www.sensussystems.com
 */
use Jenga\App\Request\Url;
?>
<div class="panel panel-default" id="step-3">
    <div class="panel-heading"><b>Motor: Step 3 of 4 - Cover Details</b> (<small>Note: All the fields marked with an asterisk(*) are mandatory!</small>)</div>
    <div class="col-xs-12">
        <ul class="nav nav-pills nav-justified thumbnail setup-panel">
            <li class="disabled"><a href="<?= Url::link('motor/step') ?>">
                    <h4 class="list-group-item-heading">Step 1</h4>
                    <p class="list-group-item-text">Proper personal details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('motor/step/2') ?>">
                    <h4 class="list-group-item-heading">Step 2</h4>
                    <p class="list-group-item-text">Car Details</p>
                </a>
            </li>
            <li class="active"><a href="<?= Url::link('motor/step/3') ?>">
                    <h4 class="list-group-item-heading">Step 3</h4>
                    <p class="list-group-item-text">Cover Details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('motor/step/4') ?>">
                    <h4 class="list-group-item-heading">Step 4</h4>
                    <p class="list-group-item-text">Quotation and Payment</p>
                </a>
            </li>
        </ul>
    </div>
    <div class="form-group" style="width: 90%; margin: 0 auto;">
        <?= $form ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#coverstart").datepicker({minDate: 0, dateFormat: 'yy-mm-dd', changeMonth: true,
            onSelect: function (date) {
                var date2 = new Date(date);
                date2.setFullYear(date2.getFullYear() + 1);
                $('#coverend').datepicker('setDate', date2);
            }}
        );
        $("#coverend").datepicker({dateFormat: 'yy-mm-dd', disabled: true});
    });
</script>