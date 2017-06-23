$(function () {
    var sub_btn = $('#btnSubmit');
    var next_btn = $('#next');
    var prev_btn = $('#prev');
    sub_btn.hide();
    prev_btn.hide();
    var Travel = {
        loadMore: function (no) {
            var no = parseInt(no);
            var inputs = '';
            if (no) {
                for (var i = 1; i <= no; i++) {
                    inputs += '<div class="row">';
                    inputs += '<div class="col-md-3">';
                    inputs += '<div class="form-group">';

                    inputs += '<label>Name</label>'
                    inputs += '<input type="text" name="companion_name' + i + '" class="form-control"/>';

                    inputs += '</div>';
                    inputs += '</div>';

                    inputs += '<div class="col-md-3">';
                    inputs += '<div class="form-group">';

                    inputs += '<label>Occupation</label>'
                    inputs += '<input type="text" name="companion_occupation' + i + '" class="form-control"/>';

                    inputs += '</div>';
                    inputs += '</div>';

                    inputs += '<div class="col-md-3">';
                    inputs += '<div class="form-group">';

                    inputs += '<label>Date of Birth</label>'
                    inputs += '<input type="text" name="companion_dob' + i + '" class="form-control datepicker"/>';

                    inputs += '</div>';
                    inputs += '</div>';

                    inputs += '<div class="col-md-3">';
                    inputs += '<div class="form-group">';

                    inputs += '<label>Relationship with the Proposer</label>'
                    inputs += '<select type="text" name="companion_relationship' + i + '" class="form-control">' +
                        '<option value="wife">wife</option>' +
                        '<option value="husband">husband</option>' +
                        '<option value="son">son</option>' +
                        '<option value="daughter">daughter</option>' +
                        '<option value="other">other</option>' +
                        '</select>';

                    inputs += '</div>';
                    inputs += '</div>';
                    inputs += '</div>';

                    inputs += '<div class="row">';
                    inputs += '<div class="col-md-3">';
                    inputs += '<div class="form-group">';

                    inputs += '<label>Passport Number</label>'
                    inputs += '<input type="text" name="companion_passport' + i + '" class="form-control"/>';

                    inputs += '</div>';
                    inputs += '</div>';

                    inputs += '<div class="col-md-3">';
                    inputs += '<div class="form-group">';

                    inputs += '<label>Plan</label>'
                    inputs += '<input type="text" name="companion_plan' + i + '" class="form-control"/>';

                    inputs += '</div>';
                    inputs += '</div>';

                    inputs += '<div class="col-md-3">';
                    inputs += '<div class="form-group">';

                    inputs += '<label>No of days</label>'
                    inputs += '<input type="number" name="companion_no_of_days' + i + '" class="form-control"/>';

                    inputs += '</div>';
                    inputs += '</div>';
                    inputs += '</div>';
                    inputs += '<hr/>';
                }
            }
            $('div#companion_inputs').html(inputs);
        },
        generateQuote: function (site_path, quote_id) {
            $.ajax({
                url: site_path + '/admin/quote/getquote/travel',
                type: 'GET',
                success: function (data) {
                    console.log(quote_id);
                    // show actual quote
                    $('.travel').html(data);

                    // set it
                    var return_to = encodeURI('policies/createpolicy/' + quote_id);
                    $(document).find('#proceed_with_policy').attr('href', site_path + '/ajax/admin/quotes/internalacceptquote/' + quote_id + '?return=' + return_to);
                }
            })
        },
        switchToQuote: function () {
            // loading...
            $('.travel').html($('span#preloader').html());

            // show quote tab
            $('.tab4').removeClass('hide');

            // switch to tab4
            $('li > a[href=#tab4]').click();
        }
    };

    var site_path = $('#site_path').val();

    // initialize datepicker
    $(".datepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, maxDate: '-15Y'});

    // inline radio
    $(document).find('input[id^="cover_plan"], input[id^="add_travel_companions"], input[id^="i_agree"]').css('display', 'inline');

    $('input:radio[id^="add_travel_companions"]').on('click', function () {
        Common.makeMandatory($(this).val(), 'select#no_of_travel_companions');

        if (!parseInt($(this).val()))
            $('div#companion_inputs').html('');
    });

    /**
     * Step 2
     */
    $('input:radio[id^="physical_disability"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#phy_dis_particulars');
    });

    $('input:radio[id^="good_health"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#health_particulars');
    });

    $('input:radio[id^="medical_treatment"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#med_treat_particulars');
    });

    $('input:radio[id^="disorders"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#dis_particulars');
    });

    $('input:radio[id^="cancelled_prev_insurance"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#prev_ins_cancelled');
    });

    $('input:radio[id^="already_insured"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#already_ins_particulars');
    });

    $('input:radio[id^="claimed"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#claimed79_particulars');
    });

    /**
     * Step 3
     */
    $('input:radio[id^="i_agree"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#gen_quote');
    });

    $('#no_of_travel_companions').on('change', function () {
        var no_of_companions = $(this).val();

        Travel.loadMore(no_of_companions);
    });

    // inline radio
    $(document).find('input[id^="cover_plan"], input[id^="add_travel_companions"], input[id^="i_agree"]').css('display', 'inline');

    sub_btn.click(function () {
        Forms2.isFormValid();
    });
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        Forms2.updateBtn(e);
    });
    $('#prev').click(function () {
        Forms2.moveTab('prev');
    });
    $('#next').click(function () {
        Forms2.moveTab('next');
    });
    var Forms2 = {
        isFormValid: function () {
            var isFormValid = true;
            $('input:required,select:required,textarea:required').each(function () {
                if ($.trim($(this).val()).length == 0 || $.trim($(this).val()) == '0.00') {
                    $(this).parent().addClass('form-group has-error');
                    isFormValid = false;
                }
                else {
                    $(this).parent().removeClass('has-error');
                }
            });
            if (!isFormValid) {
                var it = $('.has-error:first').parents('div.tab-pane').attr('id');
                $('a[href=#' + it + ']').click();
            } else {
                var completed = false,
                    quote_id = '';

                Travel.switchToQuote();

                // loop and submit all forms
                $('form').each(function () {
                    var form = $(this);

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize() + '&request_type=__ajax',
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if (data.success) {
                                completed = true;

                                if (data.quote_id != undefined)
                                    quote_id = data.quote_id;

                            } else {
                                completed = false;
                            }
                        }
                    });
                });

                var site_path = $('#site_path').val();

                // redirect to the next tab if all the forms saved successfully
                if (completed) {
                    // generate the quote
                    Travel.generateQuote(site_path, quote_id);

                    return false;
                }
            }
        },
        updateBtn: function (e) {
            var link = $(e.target).attr('href');
            sub_btn.hide();
            next_btn.hide();
            prev_btn.hide();
            if (link === '#tab3') {
                next_btn.hide();
                prev_btn.show();
                sub_btn.show();
            } else if (link === '#tab1') {
                prev_btn.hide();
                next_btn.show();
            } else {
                prev_btn.show();
                next_btn.show();
            }
        },
        moveTab: function moveTab(nextOrPrev) {
            var target = $(".nav-tabs li.active");
            if (nextOrPrev === "next") {
                sibbling = target.next();
            } else {
                sibbling = target.prev();
            }
            console.log(target,sibbling);
            if (sibbling.is("li")) {
                sibbling.children("a").tab("show");
            }
        }
    };

    var Loader = {
        autoFill: function (customer) {
            $.each(customer, function (index, value) {
                var selector = $('[name=' + index + ']');

                if (selector.is(':checkbox')) {
                    selector.attr('checked', 'checked');
                } else if (selector.is(':radio')) {
                    $('input[name=' + index + '][value=' + value + ']').attr("checked", "checked");
                } else {
                    selector.val(value);
                }
            })
        }
    };

    if (typeof customer_data !== 'undefined') {
        Loader.autoFill(customer_data);
    }

    if (typeof product_info !== 'undefined') {
        Loader.autoFill(product_info);
    }

    var companions = $('#no_of_travel_companions').removeAttr('disabled').val();
    Travel.loadMore(companions);

    if (typeof entity_data !== 'undefined') {
        Loader.autoFill(entity_data);
    }
});