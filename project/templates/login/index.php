<?php
use Jenga\App\Views\HTML;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo PROJECT_NAME; ?></title>
<?php
    HTML::head();
    HTML::css('login/css/template_css.css');
?>

<link href="<?php echo TEMPLATE_URL ?>login/images/logo.png" rel="shortcut icon" type="image/x-icon" />
<link href="<?php echo TEMPLATE_URL ?>frontend/images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>

<body>
<?php
    HTML::notifications();
?>
<div class="container">
  <div id="login">
      <?php
        $this->loadMainPanel();
      ?>
  </div>
</div>
<?php
    HTML::end();
?>
</body>
</html>