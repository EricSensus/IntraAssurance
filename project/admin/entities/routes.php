<?php

//the admin template behind the login wall
use Jenga\App\Project\Routing\Route;

Route::group(['before' => 'auth.check'], function () {
    Route::get('/admin/entities/createentityfield/{alias}', 'EntitiesController@createEntityField')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/entities/fieldedit/{formname}/{field}', 'EntitiesController@fieldEdit')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/entities/fielddelete/{formid}/{entityid}/{field}', 'EntitiesController@fieldDelete')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/entities/fieldrowreorder/{formid}', 'EntitiesController@fieldRowReorder')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);
});
