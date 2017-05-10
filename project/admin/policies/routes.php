<?php

use Jenga\App\Project\Routing\Route;

Route::any('/policies/previewpolicy/{id}/{pdf}', 'PoliciesController@previewPolicy')
        ->attachTemplate('preview')
        ->assignPanels([
            'logout' => 'UsersController@logout:logout'
        ])->assignResources([
    '<link rel="stylesheet" type="text/css" href="' . RELATIVE_PROJECT_PATH . '/templates/admin/assets/font-awesome-4.7.0/css/font-awesome.min.css"/>'
]);
Route::get('/admin/policies/downloaddocs/{id}', 'PoliciesController@showRelatedDocs')
        ->attachTemplate('admin')
        ->assignPanels(['_ajax' => TRUE]);

Route::get('/customer/my-policies', 'PoliciesController@myPolicies')
    ->attachTemplate('admin')
    ->assignPanels([
        'top' => 'UsersController@index:login',
        'navigation' => 'NavigationController@display:menus',
        'logout' => 'UsersController@logout:logout'
    ]);

Route::group(['before' => 'auth.check'], function () {

    Route::any('/admin/policies/deletedoc/{policy_id}/{id}', 'PoliciesController@deletedoc')
            ->assignPanels(['_ajax' => TRUE]);
    Route::get('/admin/policies/createpolicy/{offer}', 'PoliciesController@createpolicy')
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
    ]);

    Route::get('/admin/policies/emailPolicy/{id}', 'PoliciesController@emailPolicy')
            ->attachTemplate('admin')
            ->assignPanels(['_ajax' => TRUE]);

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
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
    ]);
});
