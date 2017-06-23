<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Session;
use Jenga\MyProject\Elements;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo PROJECT_NAME; ?> | Administration</title>
    <?php
        HTML::head();
        HTML::css('admin/css/admin_css.css');
    ?>
    <link href="<?php echo TEMPLATE_URL ?>admin/images/esurance_favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon"/>
    <link rel="stylesheet" type="text/css" href="<?= TEMPLATE_URL ?>admin/assets/font-awesome-4.7.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.min.css"/>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"/>
</head>

<body>
<?php
    HTML::notifications();
?>
<div id="top" class="hidden-print">
    <div id="logo">
        <img src="<?php echo TEMPLATE_URL ?>admin/images/esurance_logo.png" width="215" height="75"/>
    </div>
    <div style="float: right">
        <div id="notices">
        <?php
            $this->loadPanel('top');
        ?>
        </div>
        <?php
            $this->loadPanel('logout');
        ?>
    </div>
</div>
<div id="navigation" class="hidden-print">
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
<div id="footer" class="navbar-default">
    <div class="container">
      <p class="navbar-text pull-right">
          <small>© 2017 Powered by <a href="http://www.sensussystems.com" target="_blank">Sensus Systems Ltd</a></small>
      </p>
    </div>
</div>
<?php
    HTML::end();
?>
</body>
</html>
