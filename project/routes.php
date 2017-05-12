<?php
/**
 * This is where the user can specify the specific routing for his/her elements
 *
 * Keywords: _ajax, _main (this assigns the main panel for an ajax request)
 *
 * Group Events: before:request, on:request/before, at:controller, on:response, after:response,
 *      on:complete/after, on:exception
 */
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

//route for homepage
Route::get('/', '{default}')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation',
        'banner' => 'FrontController@showBanner:banner',
        'lowermain' => 'FrontController@showProducts:productslisting',
        'sign_in' => 'FrontController@signIn:sign_in'
    ]);

/* Collected routes */
Route::collect('accident');
Route::collect('domestic');
Route::collect('motor');
Route::collect('medical');
Route::collect('travel');

//quotes
Route::collect('quotes');
Route::collect('policies');
Route::collect('navigation');
Route::collect('rates');
Route::collect('users');
Route::collect('products');
Route::collect('customers');
Route::collect('entities');
Route::collect('tasks');
Route::collect('documents');
Route::collect('claims');
Route::collect('profile');
Route::collect('users');

//the admin template behind the login wall
Route::group(['before' => 'auth.check'], function () {

    Route::get('/admin/dashboard', 'admin' . DS . 'static' . DS . 'dashboard.php')
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout',
                'leads' => 'QuotesController@getLeads:leads',
                'active-quotes' => 'QuotesController@getActiveQuotes:active-quotes',
                'unprocessed-policies' => 'PoliciesController@getUnprocessedPolicies:unprocessed-policies',
                'expired-policies' => 'PoliciesController@getExpiringPolicies:expired-policies',
                'tasks' => 'TasksController@show:tasks'
            ]);

    Route::get('/admin/reports', 'admin' . DS . 'static' . DS . 'reports.php')
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout',
                'products-share' => 'QuotesController@analyseProducts:products-share',
                'monthly-quotes' => 'QuotesController@analyseQuotesByMonth:monthly-quotes',
                'monthly-policies' => 'PoliciesController@analysePoliciesByMonth:monthly-policies',
                'agents-share' => 'AgentsController@agentPerformance:agents-share'
            ])
            ->assignResources([
                //highchart scripts
                '<script src="' . RELATIVE_PROJECT_PATH . '/services/highcharts-4.1.5/js/highcharts.js"></script>'
            ]);

    Route::get('/admin/setup', 'admin' . DS . 'static' . DS . 'setup.php')
            ->attachTemplate('admin')
            ->assignPanels([
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout',
                'company-details' => 'CompaniesController@ownCompany:own-company-details',
                'insurer-companies' => 'CompaniesController@getInsurers:insurer-companies',
                'products-setup' => 'ProductsController@getProducts:products-setup',
                'entities-setup' => 'EntitiesController@getEntities:entities-setup',
                'rates-setup' => 'RatesController@showSetup',
                'companies' => 'RatesController@getCompanies:companies',
                //'commissions-setup' => 'InsurersController@getCommissions:commissions',
                'agents-setup' => 'AgentsController@getAgents:agents'
            ]);

    Route::get('/admin/{element}/{action}/{id}', '{default}')
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
            ]);

    Route::post('/admin/{element}/{action}', '{default}')
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
            ]);
});
