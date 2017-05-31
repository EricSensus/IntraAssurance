<?php
use Jenga\App\Views\HTML;

HTML::css(RELATIVE_PROJECT_PATH.'/tools/smartmenus/css/sm-core-css.css',TRUE);
HTML::css(RELATIVE_PROJECT_PATH.'/tools/smartmenus/css/sm-clean/sm-clean.css',TRUE);

HTML::script(RELATIVE_PROJECT_PATH.'/tools/smartmenus/jquery.smartmenus.min.js', 'file');

echo '<div class="esurancemenu pull-left">';

    foreach($names as $name){
        
        HTML::script("$(function() {
                    $('#".$name."').smartmenus({
                            subMenusSubOffsetX: 1,
                            subMenusSubOffsetY: -8,
                            subIndicatorsText: ''
                    });
            });");
        
        if($name != 'super_admin'){
            echo ${$name};
        }
    }
echo '</div>';

echo '<div class="main_menu pull-right">';
    if(in_array('super_admin', $names)){
        echo $super_admin;
    }
echo '</div>';

