<?php
    use Jenga\App\Request\Url;
    use Jenga\App\Request\Session;
?>

<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Medical Insurance</h2>
    </div>
</div>
<table class="wizard-heading row">
    <tr class="heading row">
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active"
               href="<?= Url::link('/medical/step/1') ?>"><h5>
                    Stage 1</h5></a>
            <a class="active"
               href="<?= Url::link('/medical/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/medical/step/2') ?>"><h5>
                    Stage 2</h5></a>
            <a href="<?= Url::link('/medical/step/2') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/medical/step/3') ?>"><h5>
                    Stage 3</h5></a>
            <a href="<?= Url::link('/medical/step/3') ?>">
                <h6>Additional Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/medical/step/4') ?>"><h5>
                    Stage 4</h5></a>
            <a href="<?= Url::link('/medical/step/4') ?>">
                <h6>Quotation & Payment</h6></a>
        </th>
    </tr>
</table>
<div class="formheading row">
    <div class="formheadingcaption col-md-12 col-sm-12 col-xs-12"><p>Please enter your personal details below so that we can find the best insurance quote for you.</p>
    </div>
 </div>

<?=$form; ?>
<?=$modal_link; ?>

<style>
    form#customerloginform > table {
        width: 100%;
    }
</style>
