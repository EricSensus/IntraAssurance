<?= $addform;
?>
<!-- @todo Make sure password matches -->
<script type="text/javascript">
    $(function () {
        $('#save_button').on('click', function (evt) {

            // stop default action
            evt.preventDefault();
            var isFormValid = true;

            //process the entire form
            var qform = $('#credentialsform').data('Zebra_Form');

            if (qform.validate()) {

                $('input.modal_required').each(function () {
                    if ($.trim($(this).val()).length == 0) {
                        $(this).addClass('redline');
                        isFormValid = false;
                    }
                    else {
                        $(this).removeClass('redline');
                    }
                });
                $('select.modal_required').each(function () {
                    if ($('select.modal_required').val() == '') {
                        $(this).addClass('redline');
                        isFormValid = false;
                    }
                    else {
                        $(this).removeClass('redline');
                    }
                });

                if (isFormValid == false) {
                    $('html, body').animate({
                        scrollTop: $('.redline').first().offset().top
                    }, 1000);
                }
                else {

                    qform.submit();
                }
            }
        });
    });
</script>


