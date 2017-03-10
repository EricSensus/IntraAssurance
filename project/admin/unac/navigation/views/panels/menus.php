<?php
use Jenga\App\Views\HTML;

HTML::css(RELATIVE_PROJECT_PATH.'/tools/smartmenus/css/sm-core-css.css',TRUE);
HTML::css(RELATIVE_PROJECT_PATH.'/tools/smartmenus/css/sm-clean/sm-clean.css',TRUE);

HTML::script(RELATIVE_PROJECT_PATH.'/tools/smartmenus/jquery.smartmenus.min.js', 'file');

HTML::script("$(function() {
                    $('#admin_menu').smartmenus({
                            subMenusSubOffsetX: 1,
                            subMenusSubOffsetY: -8,
                            subIndicatorsText: ''
                    });
            });");

echo '<div class="admin_menu">';
    echo $admin_menu;
echo '</div>';

HTML::script("$(function() {
                    $('#super_admin').smartmenus({
                            subMenusSubOffsetX: 1,
                            subMenusSubOffsetY: -8,
                            subIndicatorsText: ''
                    });
            });");

echo '<div class="super_admin">';
    echo $super_admin;
echo '</div>';
