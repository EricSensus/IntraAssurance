<?php
use Jenga\App\Project\Routing\Route;

Route::get('/profile/customer/login', 'ProfileController@profileLoginForm')
    ->assignPanels(['_ajax' => TRUE]);

Route::post('/profile/reset-password', 'UsersController@sendResetLink');
Route::get('/profile/reset-password/{user_id}', 'UsersController@resetPasswordForm')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation',
        'sign_in' => 'FrontController@signIn:sign_in'
    ]);
Route::get('/test-cron', 'UsersController@testCron');
Route::post('/profile/save-password', 'UsersController@saveNewPassword');

Route::group(['before' => 'auth.check'], function () {
    
    //the customer routes
    Route::get('/profile/dashboard', 'admin' . DS . 'profile' . DS . 'views' . DS . 'panels' . DS . 'profile.php')
        ->assignPanels('admin', [
            'top' => 'NotificationsController@load:notices',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout',
            'my_quotes' => 'QuotesController@myDashQuotes:my_quotes',
            'my_policies' => 'PoliciesController@myDashPolicies:my_policies',
            'my_claims' => 'ClaimsController@myDashClaims:my_claims',
            'linked_agent' => 'AgentsController@getLinkedAgent'
        ]);

    Route::get('/profile/my-profile', 'ProfileController@myProfile')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'NotificationsController@load:notices',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);
});