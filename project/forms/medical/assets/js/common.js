var Common = {
    makeMandatory: function(answer, field){
        console.log('Answer: '+answer);
        console.log('Field: '+field);
        if(answer == 'yes' || parseInt(answer) == 1)
            $(field).attr('required', 'required').removeAttr('disabled');
        else
            $(field).removeAttr('required').attr('disabled', 'disabled').val('');
    }
};