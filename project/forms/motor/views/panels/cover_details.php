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
<div class="insuranceheader row">
    <div class="insurance col-md-4 col-sm-12 col-xs-12">
        <h2>Motor Insurance</h2>
    </div>
</div>
<table class="wizard-heading row">
    <tbody>
    <tr>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/motor/step/1') ?>"><h5>Stage 1</h5></a>
            <a href="<?= Url::link('motor/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/motor/step/2') ?>"><h5>Stage 2</h5></a>
            <a href="<?= Url::link('/motor/step/2') ?>">
                <h6>Car Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active" href="<?= Url::link('/motor/step/3') ?>"><h5>Stage 3</h5></a>
            <a class="active" href="<?= Url::link('/motor/step/3') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/motor/step/4') ?>"><h5>Stage 4</h5></a>
            <a href="<?= Url::link('/motor/step/4') ?>">
                <h6>Quotation &amp; Payment</h6></a>
        </th>
    </tr>
    </tbody>
</table>
<div class="formheading row">
</div>

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
        $("#coverend").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>