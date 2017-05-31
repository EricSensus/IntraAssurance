<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;
use Jenga\App\Request\Session;

//get names
if($this->user()->acl == 'superadmin' || $this->user()->acl == 'admin'){  
    $names = explode(' ', $this->user()->fullname);
}
elseif($this->user()->acl == 'customer'){
    $names = explode(' ', $this->user()->customer_name);
}
?>
<script>
    $(function() {
        
        //moved the notice script from the notices element
        $('#profilemodal').on('hidden.bs.modal',function(){
            $.ajax({
                url: "<?= Url::link('/ajax/notices/load/summary') ?>",
                success: function(response){           
                    $('#notices').html('');
                    $('#notices').html(response);
                }
            });
        });
    });
</script>
<div id="logout" class="dropdown">
    <div class="dropdown-toggle" data-toggle="dropdown">
        <span class="logoutbutton">        
            <img class="img-rounded" src="<?= TEMPLATE_URL ?>admin/images/profilepic.png" width="35" />
        </span>
        <?php
            echo '<span class="username">'
                    . $names[0].' '.$names[1]
                . '</span>';
        ?>
        <span class="caret"></span>
    </div>
    <ul class="dropdown-menu dropdown-menu-right">
        <!-- to be redone using a much better ways -->
        <li>
            <?php
                if($this->user()->acl == 'superadmin' || $this->user()->acl == 'admin'){
                    $id = $this->user()->profileid;
                }
                elseif($this->user()->acl == 'customer'){
                    $id = $this->user()->customer_id;
                }
            ?>
            <a data-toggle="modal" data-backdrop="static" data-target="#profilemodal" href="<?=Url::link('/ajax/admin/user/profile/'.$this->user()->acl.'/'.$id); ?>">My Profile</a>
        </li>
        <li class="divider"></li>
        <li><a href="<?=Url::link('/admin/logout/'.Session::id()); ?>">Logout</a></li>
    </ul>
</div>
<div style="color: #000">
<?php
    //modal for the profile
    echo  Overlays::Modal(['id'=>'profilemodal']);
?>
</div>