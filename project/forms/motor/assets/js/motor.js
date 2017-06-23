$(function () {
    var Loader = {
        autoFill: function () {
            $.each(customer_data, function (index, value) {
                var input = $('[name=' + index + ']');
                if (input.is(':radio')) {
                    $('[name=' + index + "][value=" + value + "]").attr('checked', 'checked');
                } else if (input.is(':checkbox')) {
                    $('[name=' + index + "][value=" + value + "]").prop('checked', true);
                } else if (input.is('select')) {
                    input.val(value);//.trigger('change');
                } else {
                    input.val(value);
                }
            });
        }
    };
    if ($("#motor_car_details").length) {
        var can_display = false;
        var counter = $('select[name=othercovers]');
        var enabler = $('input[name=somecovers]');

        var nextBtn = $('#btnSubmitSpecial');
        var btnText = "Proceed to Cover Details >>";
        var btnAltText = "Add additional car Details >>";
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
            if (!this.value) {
                nextBtn.prop('value', btnText);
            } else {
                btnAltText = "Add " + this.value + " additional cars Details >>";
                nextBtn.prop('value', btnAltText);
            }
        });
    }
    if ($('#motor_personal_details').length) {
        try {
            Loader.autoFill();
        } catch (e) {
        }
    }

});