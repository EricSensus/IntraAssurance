<?php
use Jenga\App\Request\Url;
?>
<div class="index_files-slider">
			<!-- start slider -->
		    <div id="fwslider" style="height: 554px;">
            
		        <div class="slider_container">
                <div class="slide" style="display: block; z-index: 1; opacity: 1;"> 
		                <!-- Slide image -->
		                    <img src="<?php echo TEMPLATE_URL ?>frontend/images/slider-bg.jpg" alt="">
		                <!-- /Slide image -->
		                <!-- Texts container -->
		                <div class="slide_content">
		                    <div class="slide_content_wrap">
		                        <!-- Text title -->
		                        <h4 class="title" style="opacity: 1;">Domestic Package</h4>
		                        <!-- /Text title -->
		                        <!-- Text description -->
		                        <p class="description" style="opacity: 1;">Domestic insurance includes house and contents insurance.</p>
		                        <!-- /Text description -->
		                        <div class="slide-btns description" style="opacity: 1;">
		                        	<ul>
		                        		<li><a class="minfo" href="<?= Url::base().'/domestic/step/1' ?>">Get Instant Quote</a></li>
		                        		<div class="clear"> </div>
		                        	</ul>
		                        </div>
		                    </div>
		                </div>
		                 <!-- /Texts container -->
		            </div><div class="slide" style="opacity: 1; z-index: 0; display: none;"> 
		                <!-- Slide image -->
		                    <img src="<?php echo TEMPLATE_URL ?>frontend/images/moto-private-insurance.jpg" alt="">
		                <!-- /Slide image -->
		                <!-- Texts container -->
		                <div class="slide_content">
		                    <div class="slide_content_wrap">
		                        <!-- Text title -->
		                        <h4 class="title" style="opacity: 0;">Motor Private Insurance</h4>
		                        <!-- /Text title -->
		                        <!-- Text description -->
		                        <p class="description" style="opacity: 0;">Cover is available for: Saloons, station wagons etc.</p>
		                        <!-- /Text description -->
		                        <div class="slide-btns description" style="opacity: 0;">
		                        	<ul>
		                        		<li><a class="minfo" href="<?= Url::base().'/motor/step/1' ?>">Get Instant Quote</a></li>
		                        		<div class="clear"> </div>
		                        	</ul>
		                        </div>
		                    </div>
		                </div>
		                 <!-- /Texts container -->
		            </div><div class="slide" style="z-index: 0; opacity: 1; display: none;"> 
		                <!-- Slide image -->
		                    <img src="<?php echo TEMPLATE_URL ?>frontend/images/fire-insurance.jpg" alt="">
		                <!-- /Slide image -->
		                <!-- Texts container -->
		                <div class="slide_content">
		                    <div class="slide_content_wrap">
		                        <!-- Text title -->
		                        <h4 class="title" style="opacity: 0;">Fire Insurance</h4>
		                        <!-- /Text title -->
		                        <!-- Text description -->
		                        <p class="description" style="opacity: 0;">This policy covers loss, damage or destruction to the insured property, caused by Fire and allied perils</p>
		                        <!-- /Text description -->
		                        <div class="slide-btns description" style="opacity: 0;">
		                        	<ul>
		                        		<li><a class="minfo" href="<?= Url::base().'/domestic/step/1' ?>">Get Instant Quote</a></li>
		                        		<div class="clear"> </div>
		                        	</ul>
		                        </div>
		                    </div>
		                </div>
		                 <!-- /Texts container -->
		            </div>
		            <!--/slide -->
		        </div>
                
		        <div class="timers" style="width: 225px;"> <div class="timer"><div class="progress" style="overflow: hidden; width: 18.72%;"></div></div><div class="timer"><div class="progress" style="overflow: hidden; width: 0%;"></div></div><div class="timer"><div class="progress" style="width: 0%;"></div></div><div class="timer"><div class="progress" style="width: 0%;"></div></div></div>
		        <div class="slidePrev" style="left: 0px; top: 252.5px; opacity: 0.5;"><span> </span></div>
		        <div class="slideNext" style="right: 0px; top: 247px; opacity: 0.5;"><span> </span></div>
		    </div>
		    <!--/slider -->
		</div>