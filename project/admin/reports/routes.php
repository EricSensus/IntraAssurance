<?php
use Jenga\App\Project\Routing\Route;

Route::get('/admin/reports/overview', 'admin' . DS . 'static' . DS . 'charts.php')
    ->attachTemplate('admin')
    ->assignPanels([
        'top' => 'NotificationsController@load:notices',
        'navigation' => 'NavigationController@display:menus',
        'logout' => 'UsersController@logout:logout',
        'products-share' => 'QuotesController@analyseProducts:products-share',
        'monthly-quotes' => 'QuotesController@analyseQuotesByMonth:monthly-quotes',
        'monthly-policies' => 'PoliciesController@analysePoliciesByMonth:monthly-policies',
        'agents-share' => 'AgentsController@agentPerformance:agents-share',
        'current-scs' => 'QuotesController@salesConvForCurrentMonth:current-scs'
    ])
    ->assignResources([
        // highchart scripts
        '<script src="' . RELATIVE_PROJECT_PATH . '/services/highcharts-4.1.5/js/highcharts.js"></script>'
    ]);

Route::get('/admin/reports/{action}', 'ReportsController@index')
    ->attachTemplate('admin')
    ->assignPanels([
        'top' => 'NotificationsController@load:notices',
        'navigation' => 'NavigationController@display:menus',
        'logout' => 'UsersController@logout:logout',
    ]);
