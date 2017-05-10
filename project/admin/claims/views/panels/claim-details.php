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
<form method="post">
    <div class="form-group">
        <?= $label_subject ?>
        <?= $subject ?>
    </div>
    <div class="form-group">
        <?= $label_description ?>
        <?= $description ?>
    </div>
    <div>
        <button type="submit" class="btn btn-lg btn-success" id="filenow"><i class="fa fa-arrow-circle-o-right"></i>
            File
            claim
        </button>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.6/tinymce.min.js"></script>
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