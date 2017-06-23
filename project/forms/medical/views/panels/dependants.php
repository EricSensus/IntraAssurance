<?php
//    print_r(get_defined_vars());exit;
    $no_of_dependants = \Jenga\App\Request\Session::get('no_of_dependants');
?>

<form action="/medical/step/dependants" method="post" class="form-horizontal">
    <?php for ($i = 1; $i <= $no_of_dependants; $i++){ ?>
        <div class="row-fluid"><h2>Dependendant <?=$i; ?></h2><hr/></div>

        <div class="row-fluid">
            <div class="form-group col-md-3">
                <?=${'label_title_'.$i}.' '.${'title_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_proposer_surname_'.$i}.' '.${'proposer_surname_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_other_names_'.$i}.' '.${'other_names_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_dob_'.$i}.' '.${'dob_'.$i}; ?>
            </div>
        </div>

        <div class="row-fluid">
            <div class="form-group col-md-3">
                <?=${'label_gender_'.$i}.' '.${'gender_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_age_range_bracket_'.$i}.' '.${'age_range_bracket_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_id_passport_no_'.$i}.' '.${'id_passport_no_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_nhif_'.$i}.' '.${'nhif_'.$i}; ?>
            </div>
        </div>

        <div class="row-fluid">
            <div class="form-group col-md-3">
                <?=${'label_blood_type_'.$i}.' '.${'blood_type_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_nationality_'.$i}.' '.${'nationality_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_relation_to_proposer_'.$i}.' '.${'relation_to_proposer_'.$i}; ?>
            </div>

            <div class="form-group col-md-3">
                <?=${'label_occupation_'.$i}.' '.${'occupation_'.$i}; ?>
            </div>
        </div>

        <div class="row-fluid"><h3>Core Plan</h3></div>

        <!--Cover Plans-->
        <div class="row">
            <!--Premier Plan-->
            <div class="col-xs-12 col-md-3">
                <div class="panel panel-primary" style="text-align: center;">
                    <div class="panel-heading">
                        <h3 class="panel-title"><label>Premier</label> <?=${'core_plans_'.$i.'_premier'}; ?>
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
                        </div>" class="btn btn-sm btn-info btn-circle">i</a></h3>
                    </div>
                    <div class="panel-body" style="padding: 0px; text-align: center">
                        <div class="the-price">
                            <h3>Optional Benefits</h3>
                        </div>
                        <table class="table" style="text-align: center;">
                            <tr>
                                <td>
                                    <label>Out Patient</label>
                                    <?=${'ba1_'.$i.'_ba1'}; ?>
                                </td>
                            </tr>
                            <tr class="active">
                                <td>Overall limit per year 150000</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr class="active"><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr class="active"><td>&nbsp;</td></tr>
                            <tr><td><label>Last Expense</label> <?=${'bc1_'.$i.'_bc1'}; ?></td></tr>
                            <tr class="active">
                                <td>Overall limit per year 150000 </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr class="active">
                                <td><label>Personal Accident</label> <?=${'bd1_'.$i.'_bd1'}; ?></td>
                            </tr>
                            <tr><td>Overall limit per year 1500000</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <!--End Premier Plan-->

            <!--Advanced-->
            <div class="col-xs-12 col-md-3">
                        <div class="panel panel-success" style="text-align: center;">
                            <div class="panel-heading">
                                <h3 class="panel-title"><label>Advanced</label> <?=${'core_plans_'.$i.'_advanced'}; ?>
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
                                        <td><label>Out Patient</label> <?=${'ba2_'.$i.'_ba2'}; ?></td>
                                    </tr>
                                    <tr class="active">
                                        <td>Overall limit per year 180000</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr class="active"><td>&nbsp;</td></tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr class="active"><td>&nbsp;</td></tr>
                                    <tr><td><label>Last Expense</label> <?=${'bc2_'.$i.'_bc2'}; ?></td></tr>
                                    <tr class="active">
                                        <td>Overall limit per year 150000 </td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr class="active">
                                        <td><label>Personal Accident</label> <?=${'bd2_'.$i.'_bd2'}; ?></td>
                                    </tr>
                                    <tr><td>Overall limit per year 1500000</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
            <!--End Advanced-->

            <!--Executive-->
            <div class="col-xs-12 col-md-3">
                        <div class="panel panel-warning" style="text-align: center;">
                            <div class="panel-heading">
                                <h3 class="panel-title"><label>Executive</label> <?=${'core_plans_'.$i.'_executive'}; ?>
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
                                        <td><label>Out Patient</label> <?=${'ba3_'.$i.'_ba3'}; ?></td>
                                    </tr>
                                    <tr class="active">
                                        <td>Overall limit per year 1100000</td>
                                    </tr>
                                    <tr><td>Maternity</td></tr>
                                    <tr class="active"><td><label>Normal - Overall limit per year 60000</label> <?=${'bb1_'.$i.'_bb1'}; ?></td></tr>
                                    <tr><td><label>Caesarean - Overall limit per year 120000</label> <?=${'bb3_'.$i.'_bb3'}; ?></td></tr>
                                    <tr class="active"><td>&nbsp;</td></tr>
                                    <tr><td><label>Last Expense</label> <?=${'bc3_'.$i.'_bc3'}; ?></td></tr>
                                    <tr class="active">
                                        <td>Overall limit per year 50000 </td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr class="active">
                                        <td><label>Personal Accident</label> <?=${'bd4_'.$i.'_bd4'}; ?></td>
                                    </tr>
                                    <tr><td>Overall limit per year 500000</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
            <!--End Executive-->

            <!--Royal Plan-->
            <div class="col-xs-12 col-md-3">
                        <div class="panel panel-danger" style="text-align: center;">
                            <div class="panel-heading">
                                <h3 class="panel-title"><label>Royal</label> <?=${'core_plans_'.$i.'_royal'}; ?>
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
                                        <td><label>Out Patient</label> <?=${'ba4_'.$i.'_ba4'}; ?></td>
                                    </tr>
                                    <tr class="active">
                                        <td>Overall limit per year 2100000</td>
                                    </tr>
                                    <tr><td>Maternity</td></tr>
                                    <tr class="active"><td><label>Normal - Overall limit per year 60000</label> <?=${'bb2_'.$i.'_bb2'}; ?></td></tr>
                                    <tr><td><label>Caesarean - Overall limit per year 120000</label> <?=${'bb4_'.$i.'_bb4'}; ?></td></tr>
                                    <tr class="active"><td>&nbsp;</td></tr>
                                    <tr><td><label>Last Expense</label> <?=${'bc4_'.$i.'_bc4'}; ?></td></tr>
                                    <tr class="active">
                                        <td>Overall limit per year 150000 </td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr class="active">
                                        <td><label>Personal Accident</label> <?=${'bd4_'.$i.'_bd4'}; ?></td>
                                    </tr>
                                    <tr><td>Overall limit per year 1500000</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
            <!--End Royal Plan-->
        </div>
    <?php } ?>

    <!--hidden fields-->
    <input type="hidden" name="form_step" value="dependants"/>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <input type="submit" name="btnsubmit" id="btnsubmit" value="Proceed to Additional Details >" class="submit btn btn-primary pull-right">
            </div>
        </div>
        <div class="clear"></div>
    </div>
</form>