<?php

#### Travel

use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

$travel_path = Project::elements()['travel']['path'];
Route::get('/travel/step/{step}', 'TravelController')
        ->assignPanels('frontend', [
            'top' => 'FrontController@loadNavigation:navigation',
            'sign_in' => 'FrontController@signIn:sign_in'
        ])->assignResources([
    '<link href="' . RELATIVE_PROJECT_PATH . '/templates/frontend/datepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>',
    '<script src="' . RELATIVE_PROJECT_PATH . '/templates/frontend/datepicker/js/bootstrap-datetimepicker.min.js"></script>',
    '<script src="' . RELATIVE_PROJECT_PATH . '/' . $travel_path . '/assets/js/common.js"></script>',
    '<script src="' . RELATIVE_PROJECT_PATH . '/' . $travel_path . '/assets/js/travel.js"></script>'
]);
Route::post('/travel/save/{step}', 'TravelController@saveForm');
//the admin template behind the login wall
Route::group(['before' => 'auth.check'], function () {
    Route::get('/admin/quote/getquote/travel', 'TravelController@showQuote')
            ->attachTemplate('admin')->assignPanels([
        '_ajax' => TRUE
    ]);
});
