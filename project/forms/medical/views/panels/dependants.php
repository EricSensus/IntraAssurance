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
        <div class="clearfix"></div>
<!--        <div class="row-fluid"><h3>Core Plan</h3></div>-->

        <!--Cover Plans-->
        <?php //include('cover_plans.php'); ?>
    <?php } ?>

    <!--hidden fields-->
    <input type="hidden" name="form_step" value="dependants"/>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <?=$btnsubmit; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</form>