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
                
                <?php
                
                //home
                if(Url::current() == Url::base().'')
                    $homeactive = 'class="active"';
                else
                    $homeactive = '';
                
                //motor
                if(Url::current() == Url::base().'/motor/step/1')
                    $motoractive = 'class="active"';
                else
                    $motoractive = '';
                
                //domestic
                if(strstr(Url::current(), Url::base().'/domestic/step'))
                    $domesticactive = 'class="active"';
                else
                    $domesticactive = '';
                
                //medical
                if(strstr(Url::current(), Url::base().'/medical/step'))
                    $medicalactive = 'class="active"';
                else
                    $medicalactive = '';
                
                //personal accident
                if(strstr(Url::current(), Url::base().'/accident/step'))
                    $personalactive = 'class="active"';
                else
                    $personalactive = '';
                
                //travel
                if(strstr(Url::current(),Url::base().'/travel/step'))
                    $travelactive = 'class="active"';
                else
                    $travelactive = '';
                
                ?>
                
                <li <?= $homeactive ?>><a href="<?php echo Url::base() ?>">Home</a></li>
                <li <?= $motoractive ?>><a href="<?php echo Url::base().'/motor/step/1' ?>">Motor</a></li>
                <li <?= $domesticactive ?>><a href="<?php echo Url::base().'/domestic/step/1' ?>">Domestic</a></li>
                <li <?= $medicalactive ?>><a href="<?php echo Url::base().'/medical/step/1' ?>">Medical</a></li>
                <li <?= $personalactive ?>><a href="<?php echo Url::base().'/accident/step/1' ?>">Personal Accident</a></li>
                <li <?= $travelactive ?>><a href="<?php echo Url::base().'/travel/step/1' ?>">Travel</a></li>
            </ul>
          </div>
    </div>
</div>

