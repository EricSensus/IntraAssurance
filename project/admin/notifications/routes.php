<?php
use Jenga\App\Project\Routing\Route;

Route::any('/notices/load/{display}','NotificationsController@load');
Route::any('/notices/setview/{id}','NotificationsController@setAsViewed');
Route::any('/notices/deletenotice/{id}','NotificationsController@deleteNotice');