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
					<a href="http://www.intraafrica.co.ke/index.php?id=1"><img src="<?php echo TEMPLATE_URL ?>frontend/images/logo.png" title="Intra Africa Assurance"></a>
				</div>
				<!--- //End-logo---->
				<!--- start-top-nav---->
				<div class="top-nav">
                    <ul id="" class="flexy-menu thick orange"><li class="showhide right" style="display: none;"><span class="title">MENU</span><span class="icon"><em></em><em></em><em></em><em></em></span></li>



<li class="right" style=""><a href="http://www.intraafrica.co.ke/index.php?id=5" title="Contact Us">Contact Us</a></li><li class="right" style=""><a href="http://www.intraafrica.co.ke/index.php?id=4" title="About IAA">About IAA</a><ul style=""><li class=""><a href="http://www.intraafrica.co.ke/index.php?id=39" title="The Company">The Company</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=45" title="Management Team">Management Team</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=44" title="Board of Directors">Board of Directors</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=41" title="Financial Statements">Financial Statements</a></li>
</ul></li><li class="right" style=""><a href="http://www.intraafrica.co.ke/index.php?id=3" title="Corporate Products &amp; Services">Corporate Products &amp; Services</a><ul style=""><li class=""><a href="http://www.intraafrica.co.ke/index.php?id=18" title="All Risks Insurance">All Risks Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=19" title="Burglary Insurance">Burglary Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=20" title="Cash in Transit Insurance">Cash in Transit Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=21" title="Consequential Loss (Fire) Insurance">Consequential Loss (Fire) Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=22" title="Contractor&#39;s All Risk Insurance">Contractor's All Risk Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=23" title="Employers Liability (Common Law) Insurance">Employers Liability (Common Law) Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=24" title="Fidelity Guarantee Insurance">Fidelity Guarantee Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=25" title="Fire Insurance">Fire Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=26" title="Plate Glass Insurance">Plate Glass Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=27" title="Goods in Transit Insurance">Goods in Transit Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=28" title="Group Personal Accident Insurance">Group Personal Accident Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=29" title="Machinery Insurance">Machinery Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=30" title="Marine Cargo Insurance">Marine Cargo Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=31" title="Motor Private Fleet Insurance">Motor Private Fleet Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=32" title="Motor Commercial vehicles Fleet Insurance">Motor Commercial vehicles Fleet Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=33" title="Work Injury Benefits Act (WIBA)">Work Injury Benefits Act (WIBA)</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=34" title="Motor cycles Fleet Insurance">Motor cycles Fleet Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=35" title="Professional Indemnity Insurance">Professional Indemnity Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=36" title="School Package Insurance">School Package Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=37" title="Terrorism and Political Risks Insurance">Terrorism and Political Risks Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=38" title="Public Liability Insurance">Public Liability Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=70" title="Bonds">Bonds</a></li>
</ul></li><li class="right" style=""><a href="http://www.intraafrica.co.ke/index.php?id=2" title="Individual Products &amp; Services">Individual Products &amp; Services</a><ul style=""><li class=""><a href="http://www.intraafrica.co.ke/index.php?id=16" title="Domestic Package">Domestic Package</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=15" title="Motor Commercial Insurance">Motor Commercial Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=17" title="Motor cycles">Motor cycles</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=14" title="Motor Private Insurance">Motor Private Insurance</a></li>
<li class=""><a href="http://www.intraafrica.co.ke/index.php?id=13" title="Personal Accident">Personal Accident</a></li>
</ul></li></ul>
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
		<!----start-offers---->
		<div class="offers">
			<div class="offers-head">
				<h3>Our Products</h3>
				<p>At IAA we offer the following packages:</p>
			</div>
			<!-- start content_slider -->
			<!-- FlexSlider -->
			 <!-- jQuery -->
			  <link rel="stylesheet" href="<?php echo TEMPLATE_URL ?>frontend/css/flexslider.css" type="text/css" media="screen">
			  <!-- FlexSlider -->
			  <script defer src="<?php echo TEMPLATE_URL ?>frontend/js/jquery.flexslider.js.download"></script>
			  <script type="text/javascript">
			    $(function(){
			      SyntaxHighlighter.all();
			    });
			    $(window).load(function(){
			      $('.flexslider').flexslider({
			        animation: "slide",
			        animationLoop: true,
			        itemWidth:250,
			        itemMargin: 5,
			        start: function(slider){
			          $('body').removeClass('loading');
			        }
			      });
			    });
			  </script>
			<!-- Place somewhere in the <body> of your page -->
				 <section class="slider">
		        <div class="flexslider carousel">
		          
		        <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 5400%; transition-duration: 1s; transform: translate3d(0px, 0px, 0px);">
					
                     <!-- 
		            <li onclick="location.href=&#39;index.php?id=18&#39;;" style="width: 1px; float: left; display: block;">
	<img src="./images/all-risk.jpg" height="100">
		  	    	    <!----place-caption-info----> <!--
		  	    	    <div class="caption-info">
		  	    	    	 <div class="caption-info-head">
		  	    	    	 	<div class="caption-info-head-left">
			  	    	    	 	<h4><a href="http://www.intraafrica.co.ke/index.php?id=18">All Risks Insurance</a></h4>
			  	    	    	 	
		  	    	    	 	</div>
		  	    	    	 	<div class="caption-info-head-right">
		  	    	    	 		<span> </span>
		  	    	    	 	</div>
		  	    	    	 	<div class="clear"> </div>
		  	    	    	 </div>
		  	    	    </div>
		  	    	     <!----//place-caption-info--
		  	    		</li>-->
                        <li onclick="location.href=&#39;index.php?id=70&#39;;" style="width: 350px; float: left; display: block;">
	<img src="<?php echo TEMPLATE_URL ?>frontend/images/motor-insurance.jpg" height="100">
		  	    	    <!----place-caption-info---->
		  	    	    <div class="caption-info">
		  	    	    	 <div class="caption-info-head">
		  	    	    	 	<div class="caption-info-head-left">
			  	    	    	 	<h4><a href="http://www.intraafrica.co.ke/index.php?id=70">Motor Insurance</a></h4>
			  	    	    	 	
		  	    	    	 	</div>
		  	    	    	 	<div class="caption-info-head-right">
		  	    	    	 		<span> </span>
		  	    	    	 	</div>
		  	    	    	 	<div class="clear"> </div>
		  	    	    	 </div>
		  	    	    </div>
		  	    	     <!----//place-caption-info---->
		  	    		</li>
                        <li onclick="location.href=&#39;index.php?id=19&#39;;" style="width: 240.4px; float: left; display: block;">
	<img src="<?php echo TEMPLATE_URL ?>frontend/images/fire&domestic.jpg" height="100">
		  	    	    <!----place-caption-info---->
		  	    	    <div class="caption-info">
		  	    	    	 <div class="caption-info-head">
		  	    	    	 	<div class="caption-info-head-left">
			  	    	    	 	<h4><a href="http://www.intraafrica.co.ke/index.php?id=19">Fire & Domestic Package</a></h4>
			  	    	    	 	
		  	    	    	 	</div>
		  	    	    	 	<div class="caption-info-head-right">
		  	    	    	 		<span> </span>
		  	    	    	 	</div>
		  	    	    	 	<div class="clear"> </div>
		  	    	    	 </div>
		  	    	    </div>
		  	    	     <!----//place-caption-info---->
		  	    		</li>
                        <li onclick="location.href=&#39;index.php?id=31&#39;;" style="width: 240.4px; float: left; display: block;">
	<img src="<?php echo TEMPLATE_URL ?>frontend/images/personal-accident.jpg" height="100">
		  	    	    <!----place-caption-info---->
		  	    	    <div class="caption-info">
		  	    	    	 <div class="caption-info-head">
		  	    	    	 	<div class="caption-info-head-left">
			  	    	    	 	<h4><a href="http://www.intraafrica.co.ke/index.php?id=31">Personal Accident</a></h4>
			  	    	    	 	
		  	    	    	 	</div>
		  	    	    	 	<div class="caption-info-head-right">
		  	    	    	 		<span> </span>
		  	    	    	 	</div>
		  	    	    	 	<div class="clear"> </div>
		  	    	    	 </div>
		  	    	    </div>
                        </li>
		          </ul></div></div>
		      </section>
              <!-- //End content_slider -->
		</div>
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
