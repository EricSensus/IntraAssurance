<form action="/medical/save" method="post" class="form-horizontal">
    <div class="col-md-12"><h4><?=$label_core_plans; ?></h4></div>

    <div class="row" style="margin: 0px;">

        <!--Premier Plan-->
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-primary" style="text-align: center;">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=$label_core_plans_premier.' '.$core_plans_premier; ?>
                        <a href="#" data-toggle="popover"
                           title="<h4 style='color: black'>Premier Plan</h3>"
                           data-html="true"
                           data-trigger="focus"
                           data-content="<div style='color: #000; font-size: 12px;'>
                            <p>The plan details will be described below:</p>
                            <p>Inpatient</p>
                            <p>Overall limit per year - 1M</p>
                            <p>Bed Limit - General Ward Bed</p>
                            <p>Treatment for Fibroids	- available in year 3: paid in full</p>
                            <p>Pre-existing and chronic illness	- available in year 2: max 200,000</p>
                            <p>Cancer treatment	- available in year 3: max 200,000</p>
                            <p>HIV/AIDS treatment	- available in year 2: max 200,000</p>
                            <p>Organ transplants	- available in year 2: max 200,000</p>
                        </div>
                        "
                           class="btn btn-sm btn-info btn-circle">i</a></h3>
                </div>
                <div class="panel-body" style="padding: 0px; text-align: center">
                    <div class="the-price">
                        <h3>Optional Benefits</h3>
                    </div>
                    <table class="table" style="text-align: center;">
                        <tr>
                            <td><?=$label_ba1.' '.$ba1_ba1; ?></td>
                        </tr>
                        <tr class="active">
                            <td>Overall limit per year 50000</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active"><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active"><td>&nbsp;</td></tr>
                        <tr><td><?=$label_bc1.' '.$bc1_bc1; ?></td></tr>
                        <tr class="active">
                            <td>Overall limit per year 50000 </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active">
                            <td><?=$label_bd1.' '.$bd1_bd1; ?></td>
                        </tr>
                        <tr><td>Overall limit per year 500000</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!--Advanced Plan-->
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-success" style="text-align: center;">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=$label_core_plans_advanced.' '.$core_plans_advanced; ?>
                        <a href="#" data-toggle="popover"
                           title="<h4 style='color: black'>Advanced Plan</h3>"
                           data-html="true"
                           data-trigger="focus"
                           data-content="<div style='color: #000; font-size: 12px;'>
                                <p>The plan details will be described below:</p>
                                <p>Inpatient</p>
                                <p>Overall limit per year - 2M</p>
                                <p>Bed Limit - General Ward Bed</p>
                                <p>Treatment for Fibroids	- available in year 3: paid in full</p>
                                <p>Pre-existing and chronic illness	- available in year 2: max 250,000</p>
                                <p>Cancer treatment	- available in year 3: max 250,000</p>
                                <p>HIV/AIDS treatment	- available in year 2: max 250,000</p>
                                <p>Organ transplants	- available in year 2: max 250,000</p>
                            </div>
                            "
                           class="btn btn-sm btn-info btn-circle">i</a></h3>
                </div>
                <div class="panel-body" style="padding: 0px; text-align: center">
                    <div class="the-price">
                        <h3>Optional Benefits</h3>
                    </div>
                    <table class="table" style="text-align: center;">
                        <tr>
                            <td><?=$label_ba2.' '.$ba2_ba2; ?></td>
                        </tr>
                        <tr class="active">
                            <td>Overall limit per year 80000</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active"><td>&nbsp;</td></tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active"><td>&nbsp;</td></tr>
                        <tr><td><?=$label_bc2.' '.$bc2_bc2; ?></td></tr>
                        <tr class="active">
                            <td>Overall limit per year 50000 </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active">
                            <td><?=$label_bd2.' '.$bd2_bd2; ?></td>
                        </tr>
                        <tr><td>Overall limit per year 500000</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Executive-->
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-warning" style="text-align: center;">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=$label_core_plans_executive.' '.$core_plans_executive; ?>
                        <a href="#" data-toggle="popover"
                           title="<h4 style='color: black'>Executive Plan</h3>"
                           data-html="true"
                           data-trigger="focus"
                           data-content="<div style='color: #000; font-size: 12px;'>
                                <p>The plan details will be described below:</p>
                                <p>Inpatient</p>
                                <p>Overall limit per year - 3M</p>
                                <p>Bed Limit - Standard Private Room: max 12,500</p>
                                <p>Treatment for Fibroids	- available in year 3: paid in full</p>
                                <p>Pre-existing and chronic illness	- available in year 2: max 300,000</p>
                                <p>Cancer treatment	- available in year 3: max 300,000</p>
                                <p>HIV/AIDS treatment	- available in year 2: max 300,000</p>
                                <p>Organ transplants	- available in year 2: max 300,000</p>
                            </div>
                            "
                           class="btn btn-sm btn-info btn-circle">i</a></h3>
                </div>
                <div class="panel-body" style="padding: 0px; text-align: center">
                    <div class="the-price">
                        <h3>Optional Benefits</h3>
                    </div>
                    <table class="table" style="text-align: center;">
                        <tr>
                            <td><?=$label_ba3.' '.$ba3_ba3; ?></td>
                        </tr>
                        <tr class="active">
                            <td>Overall limit per year 100000</td>
                        </tr>
                        <tr><td>Maternity</td></tr>
                        <tr class="active"><td><label>Normal - Overall limit per year 60000</label> <?=${'bb1_bb1'}; ?></td></tr>
                        <tr><td><label>Caesarean - Overall limit per year 120000</label> <?=${'bb3_bb3'}; ?></td></tr>
                        <tr class="active"><td>&nbsp;</td></tr>
                        <tr><td><?=$label_bc3.' '.$bc3_bc3; ?></td></tr>
                        <tr class="active">
                            <td>Overall limit per year 100000 </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active">
                            <td><?=$label_bd3.' '.$bd3_bd3; ?></td>
                        </tr>
                        <tr><td>Overall limit per year 500000</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!--Royal-->
        <div class="col-xs-12 col-md-3">
            <div class="panel panel-danger" style="text-align: center;">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=$label_core_plans_royal.' '.$core_plans_royal; ?>
                        <a href="#" data-toggle="popover"
                           title="<h4 style='color: black'>Royal Plan</h3>"
                           data-html="true"
                           data-trigger="focus"
                           data-content="<div style='color: #000; font-size: 12px;'>
                                <p>The plan details will be described below:</p>
                                <p>Inpatient</p>
                                <p>Overall limit per year - 5M</p>
                                <p>Bed Limit - Standard Private Room: max 12,500</p>
                                <p>Treatment for Fibroids	- available in year 3: paid in full</p>
                                <p>Pre-existing and chronic illness	- available in year 2: max 300,000</p>
                                <p>Cancer treatment	- available in year 3: max 300,000</p>
                                <p>HIV/AIDS treatment	- available in year 2: max 300,000</p>
                                <p>Organ transplants	- available in year 2: max 300,000</p>
                            </div>
                            "
                           class="btn btn-sm btn-info btn-circle">i</a></h3>
                </div>
                <div class="panel-body" style="padding: 0px; text-align: center">
                    <div class="the-price">
                        <h3>Optional Benefits</h3>
                    </div>
                    <table class="table" style="text-align: center;">
                        <tr>
                            <td><?=$label_ba4.' '.$ba4_ba4; ?></td>
                        </tr>
                        <tr class="active">
                            <td>Overall limit per year 100000</td>
                        </tr>
                        <tr><td>Maternity</td></tr>
                        <tr class="active"><td><label><?=$label_bb2; ?></label> <?=${'bb2_bb2'}; ?></td></tr>
                        <tr><td><label><?=$label_bb4; ?></label> <?=${'bb4_bb4'}; ?></td></tr>
                        <tr class="active"><td>&nbsp;</td></tr>
                        <tr><td><?=$label_bc4.' '.$bc4_bc4; ?></td></tr>
                        <tr class="active">
                            <td>Overall limit per year 100000 </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr class="active">
                            <td><?=$label_bd4.' '.$bd4_bd4; ?></td>
                        </tr>
                        <tr><td>Overall limit per year 500000</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div class="form-group col-md-12">
                <?=$label_have_dependants.' '.$have_dependants_yes.' '.$label_have_dependants_yes; ?>
                <?=$have_dependants_no.' '.$label_have_dependants_no; ?>
            </div>
        </div>

        <div class="row-fluid">
            <div class="form-group col-md-6">
                <?=$label_additional_covers.' '.$additional_covers; ?>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <label for="btnsubmit" id="label_btnsubmit"></label>
                    <?=$btnsubmit; ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>

    </div>
</form>