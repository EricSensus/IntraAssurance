<?php

### Motor
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

$motor_path = Project::elements()['motor']['path'];
Route::get('/motor/step/{step}', 'MotorController@index')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation',
        'sign_in' => 'FrontController@signIn:sign_in'
    ])
    ->assignResources([
        "<script src='" . RELATIVE_PROJECT_PATH . '/' . $motor_path . "/assets/js/motor.js'></script>"
    ]);
Route::post('/motor/save/{step}', 'MotorController@save');
Route::get('/motor/others/{count}', 'MotorController@getExtraCovers')->assignPanels(['_ajax' => true]);