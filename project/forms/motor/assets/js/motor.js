$(function () {
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
    $('head').append('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css">');
    $('head').append('<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">');
});