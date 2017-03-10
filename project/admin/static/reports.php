<?php

echo '<div class="row padding-bottom-20px">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Reporting Overview & Statistics</h4>
                    <p class="small"><strong>Insurance</strong> reporting ovreview</p>
            </div>
            <div class="right mt10">
            </div>
    </div>
</div>';

echo '</div>';

echo '<div class="row show-grid padding-bottom-20px">';
        
echo '<div class="col-md-6">
      <div class="shadow">
      <div class="mini-panel">';

$this->loadPanel('products-share',['type'=>'pie','id'=>'share-pie','width'=>'100%','height'=>'380px']);

echo '</div>'
. '<div class="panel-footer">'
    . '<p><strong>Products</strong> Percentage Share</p>'
. '</div>'
. '</div>'
. '</div>';

echo '<div class="col-md-6">'
. '<div class="shadow">'
. '<div class="mini-panel">';

$this->loadPanel('monthly-quotes',['type'=>'column','id'=>'monthly-quotes','width'=>'100%','height'=>'380px']);

echo '</div>'
. '<div class="panel-footer">'
        . '<p><strong>Monthly</strong> Quotes Generation</p>'
. '</div>'
. '</div>'
.'</div>';

echo '</div>';

echo '<div class="row show-grid">';

echo '<div class="col-md-6">'
. '<div class="shadow">'
. '<div class="mini-panel">';

$this->loadPanel('monthly-policies',['type'=>'stackedcolumn','id'=>'monthly-policies','width'=>'100%','height'=>'380px']);

echo '</div>'
    . '<div class="panel-footer">'
        . '<p><strong>Monthly</strong> Policies Generated</p>';
echo  '</div>'
. '</div>'
. '</div>';

echo '<div class="col-md-6">'
. '<div class="shadow">'
. '<div class="mini-panel">';

$this->loadPanel('agents-share',['type'=>'pie','id'=>'agent-share-pie','width'=>'100%','height'=>'380px']);

echo '</div>'
    . '<div class="panel-footer">'
        . '<p><strong>Monthly</strong> Agent Performance</p>';
echo  '</div>'
. '</div>'
. '</div>';

echo '</div>';