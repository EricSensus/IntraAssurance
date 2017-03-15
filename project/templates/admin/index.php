<?php
use Jenga\App\Views\HTML;

HTML::start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo PROJECT_NAME; ?> | Administration</title>
<?php
    HTML::head();
    HTML::css('admin/css/admin_css.css');
?>
<link href="<?php echo TEMPLATE_URL ?>admin/images/esurance_favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>

<body>
<?php
    HTML::notifications();
?>
<div id="top">
  <div id="logo"><img src="<?php echo TEMPLATE_URL ?>admin/images/intraasurance-logo.jpg" width="243" height="80" /></div>
  <div id="logout">
      <?php
        $this->loadPanel('logout');
      ?>
  </div>
</div>
<div id="navigation">
<?php
    $this->loadPanel('navigation',['name'=>'admin-menu,super-admin','template' => 'admin']);
?>
</div>
<div class="content-container">
    <div id="breadcrumbs">
    <?php
        //$this->loadPanel('breadcrumbs', ['url' => App::Url(TRUE)]);
    ?>
    </div>
    <?php
        $this->loadMainPanel();
    ?>
</div>
<?php
    HTML::end();
?>
</body>
</html>