<?php

use Jenga\App\Project\Routing\Route;

Route::any('/api/{token}/{action}/{section}/{id}', 'ApiController@index', [
    'action' => 'pull'
]);