<?php
    /*
    |--------------------------------------------------------------------------
    | Define the app routes here.
    |--------------------------------------------------------------------------
    */
   /*
    * Note that the group method can go down for 2 levels for now.
    * If you need a third level, register it as a second level and just add the second level + third level string to it.
    *
    */
    $app->get('/', [new Controller\DashboardController, 'index']);
    $app->group('/admin',function($app) {
        $app->group('/users', function ($app, $container) {
            $app->get('', [new Controller\UsersController, 'index']);
            $app->get('/edit/:id', [new Controller\UsersController, 'edit']);
            $app->get('/create', [new Controller\UsersController, 'create']);
        });
        $app->group('/posts', function ($app, $container) {
            $app->get('', [new Controller\PostsController, 'index']);
            $app->get('/edit/:id', [new Controller\PostsController, 'edit']);
            $app->get('/create', [new Controller\PostsController, 'create']);
        });
        $app->group('/categories', function ($app, $container) {
            $app->get('', [new Controller\CategoriesController, 'index']);
            $app->get('/edit/:id', [new Controller\CategoriesController, 'edit']);
            $app->get('/create', [new Controller\CategoriesController, 'create']);
        });
        $app->group('/tags', function ($app, $container) {
            $app->get('', [new Controller\TagsController, 'index']);
            $app->get('/edit/:id', [new Controller\TagsController, 'edit']);
            $app->get('/create', [new Controller\TagsController, 'create']);
        });
        $app->group('/pages', function ($app, $container) {
            $app->get('', [new Controller\PagesController, 'index']);
            $app->get('/edit/:id', [new Controller\PagesController(), 'edit']);
            $app->get('/create', [new Controller\PagesController(), 'create']);
        });
        $app->group('/contacts', function ($app, $container) {
            $app->get('', [new Controller\ContactsController, 'index']);
            $app->get('/edit/:id', [new Controller\ContactsController(), 'edit']);
            $app->get('/create', [new Controller\ContactsController(), 'create']);
        });
        $app->group('/products', function ($app, $container) {
            $app->get('', [new Controller\ProductsController, 'index']);
            $app->get('/edit/:id', [new Controller\ProductsController, 'edit']);
            $app->get('/create', [new Controller\ProductsController, 'create']);
            $app->get('/delete', [new Controller\ProductsController, 'delete']);
            $app->get('/info/:id/:name', [new Controller\ProductsController, 'info']);
        });
        $app->group('/files', function ($app, $container) {
            $app->get('', [new Controller\FilesController, 'index']);
            $app->get('/edit/:id', [new Controller\FilesController, 'edit']);
            $app->get('/create', [new Controller\FilesController, 'create']);
            $app->get('/delete', [new Controller\FilesController, 'delete']);
        });
    });



