<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;

echo $addform;
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>
<script>
    var Email = {
        createPDFAttachment: function () {
            $.ajax({
                url: '<?=Url::link('/admin/quotes/createAttachment'); ?>',
                type: 'post',
                data: {
                    id: <?=$id; ?>
                },
                beforeSend: function () {
                    $('.modal-footer').css({
                        opacity: '0.3',
                        'pointer-events': 'none'
                    });
                },
                success: function (data) {
                    $('.modal-footer').css({
                        opacity: '1',
                        'pointer-events': 'all'
                    });
                    $('#content').after(data);
                }
            });
        }
    };

    $(function () {
        $('#sendmode').on('change', function () {
            tinymce.init({selector: 'textarea'});

            var sendmode = $(this).val();

            if (sendmode == '1') {
                $('.email_content').html("<?=$preview_link_content; ?>");
                $('#content').after('');
                $('.email_attachment').remove();
                tinyMCE.activeEditor.setContent("<?=$preview_link_content; ?>");
            }

            if (sendmode == '2') {
                $('.email_content').html("<?=$pdf_content; ?>");
                Email.createPDFAttachment();
                tinyMCE.activeEditor.setContent("<?=$pdf_content; ?>");
            }
        });

        $('#save_button').on('click', function (evt) {

            // stop default action
            evt.preventDefault();
            var isFormValid = true;

            //process the entire form
            var qform = $('#emailform').data('Zebra_Form');

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

