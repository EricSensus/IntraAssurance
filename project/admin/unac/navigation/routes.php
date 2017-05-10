<?php

//the admin template behind the login wall
use Jenga\App\Project\Routing\Route;

Route::group(['before' => 'auth.check'], function () {
    
    //access
    Route::post('/admin/navigation/access/savepolicy/', 'AccessController@savepolicy')
            ->attachTemplate('admin')->assignPanels(['_ajax' => TRUE]);

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
    
    //navigation
    Route::get('/admin/navigation/additem/{groupid}/{id}', 'NavigationController@addItem')
            ->assignPanels('uplon', ['_ajax' => TRUE]);

    Route::get('/admin/navigation/deleteitem/{id}/{groupid}', 'NavigationController@deleteItem')
            ->assignPanels('uplon', ['_ajax' => TRUE]);

    Route::get('/admin/navigation/deletegroup/{id}', 'NavigationController@deletegroup')
            ->assignPanels('uplon', ['_ajax' => TRUE]);

    Route::get('/admin/navigation/savegroup', 'NavigationController@savegroup')
            ->assignPanels('uplon', ['_ajax' => TRUE]);

    Route::get('/admin/navigation/{action}/{id}', 'NavigationController@index')
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
    ]);

    

    Route::get('/admin/navigation/access/{action}/{id}', 'AccessController@index')
            ->attachTemplate('admin')
            ->assignPanels([
                'top' => 'UsersController@index:login',
                'navigation' => 'NavigationController@display:menus',
                'logout' => 'UsersController@logout:logout'
    ]);
});
