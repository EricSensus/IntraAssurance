<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Session;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo PROJECT_NAME; ?></title>
    
    <?php
        HTML::head();
        HTML::css('frontend/css/styles.css');
		HTML::css('frontend/css/homepage-colorscheme.css');
		HTML::css('frontend/css/body-colorscheme.css');
		HTML::css('frontend/css/innerpages-colorscheme.css');					
    ?>
    
    <link href="<?php echo TEMPLATE_URL ?>frontend/images/esurance_favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
    <link rel="stylesheet" type="text/css" href="<?= TEMPLATE_URL ?>admin/assets/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css"/>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
</head>

<body>
<?php
//Session::destroy();
HTML::notifications();
?>
<style>
    form#customerloginform > table {
        width: 100%;
    }
</style>
<div class="wrapper container">
    <?php
    
    if($this->isPanelActive('sign_in'))
        $this->loadPanelPosition('sign_in');

    if ($this->isPanelActive('top')){
        $this->loadPanelPosition('top');
    }
    
    if ($this->isPanelActive('banner')){
        $this->loadPanelPosition('banner');
    }
    
    if ($this->isPanelActive('lowermain')) {
        $this->loadPanelPosition('lowermain');
    } else {
        ?>
        <div class="middlesection">
            <?php
                if(Session::has('sent_confirmation')){
            ?>
                <div class="alert alert-info text-center hidden-print">
                    <div class="row">
                        <div class="col-md-2">
                            <img class="pull-right" src="<?php echo TEMPLATE_URL ?>frontend/images/notice-icon.png" alt=""/>
                        </div>
                        <div class="col-md-9">
                            <p style="padding-top: 15px;" class="text-left"><strong><?=Session::get('sent_confirmation'); ?></strong></p>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php
                if(Session::has('step_feed')){
            ?>
                <div class="alert alert-warning text-center hidden-print" style="border-radius: 0px;">
                    <strong><?=Session::get('step_feed'); ?></strong>
                </div>
            <?php } ?>
            <div class="contentbox">
                <?php
                    $this->loadMainPanel();
                ?>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="footer hidden-print">
        <div class="footer-inner row">
            <div class="logo2 col-md-2 col-sm-12 col-xs-12">
                <img src="<?php echo TEMPLATE_URL ?>frontend/images/logo2.png"/>
            </div>
            <div class="footerlinks col-md-7 col-sm-12 col-xs-12">
            	<div class="links-inner">
                <a href="/index.php/aboutus">ABOUT US</a>
                <div class="seperator2"><p>|</p></div>
                <a href="/index.php/motor">MOTOR</a>
                <div class="seperator2"><p>|</p></div>
                <a href="/index.php/domesticpackage">DOMESTIC PACKAGE</a>
                <div class="seperator2"><p>|</p></div>
                <a href="/index.php/medical">MEDICAL</a>
                <div class="seperator2"><p>|</p></div>
                <a href="/index.php/travel">TRAVEL</a>
                <div class="seperator2"><p>|</p></div>
                <a href="/index.php/personalaccident">PERSONAL ACCIDENT</a>
                </div>
            </div>
            <div class="socialmedia col-md-3 col-sm-6 col-xs-12">
            	<div class="social-icons">
                <a href="/facebook"><img src="<?php echo TEMPLATE_URL ?>frontend/images/facebook.png"/></a>
                <a twitter="/twitter"><img src="<?php echo TEMPLATE_URL ?>frontend/images/twitter.png"/></a>
                <a href="/linkedin"><img src="<?php echo TEMPLATE_URL ?>frontend/images/linkedin.png"/></a>
                <a href="/google+"><img src="<?php echo TEMPLATE_URL ?>frontend/images/google+.png"/></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
HTML::end();
?>
</body>
</html>
