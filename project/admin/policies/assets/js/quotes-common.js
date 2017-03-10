/**
 * Created by developer on 07/03/2017.
 */
$(function(){
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
    var __onscreen = [];
    var required_fields = [];
    $('form#quoteform input[name="btnsubmit"]').addClass('disabled').attr('disabled', true);

    $('button.addentity').hide();

    $('select[name=product]').val('');

    //process the product details
    $(document).on('change', 'select[name=product]', function () {
        var productid = $(this).val();
        var customerid = $('input[name=customerid]').val();

        if (productid != '') {

            $('div.ajaxresponse1').html(PRELOADER1);
            $('div.ajaxresponse2').html(PRELOADER2);

            $.ajax({
                url: SITE_PATH + '/ajax/admin/products/getfullproductform/',
                method: 'post',
                data: {
                    id: productid
                },
                success: function (response) {
                    $('div.ajaxresponse1').empty();
                    $('tr.extra_form_fields').remove();

                    $('table.product-details tr:last').after(response);
                },
                error: function (response) {
                    $('div.ajaxresponse1').html(response);
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
                success: function (response) {
                    $('div.ajaxresponse2').html(response);

                    var selectvalue = $('select[name=entities]').val();
                    var newentity = $('input[name=newentity]').val();

                    if (selectvalue != newentity && selectvalue != '') {
                        $('button.addentity').show();
                    }
                }
            });
            addRequireds(productid);
        }
    });

    //get the customer entity selection - new entity
    $(document).on('change', 'div.ajaxresponse2', 'select', function () {

        var selectvalue = $('select[name=entities]').val();

        var defaultvalue = $('input[name=entityformid_' + selectvalue + ']').val();
        var newentity = $('input[name=newentity]').val();

        $('select[name=insurers]').removeAttr('disabled');

        if (selectvalue == newentity) {

            $('button.addentity').hide();
            $('table.entity-details tr.extra_form_fields').remove();
            $('div.ajaxresponse3').html(PRELOADER1);

            $.ajax({
                url: SITE_PATH + '/ajax/admin/entities/getfullentityform',
                method: 'post',
                data: {
                    defaultval: defaultvalue
                },
                success: function (response) {
                    $('#interimSave').parents("tr").remove();
                    $('div.ajaxresponse3').empty();
                    $('table.entity-details tr:last').after(response);
                    $('table.entity-details tr:last').after("<tr><td></td><td><button id='interimSave'>Save Entity</button></td></tr>");
                }
            });
        }
        else {
            if (selectvalue != newentity && selectvalue != '') {
                $('button.addentity').show();
            }
            $('table.entity-details tr.extra_form_fields').remove();
        }
    });

    //add saved entity
    $(document).on('click', 'button.addentity', function () {
        var prev_html = $(document).find('div.ajaxresponse3').html();
        var selectvalue = $(document).find('select[name=entities]').val();
        if (__onscreen.indexOf(selectvalue) >= 0) {
            return;
        }
        $(document).find('div.ajaxresponse3').html(PRELOADER1);
        $.ajax({
            url: SITE_PATH + '/ajax/admin/entities/returnentityentries',
            method: 'post',
            data: {
                ajax: 'yes',
                entityval: selectvalue
            },
            success: function (response) {
                $(document).find('#insurers option[value=\"' + selectvalue + '\"]').remove();
                $(document).find('div.ajaxresponse3').empty();
                $(document).find('div.ajaxresponse3').html(prev_html + response);
                __onscreen.push(selectvalue);
                previewQuote();
            }
        });
    });

    //remove stored entity
    $(document).on('click', 'a.delete_stored_entity', function () {
        var accept = confirm('This will delete this entity. Proceed?');
        if (accept) {
            var entityid = $(this).attr('id');
            $('.' + entityid).remove();
            __onscreen.pop(entityid);
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

    $(document).on('submit', '#quoteform', function (e) {
        // stop default action
        e.preventDefault();
        if (!isFormValid()) {
            $('html, body').animate({
                scrollTop: $('.redline').first().offset().top
            }, 1000);
        }
        else {
            Policy.saveQuote($(this));
        }
    });

    $(document).on('click', '#previewer', function () {
        previewQuote();
    });
    $('body').on('click', '#interimSave', function (e) {
        e.preventDefault();
        var form = $(document).find('#quoteform');
        var problems = checkFields();
        if (problems.length > 0) {
            isFormValid();
            return;
        }
        if (!$(document).find('option.addnew').is(':selected')) {
            alert("Cannot add now");
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
                $(document).find('select[name=entities]').val(obj.id).change();
                $(document).find('#interimSave').parents("tr").remove();
                $(document).find('button.addentity').show();
                $(document).find('button.addentity').click();
            },
            error: function (response) {
                alert(response);
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
            var found = $(document).find("[name=" + value + "]").val();
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
            return e
        });
    }

    function previewQuote() {
        $(document).find('form#quoteform input[name="btnsubmit"]').addClass('disabled').attr('disabled', true);
        $(document).find('#quote-preview').html(PRELOADER1);
        var form = $(document).find('#quoteform');
        var problems = checkFields();
        if (problems.length > 0) {
            isFormValid();
            $(document).find('#quote-preview').html("Cannot preview at the moment<br>Please fill the following fields : " + problems);
            return;
        }

        $.ajax({
            url: SITE_PATH + '/admin/preview/quote',
            method: 'post',
            data: form.serialize(),
            success: function (response) {
                $(document).find('#quote-preview').html(response);
                $(document).find('form#quoteform input[name="btnsubmit"]').removeClass('disabled').attr('disabled', false);
            },
            error: function (response) {
                $(document).find('#quote-preview').html("This quote could not be previewed<br/>" + response);
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
                console.log(date2);
                date2.setFullYear(date2.getFullYear() + 1);
                $('input.date_end').val(date2.toISOString().slice(0, 10));
            }
        });
    });
});