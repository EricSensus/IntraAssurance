<?php
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

### accident
$accident_path = Project::elements()['accident']['path'];
Route::get('/accident/step/{step}', 'AccidentController@index')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation',
        'sign_in' => 'FrontController@signIn:sign_in'
    ])
    ->assignResources([
        "<script src='" . RELATIVE_PROJECT_PATH . "/$accident_path/assets/js/accident.js'></script>"
    ]);
Route::post('/accident/save/{step}', 'AccidentController@save');
