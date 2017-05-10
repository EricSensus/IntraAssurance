<?php

use Jenga\App\Project\Routing\Route;

//the admin template behind the login wall
Route::group(['before' => 'auth.check'], function () {
    Route::any('/admin/documents/upload/{element}/{action}/{id}/{folder}', 'DocumentsController@upload:uploaddoc')
            ->attachTemplate('admin')->assignPanels([
        '_ajax' => TRUE
    ]);

    Route::post('/admin/documents/processupload', 'DocumentsController@processUpload')
            ->assignPanels([
                '_ajax' => TRUE
    ]);
});
