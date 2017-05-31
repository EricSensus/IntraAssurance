<?php
use Jenga\App\Request\Url;

//get names
if($this->user()->acl == 'superadmin' || $this->user()->acl == 'admin'){  
    $names = explode(' ', $this->user()->fullname);
}
elseif($this->user()->acl == 'customer'){
    $names = explode(' ', $this->user()->customer_name);
}
?>
<script>
    $(function(){
        var setAsViewed = function(id){
            
            $.ajax({
                url: "<?= Url::link('/ajax/notices/setview/') ?>"+id,
                success: function(response){                    
                    //add disabled class
                    $('a#list-item-'+id).addClass('disabled');
                    return true;
                },
                error: function(response){
                   $(".response").html(response);
                   return false;
               }
            });
        };
        
        var deleteNotice = function(id){
            
            $.ajax({
                url: "<?= Url::link('/ajax/notices/deletenotice/') ?>"+id,
                success: function(response){                    
                    //remove item listing
                    $('div#list-item-'+id).remove();
                },
                error: function(response){
                   $(".response").html(response);
               }
            });
        };
        
        //remove notice
        $('#noticeslist span.remove-item').on('click',function(){
            
            var linkid = $(this).attr('id');            
            var id = linkid.split('-')[2];
            
            //send ajax request
            deleteNotice(id);
        });
        
        //mark notice as read
        $('#noticeslist span.mark-item').on('click',function(){
            var linkid = $(this).attr('id');
            var id = linkid.split('-')[2];
            
            //send ajax request
            setAsViewed(id);
        });
        
        //set as view when link is clicked
        $('#noticeslist a.noticelink').on('click',function(){
            
            var linkid = $(this).attr('id');
            var id = linkid.split('-')[2];
            
            var href = $(this).attr('data-system-location');
            
            //send ajax request
            setAsViewed(id);
            
            //redirect link
            window.location.href = href;
        });
        
    });
</script>
<div class="modal-content">
    <div class="modal-header">
     <h4 class="modal-title" id="myModalLabel"><?= $names[0].' '.$names[1] ?> Notices</h4>
     <small style="color:grey;">(<?= $count ?> unread notices)</small>
    </div>
    <div class="response"></div>
    <div class="modal-body">
        <?= $list ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>