<?php

//the admin template behind the login wall
use Jenga\App\Project\Routing\Route;

Route::group(['before' => 'auth.check'], function () {
    Route::get('/admin/products/createproductfield/{alias}', 'ProductsController@createProductField')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);


    Route::get('/admin/products/fieldedit/{formname}/{field}', 'ProductsController@fieldEdit')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/products/fielddelete/{formid}/{productid}/{field}', 'ProductsController@fieldDelete')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/products/fieldrowreorder/{formid}', 'ProductsController@fieldRowReorder')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);
});
