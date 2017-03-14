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
        HTML::css('frontend/css/style.css');
    ?>
    <link href="<?php echo TEMPLATE_URL ?>frontend/images/esurance_favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <?php
        //menu navigation
        HTML::js('frontend/js/flexy-menu.js');
        HTML::script('$(document).ready(function(){$(".flexy-menu").flexymenu({speed: 400,type: "horizontal",align: "left"});});');
        
        //slider navigation
        HTML::css('frontend/css/fwslider.css');
        HTML::js('frontend/js/jquery-ui.min.js');
        HTML::js('frontend/js/fwslider.js');
    ?>
</head>

<body>
<?php
HTML::notifications();
?>

<!----start-wrap---->
<!----start-top-header----->
<div class="top-header" id="header">
    <div class="wrap">
        <div class="top-header-left">
            <ul>
                <li><a href="http://www.intraafrica.co.ke/index.php?id=1">Home</a></li><li><a href="http://www.intraafrica.co.ke/index.php?id=6">IAA Careers</a></li><li><a href="http://www.intraafrica.co.ke/index.php?id=7">Marine Portal</a></li><li><a href="http://www.intraafrica.co.ke/index.php?id=8">Media Center</a></li><li><a href="http://www.intraafrica.co.ke/index.php?id=9">Downloads</a></li>
                <li><a href="http://www.intraafrica.co.ke/">Call Us Now :020-2712607 /11
                        020-3743991/955</a></li>
                <div class="clear"> </div>
            </ul>
        </div>
        <div class="top-header-right">
            <ul>
                <li class="sbox"><input class="search" name="Search" type="text" value="" placeholder="Search.."><input class="sbutton" value="GO" name="Go" type="button"></li>
                <li><a class="face" href="https://www.facebook.com/I.A.A.Co.Ltd" target="_blank"><span> </span></a></li>
                <li><a class="twit" href="https://twitter.com/intraafrica"><span> </span></a></li>
                <li><a class="thum" href="https://plus.google.com/u/0/+IntraAfricaNairobi/"><span> </span></a></li>
                <li><a class="pin" href="https://www.linkedin.com/company/intra-africa-assurance-co-ltd-incorporated-in-kenya"><span> </span></a></li>
                <div class="clear"> </div>
            </ul>
        </div>
        <div class="clear"> </div>
    </div>
</div>
<!----//End-top-header----->
<!---start-header---->
<div class="header">
    <div class="wrap">
        <!--- start-logo---->
        <div class="logo">
            <a href="<?= Url::base() ?>"><img src="<?php echo TEMPLATE_URL ?>frontend/images/logo.png" title="Intra Africa Assurance"></a>
        </div>
        <!--- //End-logo---->
        <!--- start-top-nav---->
        <div class="top-nav">
            <ul id="" class="flexy-menu thick orange"><li class="showhide" style="display: none;"><span class="title">MENU</span><span class="icon"><em></em><em></em><em></em><em></em></span></li>
                    <li class="" style=""><a href="http://www.intraafrica.co.ke/index.php?id=2" title="Individual Products &amp; Services">Individual Products &amp; Services</a><ul style="">
                            <li class=""><a href="<?= Url::base().'/domestic/step/1' ?>" title="Domestic Package">Domestic Package</a></li>
                            <li class=""><a href="<?= Url::base().'/motor/step/1' ?>" title="Motor cycles">Motor cycles</a></li>
                        <li class=""><a href="<?= Url::base().'/motor/step/1' ?>" title="Motor Private Insurance">Motor Private Insurance</a></li>
                        <li class=""><a href="<?= Url::base().'/accident/step/1' ?>" title="Personal Accident">Personal Accident</a></li>
                    </ul></li>
                    <li class="" style=""><a href="http://www.intraafrica.co.ke/index.php?id=4" title="About IAA">About IAA</a><ul style=""><li class=""><a href="http://www.intraafrica.co.ke/index.php?id=39" title="The Company">The Company</a></li>
                        <li class=""><a href="http://www.intraafrica.co.ke/index.php?id=45" title="Management Team">Management Team</a></li>
                        <li class=""><a href="http://www.intraafrica.co.ke/index.php?id=44" title="Board of Directors">Board of Directors</a></li>
                        <li class=""><a href="http://www.intraafrica.co.ke/index.php?id=41" title="Financial Statements">Financial Statements</a></li>
                    </ul></li>
                    <li class="" style=""><a href="http://www.intraafrica.co.ke/index.php?id=5" title="Contact Us">Contact Us</a></li>
                    </ul>
            <!--<div class="search-box">
                <div id="sb-search" class="sb-search">
                    <form>
                        <input class="sb-search-input" placeholder="Enter your search term..." type="search" name="search" id="search">
                        <input class="sb-search-submit" type="submit" value="">
                        <span class="sb-icon-search"> </span>
                    </form>
                </div>
            </div>-->
            <!----search-scripts---->
            <script src="<?php echo TEMPLATE_URL ?>frontend/js/modernizr.custom.js.download"></script>
            <script src="<?php echo TEMPLATE_URL ?>frontend/js/classie.js.download"></script>
            <script src="<?php echo TEMPLATE_URL ?>frontend/js/uisearch.js.download"></script>
            <script>
                new UISearch( document.getElementById( 'sb-search' ) );
            </script>
            <!----//search-scripts---->
        </div>
        <!--- //End-top-nav---->
        <div class="clear"> </div>
    </div>
    <!---//End-header---->
