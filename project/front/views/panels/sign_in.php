<?php
use Jenga\App\Request\Url;
use Jenga\App\Request\Session;
?>

<?php
    if(!$loggedIn){
?>
<!--    <div class="signintomyesurance">-->
        <li><a href="<?=Url::link('/profile/customer/login'); ?>" data-toggle="modal" data-target="#customer_login_modal"
               style="border: 1px solid #CCCCCC; padding: 10px;" class="pull-right;">
                Sign into my Esurance</a></li>
<!--    </div>-->
<?php
    } else {
?>
    <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Welcome <span style="color: #2a6014;"><?=$name; ?></span>
            <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="<?=Url::link('/profile/my-profile'); ?>">My Profile</a></li>
            <li><a href="<?=Url::link('/profile/dashboard'); ?>">My Dashboard</a></li>
            <li><a href="<?=Url::link('/customer/my-quotes'); ?>"> My Quotes</a></li>
            <li><a href="<?=Url::link('/customer/my-policies'); ?>"> My Policies</a></li>
            <li><a href="<?=Url::link('/customer/my-claims'); ?>"> My Claims</a></li>
            <li><a href="<?=Url::link('/admin/logout/'.Session::id()); ?>">Logout</a></li>
        </ul>
    </div>
<?php } ?>

<?php
echo $modal_container;
?>

<script>
    var toggleLoginForm = function () {
        $('#customer_login_modal .modal-header:first').slideToggle();
        $('#customer_login_modal .modal-body:first').slideToggle();
        $('#customer_login_modal .modal-footer:first').slideToggle();
    };

    var showForgotPass = function() {
        $(document).find('#forgot_passform > div').show();
        $(document).find('#forgot_passform').slideDown();
    }

    var closeForgotPassForm = function() {
        $(document).find('#forgot_passform').slideUp();
    }

    $(document).on('click', '#forgot_pass', function () {
        // hide the login form
        toggleLoginForm();

        // show the forgot password form
        showForgotPass()
    });

    $(document).on('click', '#cancel-forgot', function () {
        closeForgotPassForm();
    });

    // finally submit forgot password form
    $(document).on('click', '#send-reset', function () {
        $('#forgot_passform form').submit();
    });
</script>
