<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo PROJECT_NAME; ?></title>
<?php
    HTML::head();
    HTML::css('frontend/css/styles.css');
?>
<link href="<?php echo TEMPLATE_URL ?>frontend/images/esurance_favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>

<body>
<?php
    HTML::notifications();
?>
<div class="wrapper container">
    <div class="header">
    	<div class="header-inner row">
            <div class="logobox col-md-3 col-sm-12 col-xs-12">
                <div class="logo">
                    <img src="<?php echo TEMPLATE_URL ?>frontend/images/logo.png" width="225px" height="94px"/>
                </div>
            </div>
            <div class="usersection col-md-3 col-sm-12 col-xs-12">
                <div class="selectclaim">
                    <a href="/index.php/selectclaim">Select Claim</a>
                </div>
                <div class="go">
                    <a href="/index.php/go">GO</a>
                </div>
                <div class="signintomyesurance">
                    <a href="<?= Url::base().'/login' ?>">Sign into my Esurance</a>
                </div>
            </div>
         </div>
    </div>
    <?php

        if($this->isPanelActive('top'))
            $this->loadPanelPosition('top');

        if($this->isPanelActive('banner'))
            $this->loadPanelPosition('banner');

        if($this->isPanelActive('lowermain')){
            $this->loadPanelPosition('lowermain');
        }
        else{
    ?>
    <div class="middlesection">
        <div class="contentbox">
    <?php
        $this->loadMainPanel();
    ?>
        </div>
    </div>
    <?php
        }
    ?>
    <div class="footer">
    	<div class="footer-inner row">
      		<div class="logo2 col-md-2 col-sm-12 col-xs-12">
            	<img src="<?php echo TEMPLATE_URL ?>frontend/images/logo2.jpg" width="202px" height="105px"/>
            </div>
      		<div class="footerlinks col-md-7 col-sm-12 col-xs-12">
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
      		<div class="socialmedia col-md-3 col-sm-6 col-xs-12">
                	<a href="/facebook"><img src="<?php echo TEMPLATE_URL ?>frontend/images/facebook.jpg" width="43px" height="45px"/></a>
                	<a twitter="/twitter"><img src="<?php echo TEMPLATE_URL ?>frontend/images/twitter.jpg" width="43px" height="45px"/></a>
                	<a href="/blog"><img src="<?php echo TEMPLATE_URL ?>frontend/images/blog.jpg" width="43px" height="45px"/></a>
                	<a href="/google+"><img src="<?php echo TEMPLATE_URL ?>frontend/images/google+.jpg" width="43px" height="45px"/></a>
            </div>
    	</div>
    </div>
</div>
<?php
    HTML::end();
?>
</body>
</html>
