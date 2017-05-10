<?php
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;
Route::any('/quotes/mypreviewquote/{id}', 'QuotesController@previewQuote')
    ->attachTemplate('preview')
    ->assignPanels(['_ajax' => TRUE]);
Route::get('/quote/actions/{link}', 'QuotesController@acceptQuote')
    ->attachTemplate('preview');
Route::post('/quote/acceptreject', 'QuotesController@acceptRejectQuote');
Route::any('/quotes/previewquote/{id}/{view}', 'QuotesController@previewQuote')
    ->attachTemplate('preview')
    ->assignPanels([
        'logout' => 'UsersController@logout:logout'
    ]);

Route::group(['before' => 'auth.check'], function () {
    Route::get('/customer/my-quotes', 'QuotesController@myQuotes')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);
    Route::get('/admin/quote/getquote/medical', 'MedicalController@showQuote')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);
    Route::any('/admin/quotes/pdfquote/{id}', 'QuotesController@pdfQuote')
        ->attachTemplate('preview')
        ->assignPanels(['_ajax' => TRUE]);
    Route::any('/admin/quote/deletedoc/{quoteid}/{id}', 'QuotesController@deletedoc')
        ->assignPanels(['_ajax' => TRUE]);
    Route::post('/admin/preview/quote', 'QuotesController@quotePreview')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);
    $path = Project::elements()['claims']['path'];
    Route::any('/admin/quotes/{action}/{id}', 'QuotesController@index')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ])
        ->assignResources([
                "<script src='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/notify/custom.min.js'></script>",
                "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/notify/custom.css' type='text/css' rel='stylesheet'/>",
                "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/css/select2.min.css'  type='text/css' rel='stylesheet'/>",
                "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/css/select2-bootstrap.min.css'  type='text/css' rel='stylesheet'/>",
                "<script src='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/js/select2.full.min.js'></script>",
            ]
        );
    Route::get('/admin/leads/assignAgent/{quote_no}', 'QuotesController@assignAgent')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);
    Route::get('/admin/leads/createTask/{agent_id}', 'QuotesController@createTaskForAgent')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);
    Route::get('/admin/quotes/addquote/{id}', 'QuotesController@add')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ])
        ->assignResources([
            "<script src='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/notify/custom.min.js'></script>",
            "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/notify/custom.css' type='text/css' rel='stylesheet'/>",
            "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/css/select2.min.css'  type='text/css' rel='stylesheet'/>",
            "<link href='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/css/select2-bootstrap.min.css'  type='text/css' rel='stylesheet'/>",
            "<script src='" . RELATIVE_PROJECT_PATH . '/' . $path . "/assets/select2/js/select2.full.min.js'></script>",
        ]);
    Route::post('/admin/quotes/{action}', 'QuotesController@index')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);
    Route::post('/admin/myquote/new/{element}', 'QuotesController@internalQuote')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);
    Route::post('/admin/myquote/save/{element}', 'QuotesController@saveInternalQuote');
    Route::get('/admin/myquote/view/{quote}', 'QuotesController@internalQuoteView')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);
    Route::post('/admin/preview/saveentity', 'QuotesController@quotePresave')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);
});