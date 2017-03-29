<?php
/**
 * This is where the user can specify the specific routing for his/her elements
 *
 * Keywords: _ajax, _main (this assigns the main panel for an ajax request)
 *
 * Group Events: before:request, on:request/before, at:controller, on:response, after:response,
 *      on:complete/after, on:exception
 */
use Jenga\App\Project\Core\Project;
use Jenga\App\Project\Routing\Route;

//route for homepage
Route::get('/', '{default}')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation',
        'banner' => 'FrontController@showBanner:banner',
        'lowermain' => 'FrontController@showProducts:productslisting'
    ]);

Route::get('/login', 'UsersController@show:login')
    ->attachTemplate('login');

Route::post('/login', 'UsersController@login:login')
    ->assignPanels(['_ajax' => TRUE]);

### accident
$accident_path = Project::elements()['accident']['path'];
Route::get('/accident/step/{step}', 'AccidentController@index')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation'
    ])
    ->assignResources([
        "<script src='" . RELATIVE_PROJECT_PATH . "/$accident_path/assets/js/accident.js'></script>"
    ]);
Route::post('/accident/save/{step}', 'AccidentController@save');

### domestic
$domestic_path = Project::elements()['domestic']['path'];
Route::get('/domestic/step/{step}', 'DomesticController@index')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation'
    ])
    ->assignResources([
        "<script src='" . RELATIVE_PROJECT_PATH . '/' . $domestic_path . "/assets/js/common.js'></script>",
        "<script src='" . RELATIVE_PROJECT_PATH . '/' . $domestic_path . "/assets/js/domestic.js'></script>"
    ]);
Route::post('/domestic/save/{step}', 'DomesticController@save');

### motor
$motor_path = Project::elements()['motor']['path'];

//commercial
Route::get('/motor/commercial', 'MotorController@commercialCfg')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation'
    ])
    ->assignResources([
        "<script src='" . RELATIVE_PROJECT_PATH . '/' . $motor_path . "/assets/js/motor.js'></script>"
    ]);

//private 
Route::get('/motor/step/{step}', 'MotorController@index')
    ->assignPanels('frontend', [
        'top' => 'FrontController@loadNavigation:navigation'
    ])
    ->assignResources([
        "<script src='" . RELATIVE_PROJECT_PATH . '/' . $motor_path . "/assets/js/motor.js'></script>"
    ]);
Route::post('/motor/save/{step}', 'MotorController@save');

Route::any('/quotes/previewquote/{id}/{view}', 'QuotesController@previewQuote')
    ->attachTemplate('preview')->assignPanels([
        'logout' => 'UsersController@logout:logout'
    ]);

