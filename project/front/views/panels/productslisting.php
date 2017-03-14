<?php
use Jenga\App\Request\Url;
?>
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
    <script defer src="<?php echo TEMPLATE_URL ?>frontend/js/jquery.flexslider.js"></script>
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
                                    <h4><a href="<?= Url::base().'/motor/step/1' ?>">Motor Insurance</a></h4>

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
                                    <h4><a href="<?= Url::base().'/domestic/step/1' ?>">Fire & Domestic Package</a></h4>

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
                                    <h4><a href="<?= Url::base().'/accident/step/1' ?>">Personal Accident</a></h4>

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
