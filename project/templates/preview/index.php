<?php

use Jenga\MyProject\Users\Handlers\Gateway;
use Jenga\App\Views\HTML;

HTML::start();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo PROJECT_NAME; ?> Quote Preview</title>
<link href="<?php echo TEMPLATE_URL ?>preview/images/esurance_favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
</head>

<body class="preview-quote">
<?php
    HTML::notifications();
?>
<div id="top">
  <div id="logo"><img src="<?php echo TEMPLATE_URL ?>admin/images/esurance_logo.png" width="215" height="75" /></div>

<?php
if(Gateway::isLogged()){

  echo '<div id="logout">';
    $this->loadPanel('logout');
  echo '</div>';
}
?>

</div>
<div class="content-container shadow">
    <div class="col-md-12">
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