</div>
<!----start-index_files-slider---->
<?php

if($this->isPanelActive('banner'))
    $this->loadPanelPosition('banner');

?>
<div class="middlesection">
    <div class="contentbox">
        <?php
        $this->loadMainPanel();
        ?>
    </div>
</div>
<!----start-find-place---->
<div class="find-place">
    <div class="wrap">
        <div class="p-h">
            <span>IAA</span>
            <label>M-pesa Paybill</label>
        </div>
        <!---strat-date-piker---->
        <script src="<?php echo TEMPLATE_URL ?>frontend/js/jquery-ui.js.download"></script>
        <script>
            $(function() {
                $( "#datepicker" ).datepicker();
            });
        </script>
        <!---/End-date-piker---->
        <div class="p-ww">
            <h2 class="bold">PAYBILL NO.<span class="span_of_mpesa">861600</span></h2>
            <img class="mpesa" src="<?php echo TEMPLATE_URL ?>frontend/images/mpesa.png">

            <!--<form>
                <span> Type</span>
                <input class="dest" type="text" value="Type of claim" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Distination';}">
                <span> When</span>
                <input class="date" id="datepicker" type="text" value="Select date" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Select date';}">
                <input type="submit" value="Submit Claim" />
            </form>-->
        </div>
        <div class="clear"> </div>
    </div>
</div>
<!----//End-find-place---->

    <?php
        if($this->isPanelActive('lowermain')){
            $this->loadPanelPosition('lowermain');
        }
    ?>
<!----//End-offers---->
<!---start-holiday-types---->
<div class="holiday-types">
    <div class="wrap">
        <div class="holiday-type-head">
            <h3>IAA Claim Type</h3>
            <span>Accidents are stressful, but we don't think settling your insurance claim should be!</span>
        </div>
        <div class="holiday-type-grids">
            <div class="holiday-type-grid" onclick="location.href=&#39;#&#39;;">
                <span class="icon1"> </span>
                <a href="http://www.intraafrica.co.ke/index.php?id=43">IAA General Claim</a>
            </div>
            <div class="holiday-type-grid" onclick="location.href=&#39;#&#39;;">
                <span class="icon2"> </span>
                <a href="http://www.intraafrica.co.ke/index.php?id=42">IAA Motor Claim</a>
            </div>
            <!--<div class="holiday-type-grid" onclick="location.href='#';">
                <span class="icon4"> </span>
                <a href="#">Adventure</a>
            </div>
            <div class="holiday-type-grid" onclick="location.href='#';">
                <span class="icon5"> </span>
                <a href="#">Safari</a>
            </div>
            <div class="holiday-type-grid" onclick="location.href='#';">
                <span class="icon6"> </span>
                <a href="#">Beach</a>
            </div>-->
            <div class="clear"> </div>
        </div>
    </div>
</div>
<!---//End-holiday-types---->
<!----//End-index_files-slider---->
<!----start-clients---->
<!--<div class="clients">
    <div class="client-head">
        <h3>Happy Clients</h3>
        <span>what customer say about us and why love our services!</span>
    </div>
    <div class="client-grids">
        <ul class="bxslider">
          <li>
              <p>Lorem Ipsum is simply dummy text of the printing and typeset industry. Lorem Ipsum has been the industry's standard dummy text ever hen an with new version look.</p>
              <a href="#">Client Name</a>
              <span>United States</span>
              <label> </label>
          </li>
          <li>
              <p>Lorem Ipsum is simply dummy text of the printing and typeset industry. Lorem Ipsum has been the industry's standard dummy text ever hen an with new version look.</p>
              <a href="#">Client Name</a>
              <span>United States</span>
              <label> </label>
          </li>
          <li>
              <p>Lorem Ipsum is simply dummy text of the printing and typeset industry. Lorem Ipsum has been the industry's standard dummy text ever hen an with new version look.</p>
              <a href="#">Client Name</a>
              <span>United States</span>
              <label> </label>
          </li>
          <li>
              <p>Lorem Ipsum is simply dummy text of the printing and typeset industry. Lorem Ipsum has been the industry's standard dummy text ever hen an with new version look.</p>
              <a href="#">Client Name</a>
              <span>United States</span>
              <label> </label>
          </li>
          <li>
              <p>Lorem Ipsum is simply dummy text of the printing and typeset industry. Lorem Ipsum has been the industry's standard dummy text ever hen an with new version look.</p>
              <a href="#">Client Name</a>
              <span>United States</span>
              <label> </label>
          </li>
        </ul>
    </div>
