<?php

#### Medical
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

$medical_path = Project::elements()['medical']['path'];
Route::get('/medical/step/{step}', 'MedicalController@index')
        ->assignPanels('frontend', [
            'top' => 'FrontController@loadNavigation:navigation',
            'sign_in' => 'FrontController@signIn:sign_in'
        ])
        ->assignResources([
            '<link href="' . RELATIVE_PROJECT_PATH . '/templates/frontend/datepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>',
            '<script src="' . RELATIVE_PROJECT_PATH . '/templates/frontend/datepicker/js/bootstrap-datetimepicker.min.js"></script>',
            '<script src="' . RELATIVE_PROJECT_PATH . '/' . $medical_path . '/assets/js/common.js"></script>',
            '<script src="' . RELATIVE_PROJECT_PATH . '/' . $medical_path . '/assets/js/medical.js"></script>',
            '<link href="' . RELATIVE_PROJECT_PATH . '/' . $medical_path . '/assets/css/medical.css" rel="stylesheet"/>'
        ]);
Route::post('/medical/save/{step}', 'MedicalController@saveForm');
Route::get('/medical/load/{deps}', 'MedicalController@loadDependants');

//the admin template behind the login wall
Route::group(['before' => 'auth.check'], function () {
    Route::get('/admin/quote/getquote/medical', 'MedicalController@showQuote')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);
});