//the admin template behind the login wall
Route::group(array('before' => 'auth.check'), function () {

    Route::any('/admin/quotes/pdfquote/{id}', 'QuotesController@pdfQuote')
        ->attachTemplate('preview')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::any('/admin/quote/deletedoc/{quoteid}/{id}', 'QuotesController@deletedoc')
        ->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::any('/admin/policies/deletedoc/{policy_id}/{id}', 'PoliciesController@deletedoc')
        ->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::any('/admin/documents/upload/{element}/{action}/{id}/{folder}', 'DocumentsController@upload:uploaddoc')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::post('/admin/documents/processupload', 'DocumentsController@processUpload')
        ->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/logout/{sessid}', 'UsersController@logout:logout')
        //->at('before')->fire('log.check')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/dashboard', 'admin' . DS . 'static' . DS . 'dashboard.php')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout',
            'leads' => 'QuotesController@getLeads:leads',
            'active-quotes' => 'QuotesController@getActiveQuotes:active-quotes',
            'unprocessed-policies' => 'PoliciesController@getUnprocessedPolicies:unprocessed-policies',
            'expired-policies' => 'PoliciesController@getExpiringPolicies:expired-policies',
            'tasks' => 'TasksController@show:tasks'
        ]);

    Route::get('/admin/reports', 'admin' . DS . 'static' . DS . 'reports.php')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout',
            'products-share' => 'QuotesController@analyseProducts:products-share',
            'monthly-quotes' => 'QuotesController@analyseQuotesByMonth:monthly-quotes',
            'monthly-policies' => 'PoliciesController@analysePoliciesByMonth:monthly-policies',
            'agents-share' => 'AgentsController@agentPerformance:agents-share'
        ])
        ->assignResources([
            //highchart scripts
            '<script src="' . RELATIVE_PROJECT_PATH . '/services/highcharts-4.1.5/js/highcharts.js"></script>'
        ]);

    Route::get('/admin/setup', 'admin' . DS . 'static' . DS . 'setup.php')
        ->attachTemplate('admin')->assignPanels([
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout',
            'company-details' => 'CompaniesController@ownCompany:own-company-details',
            'insurer-companies' => 'CompaniesController@getInsurers:insurer-companies',
            'products-setup' => 'ProductsController@getProducts:products-setup',
            'entities-setup' => 'EntitiesController@getEntities:entities-setup',
            'commissions-setup' => 'InsurersController@getCommissions:commissions',
            'agents-setup' => 'AgentsController@getAgents:agents'
        ]);

    Route::get('/admin/policies/createpolicy/{offer}', 'PoliciesController@createpolicy')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::get('/admin/policies/{action}/{id}', 'PoliciesController@index', ['action' => 'show'])
        ->attachTemplate('admin')
        ->assignPanels([
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout',
            'company-details' => 'CompaniesController@ownCompany:own-company-details',
            'insurer-companies' => 'CompaniesController@getInsurers:insurer-companies',
            'products-setup' => 'ProductsController@getProducts:products-setup',
            'entities-setup' => 'EntitiesController@getEntities:entities-setup',
            'commissions-setup' => 'InsurersController@getCommissions:commissions',
            'agents-setup' => 'AgentsController@getAgents:agents'
        ])
        ->assignResources([
            '<link rel="stylesheet" href="' . RELATIVE_PROJECT_PATH . '/tools/Autocomplete-master/jquery.autocomplete.css">',
            '<script src="' . RELATIVE_PROJECT_PATH . '/tools/Autocomplete-master/jquery.autocomplete.js"></script>'
        ]);

    Route::post('/admin/policies/{action}', 'PoliciesController@index')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::get('/admin/products/createproductfield/{alias}', 'ProductsController@createProductField')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);
    Route::post('/admin/preview/saveentity', 'QuotesController@quotePresave')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);
    Route::post('/admin/preview/quote', 'QuotesController@quotePreview')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);
    //  /admin/preview/saveentity
    Route::get('/admin/products/fieldedit/{formname}/{field}', 'ProductsController@fieldEdit')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/products/fielddelete/{formid}/{productid}/{field}', 'ProductsController@fieldDelete')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/products/fieldrowreorder/{formid}', 'ProductsController@fieldRowReorder')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/entities/createentityfield/{alias}', 'EntitiesController@createEntityField')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/entities/fieldedit/{formname}/{field}', 'EntitiesController@fieldEdit')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/entities/fielddelete/{formid}/{entityid}/{field}', 'EntitiesController@fieldDelete')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/entities/fieldrowreorder/{formid}', 'EntitiesController@fieldRowReorder')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);
    $quote_path = Project::elements()['quotes']['path'];
    Route::any('/admin/quotes/{action}/{id}', 'QuotesController@index')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ])
        ->assignResources([
            '<link rel="stylesheet" href="' . RELATIVE_PROJECT_PATH . '/tools/Autocomplete-master/jquery.autocomplete.css">',
            '<script src="' . RELATIVE_PROJECT_PATH . '/tools/Autocomplete-master/jquery.autocomplete.js"></script>',
            //  "<script src='" . RELATIVE_PROJECT_PATH . "/$quote_path/assets/js/quotes-common.js'></script>",
            "<script src='" . RELATIVE_PROJECT_PATH . "/$quote_path/assets/js/quote.js'></script>"
        ]);

    Route::get('/admin/leads/assignAgent/{quote_no}', 'QuotesController@assignAgent')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/leads/createTask/{agent_id}', 'QuotesController@createTaskForAgent')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/quotes/addquote/{id}', 'QuotesController@add')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ])
        ->assignResources([
            '<link rel="stylesheet" href="' . RELATIVE_PROJECT_PATH . '/tools/Autocomplete-master/jquery.autocomplete.css">',
            '<script src="' . RELATIVE_PROJECT_PATH . '/tools/Autocomplete-master/jquery.autocomplete.js"></script>'
        ]);

    Route::get('/admin/tasks/addtask/{customerid}', 'TasksController@add')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/tasks/markascomplete/{id}/{destination}', 'TasksController@markAsComplete')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/tasks/delete/{id}/{destination}', 'TasksController@delete');

    Route::get('/admin/customers/deleteentity/{id}/{customerid}', 'CustomersController@deleteEntity')
        ->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::post('/admin/customers/{action}/{id}', 'CustomersController@index')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::get('/admin/customers/getfullentity/{id}/{customerid}', 'CustomersController@getfullentity')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/customers/editfullentity/{id}/{customerid}', 'CustomersController@editfullentity')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);


    Route::get('/admin/customers/{action}/{id}', 'CustomersController@index')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::post('/admin/quotes/{action}', 'QuotesController@index')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::post('/admin/navigation/access/savepolicy/', 'AccessController@savepolicy')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/navigation/access/policies/{alias}', 'AccessController@policies')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ])
        ->assignResources([
            '<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">',
            '<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>',
            '<script>
                    $(function() {
                            $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
                            e.preventDefault();
                            
                            $(this).siblings(\'a.active\').removeClass("active");
                            $(this).addClass("active");
                            var index = $(this).index();
                            
                            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
                            $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
                            
                            $(".checkbox-toggle").bootstrapToggle();
                        });
                    });
                </script>',
            '<style>
              .slow .toggle-group { transition: left 0.7s; -webkit-transition: left 0.7s; }
              .fast .toggle-group { transition: left 0.1s; -webkit-transition: left 0.1s; }
              .quick .toggle-group { transition: none; -webkit-transition: none; }
            </style>'
        ]);

    Route::get('/admin/navigation/access/{action}/{id}', 'AccessController@index')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::get('/admin/navigation/additem/{groupid}/{id}', 'NavigationController@addItem')
        ->assignPanels('uplon', [
            '_ajax' => TRUE
        ]);

    Route::get('/admin/navigation/deleteitem/{id}/{groupid}', 'NavigationController@deleteItem')
        ->assignPanels('uplon', [
            '_ajax' => TRUE
        ]);

    Route::get('/admin/navigation/deletegroup/{id}', 'NavigationController@deletegroup')
        ->assignPanels('uplon', [
            '_ajax' => TRUE
        ]);

    Route::get('/admin/navigation/savegroup', 'NavigationController@savegroup')
        ->assignPanels('uplon', [
            '_ajax' => TRUE
        ]);

    Route::get('/admin/navigation/{action}/{id}', 'NavigationController@index')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::get('/admin/editrates/{id}', 'RatesController@editModal')
        ->attachTemplate('admin')->assignPanels([
            '_ajax' => TRUE
        ]);

    Route::get('/admin/{element}/{action}/{id}', '{default}')
        ->attachTemplate('admin')
        ->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::post('/admin/{element}/{action}', '{default}')
        ->attachTemplate('admin')->assignPanels([
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ]);

    Route::get('/admin/rates/{action}/{id}', 'RatesController')
        ->assignPanels('admin', [
            'top' => 'UsersController@index:login',
            'navigation' => 'NavigationController@display:menus',
            'logout' => 'UsersController@logout:logout'
        ])
        ->assignResources([
            '<script src="' . RELATIVE_PROJECT_PATH . '/admin/rates/assets/js/rates.js"></script>'
        ]);
});

