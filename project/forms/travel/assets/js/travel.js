var Travel = {
    loadMore: function(no){
        var no = parseInt(no);
        var inputs = '';
        if(no) {
            for (var i = 1; i <= no; i++) {
                inputs += '<div class="row">';
                inputs += '<div class="col-md-3">';
                inputs += '<div class="form-group">';

                inputs += '<label>Name</label>'
                inputs += '<input type="text" name="companion_name'+i+'" class="form-control"/>';

                inputs += '</div>';
                inputs += '</div>';

                inputs += '<div class="col-md-3">';
                inputs += '<div class="form-group">';

                inputs += '<label>Occupation</label>'
                inputs += '<input type="text" name="companion_occupation'+i+'" class="form-control"/>';

                inputs += '</div>';
                inputs += '</div>';

                inputs += '<div class="col-md-3">';
                inputs += '<div class="form-group">';

                inputs += '<label>Date of Birth</label>'
                inputs += '<input type="text" name="companion_dob'+i+'" class="form-control datepicker"/>';

                inputs += '</div>';
                inputs += '</div>';

                inputs += '<div class="col-md-3">';
                inputs += '<div class="form-group">';

                inputs += '<label>Relationship with the Proposer</label>'
                inputs += '<select type="text" name="companion_relationship'+i+'" class="form-control">' +
                        '<option value="wife">wife</option>' +
                        '<option value="husband">husband</option>' +
                        '<option value="son">son</option>' +
                        '<option value="daughter">daughter</option>' +
                        '<option value="other">other</option>' +
                    '</select>';

                inputs += '</div>';
                inputs += '</div>';

                inputs += '<div class="col-md-3">';
                inputs += '<div class="form-group">';

                inputs += '<label>Passport Number</label>'
                inputs += '<input type="text" name="companion_passport'+i+'" class="form-control"/>';

                inputs += '</div>';
                inputs += '</div>';

                inputs += '<div class="col-md-3">';
                inputs += '<div class="form-group">';

                inputs += '<label>Plan</label>'
                inputs += '<input type="text" name="companion_plan'+i+'" class="form-control"/>';

                inputs += '</div>';
                inputs += '</div>';

                inputs += '<div class="col-md-3">';
                inputs += '<div class="form-group">';

                inputs += '<label>No of days</label>'
                inputs += '<input type="number" name="companion_no_of_days'+i+'" class="form-control"/>';

                inputs += '</div>';
                inputs += '</div>';
                inputs += '</div>';
                inputs += '<hr/>';
            }
        }
        $('div#companion_inputs').html(inputs);
    }
}

$(function(){
    // initialize datepicker
    $('.datepicker').datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    // inline radio
    $(document).find('input[id^="cover_plan"], input[id^="add_travel_companions"], input[id^="i_agree"]').css('display', 'inline');

    $('input:radio[id^="add_travel_companions"]').on('click', function(){
        Common.makeMandatory($(this).val(), 'select#no_of_travel_companions');

        if(!parseInt($(this).val()))
            $('div#companion_inputs').html('');
    });

    /**
     * Step 2
     */
    $('input:radio[id^="physical_disability"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#phy_dis_particulars');
    });

    $('input:radio[id^="good_health"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#health_particulars');
    });

    $('input:radio[id^="medical_treatment"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#med_treat_particulars');
    });

    $('input:radio[id^="disorders"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#dis_particulars');
    });

    $('input:radio[id^="cancelled_prev_insurance"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#prev_ins_cancelled');
    });

    $('input:radio[id^="already_insured"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#already_ins_particulars');
    });

    $('input:radio[id^="claimed"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#claimed79_particulars');
    });

    /**
     * Step 3
     */
    $('input:radio[id^="i_agree"]').on('click', function(){
        Common.makeMandatory($(this).val(), '#gen_quote');
    });

    $('#no_of_travel_companions').on('change', function(){
        var no_of_companions = $(this).val();

        Travel.loadMore(no_of_companions);
    });
});