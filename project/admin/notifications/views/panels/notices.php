<?php
use Jenga\App\Request\Url;
?>
<a href="<?= Url::link('/ajax/notices/load/all') ?>" class="notification-container" data-toggle="modal" data-backdrop="true" data-target="#profilemodal">
    <?php
    if($count == '0'){
    ?>
        <i class="fa fa-bell-o fa-2x" aria-hidden="true"></i>
    <?php
    }
    else{
    ?>
        <i class="fa fa-bell fa-2x" aria-hidden="true"></i>
        <span class="notification-counter"><?= $count ?></span>
    <?php
    }
    ?>
</a>
