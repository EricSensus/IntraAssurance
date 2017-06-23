<?php
use Jenga\App\Request\Input;
use Jenga\App\Views\HTML;

HTML::script("$(function() { 
    
    // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('a[data-toggle=\"tab\"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
    });
 $('a.raters').on('click', function (e) {
            localStorage.setItem('lastTab', '#rates');
            console.log(localStorage);
        });
    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        $('[href=\"' + lastTab + '\"]').tab('show');
    }
});");

echo '<div class="row">'
    . '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Esurance Configuration</h4>
                    <p class="small">This <strong>section</strong> allows for global settings of the major sections in the system</p>
            </div>
    </div>
</div>
</div>';

echo '<div class="tabs row-padding">
    <ul role="tablist" class="nav nav-pills" id="myTabs">
      <li class="active" role="presentation" ><a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#company-details">Company Details</a></li>
      <li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#insurer-companies" aria-expanded="true">Insurer Companies</a></li>
      <li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#products" aria-expanded="true">Products</a></li>
      <li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#api" aria-expanded="true">API Tokens</a></li>';

//echo '<li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#commissions" aria-expanded="true">Commissions</a></li>';
echo '<li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#agents" aria-expanded="true">Users / Agents</a></li>';
   echo  '  <li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#entities" aria-expanded="true">Entities</a></li>';
if (empty(Input::get('__company')))
    echo '<li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#companies-list" aria-expanded="true">Company Rates</a></li>';
else
    echo '<li role="presentation"><a aria-controls="profile" data-toggle="tab" role="tab" href="#rates" aria-expanded="true">Rates</a></li>';

echo '</ul>
    </div>';

echo '<div class="row show-grid">';
echo '<div class="tab-content shadow col-md-12">';

//own company tab
echo '<div role="tabpanel" class="tab-pane active" id="company-details">'
    . '<div class="col-md-12">'
    . '<div class="mini-panel no-bottom-border">';

$this->loadPanelPosition('company-details');

echo '</div>
    </div>
    </div>';

//insurer company tab
echo '<div role="tabpanel" class="tab-pane" id="insurer-companies">'
    . '<div class="col-md-12">'
    . '<div class="mini-panel no-bottom-border">';

$this->loadPanelPosition('insurer-companies');

echo '</div>
    </div>
    </div>';

//products tab
echo '<div role="tabpanel" class="tab-pane" id="products">'
    . '<div class="col-md-12">'
    . '<div class="mini-panel no-bottom-border">';

$this->loadPanelPosition('products-setup');

echo '</div>
    </div>
    </div>';

/**
 * //commissions tab
 * echo '<div role="tabpanel" class="tab-pane" id="commissions">'
 * .'<div class="col-md-12">'
 * .'<div class="shadow">'
 * .'<div class="mini-panel">';
 *
 * $this->loadPanelPosition('commissions-setup');
 *
 * echo  '</div>
 * </div>
 * </div>
 * </div>';
 *
 */

//entities tab
echo '<div role="tabpanel" class="tab-pane" id="entities">'
    . '<div class="col-md-12">'
    . '<div class="mini-panel no-bottom-border">';

$this->loadPanelPosition('entities-setup');

echo '</div>
    </div>
    </div>';

//api tab
echo '<div role="tabpanel" class="tab-pane" id="api">'
    . '<div class="col-md-12">'
    . '<div class="mini-panel no-bottom-border">';

$this->loadPanelPosition('api-setup');

echo '</div>
    </div>
    </div>';
//companies tab
if (empty(Input::get('__company'))) {
    echo '<div role="tabpanel" class="tab-pane" id="companies-list">'
        . '<div class="col-md-12">'
        . '<div class="mini-panel no-bottom-border">';

    $this->loadPanelPosition('companies');

    echo '</div>
    </div>
    </div>';
} else {
//rates tab
    echo '<div role="tabpanel" class="tab-pane" id="rates">'
        . '<div class="col-md-12">'
        . '<div class="mini-panel no-bottom-border">';
    $this->loadPanelPosition('rates-setup');
    echo '</div>
    </div>
    </div>';
}
//agents tab
echo '<div role="tabpanel" class="tab-pane" id="agents">'
        .'<div class="col-md-12">'
        .'<div class="shadow">'
        .'<div class="mini-panel">';

$this->loadPanelPosition('agents-setup');

    echo '</div>
    </div>
    </div>';
/**
 * //agents tab
 * echo '<div role="tabpanel" class="tab-pane" id="agents">'
 * .'<div class="col-md-12">'
 * .'<div class="shadow">'
 * .'<div class="mini-panel">';
 *
 * $this->loadPanelPosition('agents-setup');
 *
 * echo  '</div>
 * </div>
 * </div>
 * </div>';
 *
 */
echo '</div>';
echo '</div>';


