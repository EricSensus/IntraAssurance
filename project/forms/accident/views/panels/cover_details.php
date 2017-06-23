<?php
use Jenga\App\Request\Url;
?>

<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Personal Accident Insurance</h2>
    </div>
</div>
<table class="wizard-heading row">
    <tr class="heading row">
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/1') ?>"><h5>
                    Stage 1</h5></a>
            <a href="<?= Url::link('/accident/step/1') ?>">
                <h6>Proposer Personal Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/2') ?>"><h5>
                    Stage 2</h5></a>
            <a href="<?= Url::link('/accident/step/2') ?>">
                <h6>Personal Accident Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a class="active" href="<?= Url::link('/accident/step/3') ?>"><h5>
                    Stage 3</h5></a>
            <a class="active" href="<?= Url::link('/accident/step/3') ?>">
                <h6>Cover Details</h6></a>
        </th>
        <th class="heading col-md-3 col-sm-12 col-xs-12">
            <a href="<?= Url::link('/accident/step/4') ?>"><h5>
                    Stage 4</h5></a>
            <a href="<?= Url::link('/accident/step/4') ?>">
                <h6>Quotation & Payment</h6></a>
        </th>
    </tr>
</table>

<div class="row setup-content" id="step-3">
    <div class="col-xs-12">
        <div class="col-md-12 well">
            <p>Please now enter your cover details below so that we can find the best insurance quote for you.
                <br/>Do not forget that you can always come back later to retrieve your quote or change your details</p>
            <small>
                CLASSIFICATION OF OCCUPATIONS INSURABLE UNDER THE POLICY
                <br/>
                <strong>CLASS I:</strong> Persons engaged solely in professional, administrative, clerical
                and non-manual occupations generally such as accountants, architects, auditors, bankers,
                clergymen, clerks, dentists, lawyers, medical practitioners, secretaries, stockbrokers, surgeons and teachers.
                <br/>
                <strong>CLASS II:</strong> Persons engaged in work of supervisory nature and others not in Class I,
                whose duties do not involve the use of tools or machinery or expose them to any special hazard such as auctioneers
                (not livestock), builders (superintending), civil engineers, commercial travelers, estate agents,
                farmers (superintending), decorators (superintending), grocers, hairdressers, merchants, pharmacists,
                plumbers (superintending), salesmen, tailors.
            </small>
            <br/><br/>
            <?= $form ?>
        </div>
    </div>
</div>

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
