$(function () {
    var sub_btn = $('#btnSubmit');
    var next_btn = $('#next');
    var prev_btn = $('#prev');
    sub_btn.hide();
    prev_btn.hide();
    var Medical = {
        tab: 'tab2',
        addDependantTab: function (form) {
            $('.tab22').removeClass('hide');

            // add form
            $('div#tab22').html(form);
        },
        removeDependantTab: function () {
            $('.tab22').addClass('hide');
        },
        generateQuote: function (site_path, quote_id) {
            $.ajax({
                url: site_path + '/admin/quote/getquote/medical',
                type: 'GET',
                success: function (data) {
                    // show actual quote
                    $('.med').html(data);

                    // set it
                    var return_to = encodeURI('policies/createpolicy/' + quote_id);
                    $(document).find('#proceed_with_policy').attr('href', site_path + '/ajax/admin/quotes/internalacceptquote/' + quote_id + '?return=' + return_to);
                }
            })
        },
        switchToQuote: function () {
            // loading...
            $('.med').html($('span#preloader').html());

            // show quote tab
            $('.tab4').removeClass('hide');

            // switch to tab4
            $('li > a[href=#tab4]').click();
        }
    }
    var site_path = $('#site_path').val();

    // initialize datepicker
    $(".datepicker").datepicker({dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, maxDate: '-15Y'});

    // inline radio
    $(document).find('input:radio, input:checkbox').css('display', 'inline');

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

                Medical.switchToQuote();

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

                if (completed) {
                    // generate the quote
                    Medical.generateQuote(site_path, quote_id);

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
            if (sibbling.is("li")) {
                sibbling.children("a").tab("show");
            }
        }
    };

    $('#additional_covers').on('change', function () {
        var control = $(this);
        var dependants = parseInt(control.val());

        if (dependants >= 1) {
            $.ajax({
                url: site_path + '/medical/load/' + dependants,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        Medical.addDependantTab(data.form.form);
                    }
                }
            });
        } else {
            Medical.removeDependantTab();
        }
    });

    // initialize popover
    $('[data-toggle="popover"]').popover({
        container: 'body'
    });

    // highlight selected plan on click of a radio
    $('input[name="core_plans"]').on('click', function () {
        $('input[name="core_plans"]').each(function () {
            $(this).parents('div.panel:first').removeClass('highlight-plan');
        });
        $(this).parents('div.panel:first').addClass('highlight-plan');
    });

    /**
     * Step Two validation
     * Dependants
     */
    $('input[name="have_dependants"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#additional_covers');
    });

    /**
     * Step Three validation
     * yes or no answers
     */
    $('input[name="ever_cover_declined"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#declined_particulars');
    });

    $('input[name="dependant_claimed_cover"]').on('click', function () {
        Common.makeMandatory($(this).val(), '#dep_claimed_particulars');
    });

    // $('input:radio[id^="i_agree"]').on('click', function(){
    //     var agree = parseInt($(this).val());
    //     // alert(agree);
    //     if(agree){
    //         $('#btnsubmit').removeAttr('disabled');
    //     }else{
    //         $('#btnsubmit').attr('disabled', 'disabled');
    //     }
    // });

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
            });
        }
    };
    if (typeof customer_data !== 'undefined') {
        Loader.autoFill(customer_data);
    }

    if (typeof product_info !== 'undefined') {
        Loader.autoFill(product_info);
    }

    var additional_entities = $('#additional_covers').removeAttr('disabled').val();
    if (additional_entities >= 1) {
        $.ajax({
            url: site_path + '/medical/load/' + additional_entities,
            type: 'GET',
            dataType: 'json',
            async: false,
            success: function (data) {
                if (data.success) {
                    Medical.addDependantTab(data.form.form);
                }
            }
        });
    } else {
        Medical.removeDependantTab();
    }

    if (typeof entity_data !== 'undefined') {
        Loader.autoFill(entity_data);
    }
});