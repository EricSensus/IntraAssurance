<?php
//print_r(get_defined_vars());
//exit;
?>
<table width="100%" border="0" cellpadding="10" class="policy table-striped">
    <tr>
        <td colspan="2" class="heading">
            <h2 class="mb5 text-light">Claim details</h2>
        </td>
    </tr>
</table>
    <div class="form-group">
        <?= $label_subject ?>
        <?= $subject ?>
    </div>
    <div class="form-group">
        <?= $label_description ?>
        <?= $description ?>
    </div>
        <?=$attach_image = '<div class="form-group"><label class="col-md-2 control-label">Attach files</label><div class="col-md-6"><input type="file" name="attached_files[]" class="form-control"/></div></div>'; ?>
        <div class="col-md-4">
            <button type="button" id="attach_more" class="btn btn-default"><i class="fa fa-plus"></i> Attach More...</button>
        </div>
        <div id="more_attachments"></div>
        <br/>
        <div class="form-group">
            <button type="submit" class="btn btn-lg btn-success" id="filenow"><i class="fa fa-arrow-circle-o-right"></i>File Claim</button>
        </div>
        <!--hidden fields-->
        <input type="hidden" name="element" value="claims"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>
<script>
    $(function () {
        // add more file upload fields
        $('#attach_more').on('click', function () {
            var blueprint = '<?=$attach_image; ?>';
            blueprint += '<div class="col-md-4">' +
                '<button type="button" class="btn btn-danger remove_attachment">' +
                '<i class="fa fa-trash"></i> Remove</button></div>';

            $(blueprint).appendTo('#more_attachments');
        });

        // remove upload fields
        $(document).on('click', '.remove_attachment', function(){
            $(this).closest('.col-md-4').prev().remove();
            $(this).closest('.col-md-4').remove();
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        tinymce.init({
            selector: 'textarea',
            height: 200,
            menubar: false,
            plugins: [
                'advlist autolink lists link  charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link ',
            content_css: '//www.tinymce.com/css/codepen.min.css'
        });
    });
</script>