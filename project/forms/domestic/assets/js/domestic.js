var Domestic = {
    loadMoreFields: function (no) {
        var fields = '';

        for(var i = 1; i <= no; i++) {
            fields += '<div class="row">'


            fields += '<div class="col-md-2">';
            fields += '<label>Item</label>';
            fields += '<input type="text" name="item_name' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Make</label>';
            fields += '<input type="text" name="item_make' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Model</label>';
            fields += '<input type="text" name="item_model' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>S/No.</label>';
            fields += '<input type="text" name="s_no' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Value</label>';
            fields += '<input type="text" name="item_value' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '</div>';
        }

        $('#more_fields').html(fields);
    },
    loadSectioncFields: function (no) {
        var fields = '';

        for(var i = 1; i <= no; i++) {
            fields += '<div class="row">'


            fields += '<div class="col-md-2">';
            fields += '<label>Description of Article</label>';
            fields += '<input type="text" name="item_name' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Make</label>';
            fields += '<input type="text" name="item_make' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Model</label>';
            fields += '<input type="text" name="item_model' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>S/No.</label>';
            fields += '<input type="text" name="s_no' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Value</label>';
            fields += '<input type="text" name="item_value' + i + '" class="form-control"/>';
            fields += '</div>';

            fields += '</div>';
        }

        $('#sectionc_fields').html(fields);
    },
    loadSectiondFields: function (no) {
        var fields = '';

        for(var i = 1; i <= no; i++) {
            fields += '<div class="row">'


            fields += '<div class="col-md-2">';
            fields += '<label>Occupation</label>';
            fields += '<input type="text" name="occupation'+ i +'" class="form-control" required/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Number</label>';
            fields += '<input type="number" name="number' + i + '" class="form-control" required/>';
            fields += '</div>';

            fields += '<div class="col-md-2">';
            fields += '<label>Estimated Annual Wages</label>';
            fields += '<input type="number" name="item_model' + i + '" class="form-control" required/>';
            fields += '</div>';

            fields += '</div>';
        }

        $('#sectiond_fields').html(fields);
    }
}

$(function () {
    $('head').append('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css">');
    $('head').append('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">');

    $('input[name="insure_individually"]').on('click', function () {
        var answer = parseInt($(this).val());
        Common.makeMandatory($(this).val(), 'select[name="how_many"]');
    });

    $('input[name="security_arrangements"]').on('click', function () {
        var security = $(this).val();

        if(security == 'anyother'){
            Common.makeMandatory(1, '#specify_any_ther_security');
        } else {
            Common.makeMandatory(0, '#specify_any_ther_security');
        }
    });

    // section b option 2 - insure each item individually
    $('#how_many').on('change', function () {
        // load more fields
        Domestic.loadMoreFields($(this).val());
    });

    // section c
    $('#section_c_no_of_items').on('change', function () {
         Domestic.loadSectioncFields($(this).val());
    });

    // section d
    $('#no_employees').on('change', function () {
        Domestic.loadSectiondFields($(this).val());
    })
});