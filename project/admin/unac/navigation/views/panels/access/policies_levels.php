<?php
use Jenga\App\Request\Url;

echo '<form action="'.Url::route('/admin/navigation/access/savepolicy/').'" method="post">';
echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">System Policies Management</h4>
                    <p class="small"><strong>Manage</strong> how your Users interact with the system elements</p>
            </div>
            <div class="right toolholder">';
echo '</div>
    </div>
</div>
</div>';


echo '<div class="margin-top-10">'
    . '<div class="row show-grid panel bhoechie-tab-container">';

echo '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 bhoechie-tab-menu">'
    . $levellist
    . '</div>';

echo '<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 bhoechie-tab">'
    . $content
    . '</div>';

echo '</div>'
    . '</div>'
. '</form>';