</div>-->
<!----//End-clients---->
<!----start-footer
<div class="footer">
    <div class="wrap">
    <div class="footer-grids">
        <div class="footer-grid Newsletter">
            <h3>News letter </h3>
            <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore.</p>
            <form>
                <input type="text" placeholder="Subscribes.." /> <input type="submit" value="GO" />
            </form>
        </div>
        <div class="footer-grid Newsletter">
            <h3>Latest News </h3>
            <div class="news">
                <div class="news-pic">
                    <img src="images/f01.jpg" title="news-pic1" />
                </div>
                <div class="news-info">
                    <a href="#">Postformat Gallery: Multiple index_files</a>
                    <span>December 12, 2012 - 9:11 pm</span>
                </div>
                <div class="clear"> </div>
            </div>
            <div class="news">
                <div class="news-pic">
                    <img src="index_files/f01.jpg" title="news-pic1" />
                </div>
                <div class="news-info">
                    <a href="#">Postformat Gallery: Multiple index_files</a>
                    <span>December 12, 2012 - 9:11 pm</span>
                </div>
                <div class="clear"> </div>
            </div>
        </div>
        <div class="footer-grid tags">
            <h3>Tags</h3>
            <ul>
                <li><a href="#">Agent login</a></li>
                <li><a href="#">Customer Login</a></li>
                <li><a href="#">Not a Member?</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">New Horizons</a></li>
                <li><a href="#">Lanscape</a></li>
                <li><a href="#">Tags</a></li>
                <li><a href="#">Nice</a></li>
                <li><a href="#">Some</a></li>
                <li><a href="#">Portrait</a></li>
                <div class="clear"> </div>
            </ul>
        </div>
        <div class="footer-grid address">
            <h3>Address </h3>
            <div class="address-info">
                <span>DieSachbearbeiter Schonhauser </span>
                <span>Allee 167c,10435 Berlin Germany</span>
                <span><i>E-mail:</i><a href="mailto:moin@blindtextgenerator.de">moin@blindtextgenerator.de</a></span>
            </div>
            <div class="footer-social-icons">
                <ul>
                    <li><a class="face1 simptip-position-bottom simptip-movable" data-tooltip="facebook" href="#"><span> </span></a></li>
                    <li><a class="twit1 simptip-position-bottom simptip-movable" data-tooltip="twitter" href="#"><span> </span></a></li>
                    <li><a class="tub1 simptip-position-bottom simptip-movable" data-tooltip="tumblr" href="#"><span> </span></a></li>
                    <li><a class="pin1 simptip-position-bottom simptip-movable" data-tooltip="pinterest" href="#"><span> </span></a></li>
                    <div class="clear"> </div>
                </ul>
            </div>
        </div>
        <div class="clear"> </div>
    </div>
    </div>
</div>
<!----//End-footer---->
<!---start-subfooter---->
<div class="subfooter">
    <div class="wrap">
        <p class="copy-right">© Copyright <script>var tD = new Date();var datestr = tD.getFullYear();document.write(datestr);</script>2017 Intra Africa Assurance<a href="http://www.intraafrica.co.ke/#"> Term &amp; Conditions</a>.</p>
        <ul class="foot-nav">
            <li><a href="http://www.intraafrica.co.ke/index.php?id=5">Contact Us</a><span>::</span></li><li><a href="http://www.intraafrica.co.ke/index.php?id=6">IAA Careers</a><span>::</span></li><li><a href="http://www.intraafrica.co.ke/index.php?id=8">Media Center</a><span>::</span></li>
            <div class="clear"> </div>
        </ul>
        <!--<p class="copy-right">Template by <a href="http://w3layouts.com/">W3layouts</a></p>-->
        <!--<a class="to-top" href="#header"><span> </span> </a>-->
    </div>
</div>
<!---//End-subfooter---->
<!----//End-wrap---->

<?php
HTML::end();
?>
</body>
</html>