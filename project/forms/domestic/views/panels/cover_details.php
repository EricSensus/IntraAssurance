<?php
use Jenga\App\Request\Url;

?>
<div class="middlesection">
    <div class="contentbox">
        <div class="motorinsuranceheader row">
            <div class="motorinsurance col-md-12 col-sm-12 col-xs-12">
                <h2>Domestic Insurance</h2>
            </div>
        </div>
        <table>
            <tr class="motorheading row">
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a href="<?= Url::link('/domestic/step/1') ?>"><h5>
                            Stage 1</h5></a>
                    <a href="<?= Url::link('/domestic/step/1') ?>">
                        <h6>Proposer Personal Details</h6></a>
                </th>
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a href="<?= Url::link('/domestic/step/2') ?>"><h5>
                            Stage 2</h5></a>
                    <a href="<?= Url::link('/domestic/step/2') ?>">
                        <h6>Property Details</h6></a>
                </th>
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a class="active" href="<?= Url::link('/domestic/step/3') ?>"><h5>
                            Stage 3</h5></a>
                    <a class="active" href="<?= Url::link('/domestic/step/3') ?>">
                        <h6>Cover Details</h6></a>
                </th>
                <th class="heading col-md-3 col-sm-12 col-xs-12">
                    <a href="<?= Url::link('/domestic/step/4') ?>"><h5>
                            Stage 4</h5></a>
                    <a href="<?= Url::link('/domestic/step/4') ?>">
                        <h6>Quotation & Payment</h6></a>
                </th>
            </tr>
        </table>
        <div class="row setup-content" id="step-1">
            <div class="col-xs-12">
                <div class="col-md-12 well">
                    <?= $form ?>
                </div>
            </div>
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
        $("#coverend").datepicker({dateFormat: 'yy-mm-dd', disabled: true});
    });
</script>