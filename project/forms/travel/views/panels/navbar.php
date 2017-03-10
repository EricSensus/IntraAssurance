<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">Bima247.com | Travel Insurance</a>
      </div>
      <ul class="nav navbar-nav">
          <li class="<?= ($step == 1) ? 'active' : ''; ?>"><a href="<?= Jenga\App\Request\Url::link('/travel/step/1'); ?>">Step 1 <small>Personal Details ></small></a></li>
          <li class="<?= ($step == 2) ? 'active' : ''; ?>"><a href="<?= Jenga\App\Request\Url::link('/travel/step/2'); ?>">Step 2 <small>Travel Details ></small></a></li>
          <li class="<?= ($step == 3) ? 'active' : ''; ?>"><a href="<?= Jenga\App\Request\Url::link('/travel/step/3'); ?>">Step 3 <small>Cover Details ></small></a></li>
          <li class="<?= ($step == 4) ? 'active' : ''; ?>"><a href="<?= Jenga\App\Request\Url::link('/travel/step/4'); ?>">Step 4 <small>Quotation and Payment</small></a></li>
      </ul>
    </div>
</nav>