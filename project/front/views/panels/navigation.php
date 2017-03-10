<?php
use Jenga\App\Request\Url;
?>
<div class="menubar">
    <div class="menubar-inner row">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="true" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="true">
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php echo Url::base() ?>">Home</a></li>
                <li><a href="<?php echo Url::base().'/motor/step/1' ?>">Motor</a></li>
                <li><a href="<?php echo Url::base().'/domestic/step/1' ?>">Domestic</a></li>
                <li><a href="<?php echo Url::base().'/medical/step/1' ?>">Medical</a></li>
                <li><a href="<?php echo Url::base().'/accident/step/1' ?>">Personal Accident</a></li>
                <li><a href="<?php echo Url::base().'/travel/step/1' ?>">Travel</a></li>
            </ul>
          </div>
    </div>
</div>

