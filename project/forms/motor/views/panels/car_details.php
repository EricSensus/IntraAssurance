<?php
use Jenga\App\Request\Url;
?>
<div class="panel panel-default" id="step-2">
    <div class="panel-heading"><b>Motor: Step 2 of 4 - Car Details</b> (<small>Note: All the fields marked with an asterisk(*) are mandatory!</small>)</div>
    <div class="row form-group">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="disabled"><a href="<?= Url::link('motor/step') ?>">
                        <h4 class="list-group-item-heading">Step 1</h4>
                        <p class="list-group-item-text">Proper personal details</p>
                    </a>
                </li>
                <li class="active"><a href="<?= Url::link('motor/step/2') ?>">
                        <h4 class="list-group-item-heading">Step 2</h4>
                        <p class="list-group-item-text">Car Details</p>
                    </a>
                </li>
                <li class="disabled"><a href="<?= Url::link('motor/step/3') ?>">
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
    </div>
    <div class="form-group" style="width: 90%; margin: 0 auto;">
        <?= $form ?>
    </div>
</div>
