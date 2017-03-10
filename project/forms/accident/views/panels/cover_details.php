<?php
use Jenga\App\Request\Url;

?>
<div class="row form-group">
    <div class="col-xs-12">
        <ul class="nav nav-pills nav-justified thumbnail setup-panel">
            <li class="disabled"><a href="<?= Url::link('/accident/step/1') ?>">
                    <h4 class="list-group-item-heading">Step 1</h4>
                    <p class="list-group-item-text">Personal details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('/accident/step/2') ?>">
                    <h4 class="list-group-item-heading">Step 2</h4>
                    <p class="list-group-item-text">Personal Accident Details</p>
                </a>
            </li>
            <li class="active"><a href="<?= Url::link('/accident/step/3') ?>">
                    <h4 class="list-group-item-heading">Step 3</h4>
                    <p class="list-group-item-text">Cover Details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('/accident/step/4') ?>">
                    <h4 class="list-group-item-heading">Step 4</h4>
                    <p class="list-group-item-text">Quotation and Payment</p>
                </a>
            </li>
        </ul>
    </div>
</div>
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