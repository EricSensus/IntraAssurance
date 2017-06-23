var Common = {
    makeMandatory: function(answer, field){
        var answer = parseInt(answer);
        if(answer)
            $(field).attr('required', 'required').removeAttr('disabled');
        else
            $(field).removeAttr('required').attr('disabled', 'disabled').val('');
    }
};