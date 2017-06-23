<?php
use Jenga\App\Request\Url;
?>
<script>
    $(function(){
        var placeholder = '<?= $placeholder ?>';
        
        $('#savechanges').on('click',function(){
            
            //get the previous tab content
            var tabcontent = $('#profilelogin').html();
            
            //get the form data
            var profile = $('#profile_form').serialize();
            
            $('#login_form').parsley();
            var login = $('#login_form').serialize();
           
           //replace with placeholder
           $('#profilelogin').html(placeholder);
           
           //start processing 
           $.ajax({
               method: "POST",
               url: "<?= Url::base().'/ajax/admin/user/savefullprofile' ?>",
               data: {
                   logindata: login,
                   profiledata: profile
               },
               success: function(response){
                   $(".response").html(response);
                   $('#profilelogin').html(tabcontent);
               },
               error: function(response){
                   $(".response").html(response);
                   $('#profilelogin').html(tabcontent);
               }
           });
        });
    });
</script>
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>

        </button>
         <h4 class="modal-title" id="myModalLabel"><?= $userfullname ?> Profile & Login</h4>

    </div>
    <div class="response"></div>
    <div class="modal-body" id="profilelogin">
        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#uploadTab" aria-controls="uploadTab" role="tab" data-toggle="tab">Profile</a>
                </li>
                <li role="presentation" class="">
                    <a href="#browseTab" aria-controls="browseTab" role="tab" data-toggle="tab">Login</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">                
                <div role="tabpanel" class="tab-pane active" id="uploadTab"><?= $profile ?></div>
                <div role="tabpanel" class="tab-pane" id="browseTab"><?= $login ?></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="savechanges" class="btn btn-primary save">Save User Changes</button>
    </div>
</div>