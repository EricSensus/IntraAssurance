<?php

//the admin template behind the login wall
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

Route::get('/customer/my-claims', 'ClaimsController@myClaims')
    ->attachTemplate('admin')
    ->assignPanels([
        'top' => 'NotificationsController@load:notices',
        'navigation' => 'NavigationController@display:menus',
        'logout' => 'UsersController@logout:logout'
    ]);

Route::group(['before' => 'auth.check'], function () {
    $path = Project::elements()['claims']['path'];
    Route::any('/admin/claims/{action}/{id}', 'ClaimsController@index')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'NotificationsController@load:notices',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ])->assignResources([
            "<script src='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/notify/custom.min.js'></script>",
            "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/notify/custom.css' type='text/css' rel='stylesheet'/>",
            "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/css/select2.min.css'  type='text/css' rel='stylesheet'/>",
            "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/css/select2-bootstrap.min.css'  type='text/css' rel='stylesheet'/>",
            "<script src='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/js/select2.full.min.js'></script>",
            "<script src='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/js/claim.min.js'></script>",
        ]);
    Route::any('/admin/claims/upload/{id}', 'ClaimsController@upload:uploaddoc')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);
});
