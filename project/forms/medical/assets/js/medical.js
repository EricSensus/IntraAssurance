$(function() {
    $('.datepicker').datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    // inline radio
    $(document).find('input:radio, input:checkbox').css('display', 'inline');

    // initialize popover
    $('[data-toggle="popover"]').popover({
        container: 'body'
    });

    // highlight selected plan on click of a radio
    $('input[name="core_plans"]').on('click', function(){
        $('input[name="core_plans"]').each(function(){
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

    $('input:radio[id^="i_agree"]').on('click', function(){
        var agree = parseInt($(this).val());
        // alert(agree);
        if(agree){
            $('#btnsubmit').removeAttr('disabled');
        }else{
            $('#btnsubmit').attr('disabled', 'disabled');
        }
    });
});