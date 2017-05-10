<?php

use Jenga\App\Project\Routing\Route;

//the admin template behind the login wall
Route::group(['before' => 'auth.check'], function () {
    
    Route::get('/admin/editrates/{id}', 'RatesController@editModal')
            ->assignPanels(['_ajax' => TRUE]);
    
    Route::get('/admin/rates/{action}/{id}', 'RatesController@index')
            ->assignPanels('admin', [
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
            ])
            ->assignResources([
                '<script src="' . RELATIVE_PROJECT_PATH . '/admin/rates/assets/js/rates.js"></script>'
            ]);
});
