<?php
use Jenga\App\Views\HTML;
use Jenga\MyProject\Elements;

HTML::start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo PROJECT_NAME; ?> | Administration</title>
    <link rel="stylesheet" type="text/css" href="<?= TEMPLATE_URL ?>admin/assets/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css"/>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
<title><?php echo PROJECT_NAME; ?> | Administration</title>
<?php
    HTML::head();
    HTML::css('admin/css/admin_css.css');
?>
<link href="<?php echo TEMPLATE_URL ?>frontend/images/favicon.png" rel="shsuortcut icon" type="image/vnd.microsoft.icon" />
</head>

<body>
<?php
    HTML::notifications();
?>
<div id="top">
  <div id="logo"><img src="<?php echo TEMPLATE_URL ?>admin/images/intraasurance-logo.jpg" width="260" height="80" /></div>
  <div id="logout">
      <?php
        $this->loadPanel('logout');
      ?>
  </div>
</div>
<div id="navigation">
    <?php
    $nav = Elements::call('Navigation/NavigationController');
    $menuname = $nav->getMenusFromAccesslevel();

    $this->loadPanel('navigation', ['name' => $menuname, 'template' => 'admin']);
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
