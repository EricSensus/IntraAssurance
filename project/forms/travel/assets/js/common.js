var Common = {
    makeMandatory: function(answer, field){
        if(answer == 'yes' || parseInt(answer) == 1)
            $(field).attr('required', 'required').removeAttr('disabled');
        else
            $(field).removeAttr('required').attr('disabled', 'disabled').val('');
    }
};