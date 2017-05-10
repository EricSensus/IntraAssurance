/**
 * Created by eric on 06/03/2017.
 */
var Policy = {
    /**
     * Save quote and redirect to step two for policy creation
     * @param form
     */
    saveQuote: function(form){
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize() + '&request_type=__ajax',
            dataType: 'json',
            beforeSend: function () {
                $('#embedded_quote').html(PRELOADER1);
            },
            success: function (data) {
                if(data.success){
                    // redirect to the second step of the policy creation
                    location.href = SITE_PATH + '/admin/policies/createpolicy/' + data.quote_id;
                }
            }
        });
    }
}
