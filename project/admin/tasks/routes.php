<?php

//the admin template behind the login wall
use Jenga\App\Project\Routing\Route;

Route::group(['before' => 'auth.check'], function () {

    Route::get('/admin/tasks/addtask/{customerid}', 'TasksController@add')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/tasks/markascomplete/{id}/{destination}', 'TasksController@markAsComplete')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

    Route::get('/admin/tasks/delete/{id}/{destination}', 'TasksController@delete');
});
