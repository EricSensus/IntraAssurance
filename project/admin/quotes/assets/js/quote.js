$(function () {
    $('head').append('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css">');
    $('head').append('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">');
    $('body').on('focus', ".mydatepicker", function () {
        var my_format = $(this).attr('dervis-date');
        if (typeof my_format == 'undefined') {
            my_format = 'yy-mm-dd';
        }
        $(this).datepicker({dateFormat: my_format});
    });
    if (typeof SITE_PATH == 'undefined') {
        return;
    }
    var productid = null;
    var __onscreen = [];
    var required_fields = [];
    //buttons
    var add_entity_button = $('button.addentity');
    var submit_button = $('input[name="btnsubmit"]');
    var product_selector = $('select[name=product]');
    var customer_input = $('input[name=customer]');
    var customer_product_information = $('div.customer_product_information');

    submit_button.addClass('disabled').attr('disabled', true);
    add_entity_button.hide();
    product_selector.val('');

    //process customer details
    customer_input.devbridgeAutocomplete({
        serviceUrl: SITE_PATH + '/ajax/admin/quotes/getcustomer',
        minChars: 1,
        onSearchStart: function (query) {
            var searchinput = $(this).val();
            $('.autocomplete-suggestions').html('Searching: ' + searchinput);
        },
        onSelect: function (suggestion) {
            var selection = $(this).val(suggestion.value);
            $('input[name=customerid]').val(suggestion.data);

            $.get(SITE_PATH + '/ajax/admin/quotes/getcustomerdetails',
                {
                    id: suggestion.data
                },
                function (response) {
                    var obj = $.parseJSON(response);
                    $.each(obj, function (key, value) {
                        $('#' + key).val(value);
                    });
                });
        }
    });
    //process the product details
    product_selector.on('change', function () {
        productid = $(this).val();
        var customerid = $('input[name=customerid]').val();
        __onscreen = [];
        if (productid != '') {
            $.ajax({
                url: SITE_PATH + '/ajax/admin/products/getfullproductform/',
                method: 'post',
                data: {
                    id: productid
                },
                beforeSend: function () {
                    customer_product_information.html(PRELOADER1);
                },
                success: function (response) {
                    customer_product_information.empty();
                    $('tr.extra_form_fields').remove();
                    $('table.product-details tr:last').after(response);
                },
                error: function (response) {
                    customer_product_information.html(response);
                }
            });

            $.ajax({
                url: SITE_PATH + '/ajax/admin/entities/selectformfromproductid/',
                method: 'post',
                data: {
                    ajax: 'yes',
                    productid: productid,
                    customerid: customerid
                },
                beforeSend: function () {
                    $('div.customer_entity_select').html(PRELOADER2);
                },
                success: function (response) {
                    $('#interimSave').parents("tr").remove();
                    $('div.customer_entity_select').html(response);
                    var selectvalue = $('select[name=entities]').val();
                    var newentity = $('input[name=newentity]').val();
                    if (selectvalue != newentity && selectvalue != '') {
                        add_entity_button.show();
                    }
                }
            });

            addRequireds(productid);
        } else {
            $('tr.extra_form_fields').remove();
            $('table.product-details tr:last').after('');
            $('div.customer_entity_select').html('');
            customer_product_information.html('');
        }
        setCustomerEntities();
        previewQuote();
    });

    //get the customer entity selection - new entity
    $('div.customer_entity_select').on('change', 'select', function () {
        $('#interimSave').parents("tr").remove();
        var selectvalue = $('select[name=entities]').val();
        var defaultvalue = $('input[name=entityformid_' + selectvalue + ']').val();
        var newentity = $('input[name=newentity]').val();
        if (selectvalue == newentity) {
            add_entity_button.hide();
            $('table.entity-details tr.extra_form_fields').remove();
            $('div.customer_entities').html(PRELOADER1);

            $.ajax({
                url: SITE_PATH + '/ajax/admin/entities/getfullentityform',
                method: 'post',
                data: {
                    defaultval: defaultvalue
                },
                success: function (response) {
                    $('div.customer_entities').empty();
                    $('table.entity-details tr:last').after(response);
                    $('table.entity-details tr:last').after("<tr><td></td><td><button id='interimSave'>Save Entity</button></td></tr>");
                }
            });
        }
        else {
            if (selectvalue != newentity && selectvalue != '') {
                add_entity_button.show();
            }
            $('table.entity-details tr.extra_form_fields').remove();
        }
    });

    //add saved entity
    add_entity_button.on('click', function () {
        var selectvalue = $('select[name=entities]').val();
        var exist = __onscreen.filter(function (object) {
            return object.id === selectvalue;
        });
        if (exist.length > 0) {
            console.log('Already in view');
            return;
        }
        $('div.customer_entities').html(PRELOADER1);
        $.ajax({
            url: SITE_PATH + '/ajax/admin/entities/returnentityentries',
            method: 'post',
            data: {
                ajax: 'yes',
                entityval: selectvalue
            },
            success: function (response) {
                __onscreen.push({id: selectvalue, content: response});
                setCustomerEntities();
                previewQuote();
                $('select[name=entities] option[value=\"' + selectvalue + '\"]').remove();
            }
        });
    });
    function setCustomerEntities() {
        $('div.customer_entities').empty();
        $.each(__onscreen, function (index, value) {
            $('div.customer_entities').append(value.content);
        });
    }

    //remove stored entity
    $('div.customer_entities').on('click', 'a', function () {
        var accept = confirm('This will delete this entity. Proceed?');
        if (accept) {
            var entityid = $(this).attr('id');
            $('.' + entityid).remove();
            __onscreen = __onscreen.filter(function (obj) {
                return obj.id !== entityid;
            });
            previewQuote();
        }
    });


    function isFormValid() {
        var isFormValid = true;

        //process the entire form
        var qform = $('#quoteform').data('Zebra_Form');

        if (qform.validate()) {

            $('input.required').each(function () {
                if ($.trim($(this).val()).length == 0 || $.trim($(this).val()) == '0.00') {
                    $(this).addClass('redline');
                    isFormValid = false;
                }
                else {
                    $(this).removeClass('redline');
                }
            });
            $('select.required').each(function () {
                if ($.trim($(this).val()).length == 0) {
                    $(this).addClass('redline');
                    isFormValid = false;
                }
                else {
                    $(this).removeClass('redline');
                }
            });
        }
        return isFormValid;
    }

    submit_button.on('click', function (e) {
        // stop default action
        e.preventDefault();
        if (!isFormValid()) {
            $('html, body').animate({
                scrollTop: $('.redline').first().offset().top
            }, 1000);
        }
        else {
            $('#quoteform').submit();
        }
    });

    $('#previewer').click(function (e) {
        e.preventDefault();
        previewQuote();
    });
    $('body').on('click', '#interimSave', function (e) {
        e.preventDefault();
        var form = $('#quoteform');
        var problems = checkFields();
        if (problems.length > 0) {
            isFormValid();
            return;
        }
        $.ajax({
            url: SITE_PATH + '/admin/preview/saveentity',
            method: 'post',
            data: form.serialize(),
            success: function (response) {
                var obj = $.parseJSON(response);
                $('select[name=entities]').append($('<option>', {
                    value: obj.id,
                    text: obj.name
                }));
                $('select[name=entities]').val(obj.id).change();
                $('#interimSave').parents("tr").remove();
                add_entity_button.show();
                add_entity_button.click();
            },
            error: function (response) {
                console.log(response);
            }
        });
    });
    function addRequireds(id) {
        switch (id) {
            case "1":
                required_fields = ['Cover_Type', 'Cover_Start', 'Cover_End'];
                break;
            default:
                required_fields = [];
                break;
        }
    }

    function checkFields() {
        var problems = [];
        $.each(required_fields, function (index, value) {
            var found = $("[name=" + value + "]").val();
            if (found == '') {
                problems.push(value);
            }
        });
        if (__onscreen.length < 1) {
            problems.push("Entity");
        }
        if ($('option.addnew').is(':selected')) {
            problems.pop("Entity");
        }
        $('.required').each(function (i, obj) {
            var found = $(this).val();
            if (found == '') {
                problems.push($(this).attr('name'));
            }
        });
        return problems.filter(function (e) {
            return e;
        });
    }

    function previewQuote() {
        submit_button.addClass('disabled').attr('disabled', true);
        $('#quote-preview').html(PRELOADER1);
        var form = $('#quoteform');
        var problems = checkFields();
        if (problems.length > 0) {
            isFormValid();
            $('#quote-preview').html("Cannot preview at the moment<br>Please fill the following fields : " + problems);
            return;
        }

        $.ajax({
            url: SITE_PATH + '/admin/preview/quote',
            method: 'post',
            data: form.serialize(),
            success: function (response) {
                $('#quote-preview').html(response);
                submit_button.removeClass('disabled').attr('disabled', false);
            },
            error: function (response) {
                $('#quote-preview').html("This quote could not be previewed<br/>" + response);
            }
        });
    }

    $('body').on('focus', ".date_start", function () {
        $(this).datepicker('option', 'onSelect', function (date) {
            var end_element = $('input.date_end');
            end_element.datepicker('destroy');
            end_element.removeClass("hasDatepicker").removeClass('mydatepicker').removeAttr('id');
            end_element.attr('readonly', true);
            var start_date = $(this).datepicker("getDate");
            if (start_date != '') {
                var date2 = new Date(start_date);
                date2.setFullYear(date2.getFullYear() + 1);
                $('input.date_end').val(date2.toISOString().slice(0, 10));
            }
        });
    });
});
