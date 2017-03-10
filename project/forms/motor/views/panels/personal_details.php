<?php
use Jenga\App\Request\Url;
?>
    <div class="motorinsuranceheader row">
        <div class="motorinsurance col-md-4 col-sm-12 col-xs-12">
                <h2>Motor Insurance</h2>
        </div>
    </div>
    <table class="motorheading row">
        <tbody>
            <tr>
            <th class="heading col-md-3 col-sm-12 col-xs-12">
                <a class="active" href="<?= Url::link('motor/step') ?>"><h5>Stage 1</h5></a>
                <a class="active" href="<?= Url::link('motor/step') ?>">
                <h6>Proposer Personal Details</h6></a>
            </th>
            <th class="heading col-md-3 col-sm-12 col-xs-12">
                <a href="<?= Url::link('motor/step') ?>"><h5>Stage 2</h5></a>
                <a href="<?= Url::link('motor/step') ?>">
                <h6>Card Details</h6></a>
            </th>
            <th class="heading col-md-3 col-sm-12 col-xs-12">
                <a href="<?= Url::link('motor/step') ?>"><h5>Stage 3</h5></a>
                <a href="<?= Url::link('motor/step') ?>">
                <h6>Cover Details</h6></a>
            </th>
            <th class="heading col-md-3 col-sm-12 col-xs-12">
                <a href="<?= Url::link('motor/step') ?>"><h5>Stage 4</h5></a>
                <a href="<?= Url::link('motor/step') ?>">
                <h6>Quotation &amp; Payment</h6></a>
            </th>
            </tr>
        </tbody>
    </table>
    <div class="formheading row">
        <div class="formheadingcaption col-md-12 col-sm-12 col-xs-12"><p>Please enter your personal details below so that we can find the best insurance quote for you. Don't forget that you can always came back later to retrieve your quote or change your details.</p>
        </div>
     </div>
    <?= $form ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#dob").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, maxDate: '-15Y'});
    });
</script>
