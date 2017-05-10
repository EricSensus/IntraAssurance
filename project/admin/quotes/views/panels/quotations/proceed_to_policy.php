<?php
    use Jenga\App\Request\Session;
?>
<div>
    <?php
    if(isset($_POST['policy']) && Session::has('quote_id')){
        ?>
        <a href="<?=SITE_PATH.'/admin/policies/createpolicy/'.Session::get('quote_id'); ?>" class="btn btn-success pull-right">Continue with Policy creation ></a>
    <?php } ?>
</div>