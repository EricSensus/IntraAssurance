<?php
use Jenga\App\Request\Url;
use Jenga\App\Request\Session;
use Jenga\App\Views\Notifications;

echo '<a href="'.Url::link('/admin/logout/'.Session::get('logid')).'" >'
        . '<span class="logoutbutton" '
            .Notifications::popover('Click here to Log Out',['data-placement'=>'left']).'>'        
        . '<img src="'.TEMPLATE_URL.'admin/images/profilepic.png" width="43" height="41" />'
        . '</span>'
        . '</a>';
