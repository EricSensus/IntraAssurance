<?php //print_r(get_defined_vars()); ?>
<div class="panel panel-default">
    <div class="panel-heading"><b>Step 3 of 4 - Cover Details</b> (<small>Note: All the fields marked with an asterisk(*) are mandatory!</small>)</div>
    <div class="panel-body" style="width: 90%; margin: 0 auto;">
        <div class="panel-group">
            <form action="/travel/save" method="post" class="form-horizontal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?=$label_no_travel_days; ?></label>
                            <?=$no_travel_days; ?>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_africa_basic_plan; ?>
                                <a data-toggle="collapse" href="#collapse1"> <?=$label_cover_plan_africa_basic_plan; ?></a>
                            </span>
                        </h4>
                    </div>
                    <div id="collapse1" class="panel-collapse collapse">
                        <div class="panel-body">
                            Unlimited transport or repatriation in case of illness or accident for you and your family member (s) insured here as well as repatriation of mortal remains and emergency return home in case of the death of a close family member; emergency dental care up to US$270 and much more.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_europe_plus_plan; ?>
                                <a data-toggle="collapse" href="#collapse2"> <?=$label_cover_plan_europe_plus_plan; ?></a>
                            </span>
                        </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                        <div class="panel-body">
                            Unlimited transport or repatriation in case of illness or accident for you and your family member (s) insured here as well as repatriation of all your mortal remains and emergency return home in case of the death of a close family member; emergency dental care up to US$608; delayed departure up to US$243 and much more.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_world_wide_basic_plan; ?>
                                <a data-toggle="collapse" href="#collapse3"> <?=$label_cover_plan_world_wide_basic_plan; ?></a>
                            </span>
                        </h4>
                    </div>
                    <div id="collapse3" class="panel-collapse collapse">
                        <div class="panel-body">
                            Unlimited transport or repatriation in case of illness or accident for you and your family member (s) insured here as well as repatriation of all your mortal remains and emergency return home in case of the death of a close family member; emergency dental care up to US$ 608; delayed departure up to US$405; legal defense up to US$2835 and much more.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_world_wide_plus_plan; ?>
                                <a data-toggle="collapse" href="#collapse4"> <?=$label_cover_plan_world_wide_plus_plan; ?></a>
                            </span>
                        </h4>
                    </div>
                    <div id="collapse4" class="panel-collapse collapse">
                        <div class="panel-body">
                            Unlimited transport or repatriation in case of illness or accident for you and your family member (s) insured here as well as repatriation of all your mortal remains and emergency return home in case of the death of a close family member; emergency dental care up to US$ 608; delayed departure up to US$405; legal defense up to US$2835 and much more.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_world_wide_extra; ?>
                                <a data-toggle="collapse" href="#collapse5"> <?=$label_cover_plan_world_wide_extra; ?></a>
                            </span>
                        </h4>
                    </div>
                    <div id="collapse5" class="panel-collapse collapse">
                        <div class="panel-body">
                            Unlimited transport or repatriation in case of illness or accident for you and your family member (s) insured here as well as repatriation of all your mortal remains and emergency return home in case of the death of a close family member; emergency dental care up to US$ 608; delayed departure up to US$405; legal defense up to US$2835 and much more.
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_haj_and_umra_basic_plan; ?>
                                <a data-toggle="collapse" href="#collapse6"> <?=$label_cover_plan_haj_and_umra_basic_plan; ?></a>
                            </span>
                        </h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_haj_and_umra_plus_plan; ?>
                                <a data-toggle="collapse" href="#collapse7"> <?=$label_cover_plan_haj_and_umra_plus_plan; ?></a>
                            </span>
                        </h4>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span>
                                <?=$cover_plan_haj_and_umra_extra_plan; ?>
                                <a data-toggle="collapse" href="#collapse7"> <?=$label_cover_plan_haj_and_umra_extra_plan; ?></a>
                            </span>
                        </h4>
                    </div>
                </div>
                <p></p>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?=$label_add_travel_companions; ?></label>
                            <?=$add_travel_companions_1; ?> <?=$label_add_travel_companions_1; ?>
                            <?=$add_travel_companions_0; ?> <?=$label_add_travel_companions_0; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label><?=$label_no_of_travel_companions; ?></label>
                        <?=$no_of_travel_companions; ?>
                    </div>
                </div>
                <div id="companion_inputs"></div>
                <?=$note2; ?>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label><?=$label_i_agree; ?></label>
                        <?=$i_agree_1.' '.$label_i_agree_1; ?>
                        <?=$i_agree_0.' '.$label_i_agree_0; ?>
                    </div>
                </div>
                <input type="hidden" name="form_step" value="form_3"/>
                <button class="btn btn-primary pull-right" id="gen_quote">Proceed to Get a Quotation ></button>
            </form>
        </div>
    </div>
</div>