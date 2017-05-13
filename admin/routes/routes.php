<?php
    /*
   |--------------------------------------------------------------------------
   | Define the app routes here.
   |--------------------------------------------------------------------------
   */
   /*
    * Note that the group method can go down for 2 levels for now.
    * If you need a third level, register it as a second level and add to it.
    *
    */

    $app->get('/', [new Controller\DashboardController, 'index']);
    $app->group('/users',function($app,$container){
        $app->get('', [new Controller\UsersController, 'index']);
        $app->get('/edit/:id', [new Controller\UsersController(), 'edit']);
        $app->get('/create', [new Controller\UsersController(), 'create']);
        $app->get('/delete', [new Controller\UsersController(), 'delete']);
    });
    $app->group('/users/profile',function($app,$container){
        $app->get('/delete', [new Controller\UsersController(), 'index']);

    });
