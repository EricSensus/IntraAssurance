<?php

use Jenga\App\Project\Routing\Route;

Route::get('/login', 'UsersController@show:login')
        ->attachTemplate('login');

Route::post('/login', 'UsersController@login:login')
        ->assignPanels(['_ajax' => TRUE]);

Route::get('/customer/verifyemail/{element}/{hash_user_id}', 'UsersController@verifyEmail');
Route::post('/customer/login', 'UsersController@logInCustomer');

//the admin template behind the login wall
Route::group(['before' => 'auth.check'], function () {
    
    Route::get('/admin/logout/{sessid}', 'UsersController@logout:logout')
            //->at('before')->fire('log.check')
            ->attachTemplate('admin')->assignPanels(['_ajax' => TRUE]);
    
    Route::get('/admin/user/profile/{acl}/{id}', 'UsersController@addedit');
    Route::get('/admin/user/savefullprofile', 'UsersController@saveFullProfile');
});
