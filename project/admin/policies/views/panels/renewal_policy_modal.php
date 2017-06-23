<?=$modal_content; ?>
<script>
    // temporary fix for the non submitting renewal modal
    $(function(){
        $('#renew_pol_btn').on('click', function(){
            $('form#renewal_modal_form').submit();
        });
    });
</script>
