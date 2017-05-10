$(function () {
    var sub_btn = $('#btnSubmit');
    var main_form = $('form#mainform');
    var QUOTE_ID = null;
    var IS_FORM_VALID = true;
    var Common = {
        createTabs: function () {
            for (var i = 0; i < 4; i++) {
                $('.killer' + i).parents('div.form-group').replaceWith("<div id='mine" + i + "'></div>");
            }
            $('#mine0').nextUntil('div#mine1').wrapAll('<div class="tab-pane active" id="tab1"></div>');
            $('#mine1').nextUntil('div#mine2').wrapAll('<div class="tab-pane" id="tab2"></div>');
            $('#mine2').nextUntil('div#mine3').wrapAll('<div class="tab-pane" id="tab3"></div>');
            for (var i = 0; i < 3; i++) {
                $('#mine' + i).remove();
            }
            var before = '<ul class="nav nav-tabs"><li class="active"><a href="#tab1" data-toggle="tab">Proposer Personal Details</a></li><li><a href="#tab2" data-toggle="tab">Insurance Entity Details</a></li><li><a href="#tab3" data-toggle="tab">Cover Details</a></li></ul>';
            $('.tab-pane').wrapAll('<div class="tab-content"></div>');
            $(before).insertBefore('div.tab-content');
        },
        datePickers: function () {
            $("#dob").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, maxDate: '-15Y'});
            $("#coverstart").datepicker({
                minDate: 0, dateFormat: 'yy-mm-dd', changeMonth: true,
                onSelect: function (date) {
                    var date2 = new Date(date);
                    date2.setFullYear(date2.getFullYear() + 1);
                    $('#coverend').datepicker('setDate', date2);
                }
            });
            $("#coverend").datepicker({dateFormat: 'yy-mm-dd'/*, disabled: true*/});
        },
        isFormValid: function () {
            IS_FORM_VALID = true;
            $('input:required,select:required,textarea:required').each(function () {
                if ($.trim($(this).val()).length == 0 || $.trim($(this).val()) == '0.00') {
                    $(this).parent().addClass('form-group has-error');
                    IS_FORM_VALID = false;
                } else {
                    $(this).parent().removeClass('has-error');
                }
            });
            if (!IS_FORM_VALID) {
                var it = $('.has-error:first').parents('div.tab-pane').attr('id');
                $('a[href=#' + it + ']').click();
            } else {
                Quote.submitForm();
            }
        },
        inUrl: function (slung) {
            return window.location.href.indexOf(slung) > -1;
        },
        setUrl: function () {
            var link_suffix = null;
            if (Common.inUrl('/motor')) {
                Motor.additionalCovers();
                link_suffix = "motor"
            } else if (Common.inUrl('/accident')) {
                Accident.additionalCovers();
                link_suffix = "accident";
            } else if (Common.inUrl('/domestic')) {
                link_suffix = "domestic";
            }
            main_form.attr('action', SITE_PATH + '/admin/myquote/save/' + link_suffix);
        }
    };
    var Motor = {
        addContent: function (count) {
            var uri = SITE_PATH + "/motor/others/" + count;
            $.get(uri, function (response) {
                var res = Motor.cleanResponse(response);
                $('#tab22').html(res);
                $('#btnToSubmit').parents('div.form-group').remove();
            }
            );
        },
        cleanResponse: function (text) {
            var wrapped = $("<div>" + text + "</div>").find('form');
            return wrapped.contents().unwrap().wrapAll('div');
        },
        additionalCovers: function () {
            var can_display = false;
            var counter = $('select[name=othercovers]');
            var enabler = $('input[name=somecovers]');
            counter.prop('disabled', true);
            enabler.change(function () {
                if (this.value === 'yes') {
                    can_display = true;
                    counter.prop('disabled', false);
                    counter.val(1).trigger('change');
                } else {
                    can_display = false;
                    counter.val('').trigger('change');
                    counter.prop('disabled', true);
                }
            });
            counter.change(function () {
                if (this.value == '') {
                    $('li:has(a[href="#tab22"])').remove();
                    $('#tab22').remove();
                    return;
                }
                if (!$('#tab22').length) {
                    $('ul.nav li:last').before('<li><a href="#tab22" data-toggle="tab">Other Car Details</a></li>');
                    $(this).parents('div.tab-pane').after('<div class="tab-pane" id="tab22">' + PRELOADER2 + '</div>');
                }
                Motor.addContent($(this).val());
            });
        }
    };
    var Accident = {
        additionalCovers: function () {
            var can_display = false;
            var counter = $('select#howmany');
            var enabler = $('input[name=other_covers]');
            counter.prop('disabled', true);
            var loadash = $('div#other_covers_div');

            enabler.change(function () {
                if (this.value === 'yes') {
                    can_display = true;
                    counter.prop('disabled', false);
                    counter.val(1).trigger('change');
                } else {
                    can_display = false;
                    counter.prop('disabled', true);
                    loadash.html("");
                }
            });
            var Accident = {
                relationships: {
                    wife: "Wife",
                    husband: "Husband",
                    son: "Son",
                    daughter: "Daughter",
                    other: "Other"
                },
                agesBracket: {},
                loadMore: function (no) {
                    var inputs = '';
                    if (no) {
                        for (var i = 1; i <= no; i++) {
                            inputs += '<div class="row">';
                            inputs += '<div class="col-md-2">';
                            inputs += '<div class="form-group">';

                            inputs += '<label>Name</label>';
                            inputs += '<input type="text" name="other_name' + i + '" class="form-control"/>';

                            inputs += '</div>';
                            inputs += '</div>';

                            inputs += '<div class="col-md-2">';
                            inputs += '<div class="form-group">';

                            inputs += '<label>Relationship</label>';
                            inputs += '<select name="other_relationship' + i + '" class="form-control">' +
                                    '<option value="Wife">Wife</option> ' +
                                    '<option value="Husband">Husband</option> ' +
                                    '<option value="Son">Son</option>' +
                                    '<option value="Daughter">Daughter</option>' +
                                    '<option value="Other">Other</option> ' +
                                    '</select>';

                            inputs += '</div>';
                            inputs += '</div>';

                            inputs += '<div class="col-md-2">';
                            inputs += '<div class="form-group">';

                            inputs += '<label>Age Bracket</label>';
                            inputs += '<select name="other_bracket' + i + '" class="form-control">' +
                                    '<option value="3-17">3-17</option>' +
                                    '<option value="18-21">18 - 21</option>' +
                                    '<option value="22-25">22 - 25</option>' +
                                    '<option value="26-30">26 - 30</option>' +
                                    '<option value="31-40">31 - 40</option>' +
                                    '<option value="41-50">41 - 50</option>' +
                                    '<option value="51-60">51 - 60</option>' +
                                    '<option value="61-69">61 - 69</option>' +
                                    '<option value="70 or over">70 or over</option>' +
                                    '</select>';

                            inputs += '</div>';
                            inputs += '</div>';

                            inputs += '<div class="col-md-2">';
                            inputs += '<div class="form-group">';

                            inputs += '<label>Education</label>';
                            inputs += '<select name="other_education' + i + '" class="form-control">' +
                                    '<option value="Primary">Primary</option>' +
                                    '<option value="Secondary">Secondary</option>' +
                                    '<option value="College">College</option>' +
                                    '</select>';

                            inputs += '</div>';
                            inputs += '</div>';

                            inputs += '<div class="col-md-2">';
                            inputs += '<div class="form-group">';

                            inputs += '<label>Band</label>';
                            inputs += '<select name="other_band' + i + '" class="form-control">' +
                                    '<option value="band1">Band 1</option>' +
                                    '<option value="band2">Band 2</option>' +
                                    '<option value="band3">Band 3</option>' +
                                    '<option value="band4">Band 4</option>' +
                                    '<option value="band5">Band 5</option>' +
                                    '<option value="band6">Band 6</option>' +
                                    '<option value="band7">Band 7</option>' +
                                    '</select>';

                            inputs += '</div>';
                            inputs += '</div>';

                            inputs += '<div class="col-md-2">';
                            inputs += '<div class="form-group">';

                            inputs += '<label>Plan</label>';
                            inputs += '<select name="other_class' + i + '" class="form-control">' +
                                    '<option value="class1">Class I</option>' +
                                    '<option value = "class2">Class II </option>' +
                                    '</select>';

                            inputs += '</div>';
                            inputs += '</div>';

                            inputs += '</div>';
                            inputs += '<hr/>';
                        }
                    }
                    loadash.html(inputs);
                }
            };
            counter.change(function () {
                if (can_display) {
                    Accident.loadMore(parseInt($(this).val()));
                }
            });
        }
    };
    var Quote = {
        submitForm: function () {
            $.ajax({
                url: main_form.attr('action'),
                dataType: 'json',
                method: 'POST',
                data: main_form.serialize(),
                success: function (response) {
                    if (response.quote) {
                        QUOTE_ID = response.quote;
                        $('#quote_id').val(QUOTE_ID);
                    }
                    Quote.preview();
                }
            });
        },
        createTab: function () {
            Quote.removeTabs();
            if (!$('#tab4').length) {
                $('ul.nav li:last').after('<li><a href="#tab4" data-toggle="tab">Quotation</a></li>');
                $('#tab3').after('<div class="tab-pane" id="tab4"><center>' + PRELOADER2 + '</center></div>');
                $('a[href=#tab4]').click();
            }
        },
        removeTabs: function () {
            $('li:has(a[href="#tab4"])').remove();
            $('#tab4').remove();
        },
        preview: function () {
            $.ajax({
                url: SITE_PATH + '/admin/myquote/view/' + QUOTE_ID,
                method: 'GET',
                beforeSend: function () {
                    Quote.createTab();
                },
                success: function (response) {
                    var wrapped = $("<div>" + response + "</div>");
                    wrapped.find('button').remove();
                    $('#tab4').html(wrapped);
                },
                error: function (response) {
                    //  Quote.removeTabs();
                    $('#tab4').html("<p>Cannot generate quotation</p><pre>" + response + "</pre>");
                }
            });
        }
    };
    var Loader = {
        autoFill: function (customer) {
            $.each(customer, function (index, value) {
                var input = $('[name=' + index + ']');
                if (input.is(':radio')) {
                    $('[name=' + index + "][value=" + value + "]").attr('checked', 'checked');
                } else if (input.is(':checkbox')) {
                    $('[name=' + index + "][value=" + value + "]").prop('checked', true);
                } else {
                    input.val(value);
                }
            })
        }
    };
    sub_btn.click(function () {
        Common.isFormValid();
    });
    Common.createTabs();
    Common.datePickers();
    Common.setUrl();
    if (typeof customer_data !== 'undefined') {
        Loader.autoFill(customer_data);
    }
});