<?php

use Jenga\App\Project\Routing\Route;

Route::get('/customer/loadlogin/{element}', 'CustomersController@customerLoginForm')
    ->assignPanels(['_ajax' => TRUE]);

//the admin template behind the login wall
Route::group(['before' => 'auth.check'], function () {

    Route::get('/admin/customers/deleteentity/{id}/{customerid}', 'CustomersController@deleteEntity')
            ->assignPanels(['_ajax' => TRUE]);

    Route::post('/admin/customers/{action}/{id}', 'CustomersController@index')
            ->attachTemplate('admin')->assignPanels([
        'top' => 'NotificationsController@load:notices',
        'navigation' => 'NavigationController@display:menus',
        'logout' => 'UsersController@logout:logout'
    ]);

    Route::get('/admin/customers/getfullentity/{id}/{customerid}', 'CustomersController@getfullentity')
            ->attachTemplate('admin')->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/customers/editfullentity/{id}/{customerid}', 'CustomersController@editfullentity')
            ->attachTemplate('admin')->assignPanels(['_ajax' => TRUE]);


    Route::get('/admin/customers/{action}/{id}', 'CustomersController@index')
            ->attachTemplate('admin')->assignPanels([
                'top' => 'NotificationsController@load:notices',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
            ]);
});
