<?php
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

### domestic
$domestic_path = Project::elements()['domestic']['path'];
Route::get('/domestic/step/{step}', 'DomesticController@index')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation',
        'sign_in' => 'FrontController@signIn:sign_in'
    ])
    ->assignResources([
        "<script src='" . RELATIVE_PROJECT_PATH . '/' . $domestic_path . "/assets/js/domestic.js'></script>"
    ]);
Route::post('/domestic/save/{step}', 'DomesticController@save');