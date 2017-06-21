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
            $app->map('/edit/:id', [new Controller\UsersController, 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\UsersController, 'create'],['GET','POST']);
        });
        $app->group('/posts', function ($app, $container) {
            $app->get('', [new Controller\PostsController, 'index']);
            $app->map('/edit/:id', [new Controller\PostsController, 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\PostsController, 'create'],['GET','POST']);
        });
        $app->group('/categories', function ($app, $container) {
            $app->get('', [new Controller\CategoriesController, 'index']);
            $app->map('/edit/:id', [new Controller\CategoriesController, 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\CategoriesController, 'create'],['GET','POST']);
        });
        $app->group('/tags', function ($app, $container) {
            $app->get('', [new Controller\TagsController, 'index']);
            $app->map('/edit/:id', [new Controller\TagsController, 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\TagsController, 'create'],['GET','POST']);
        });
        $app->group('/pages', function ($app, $container) {
            $app->get('', [new Controller\PagesController, 'index']);
            $app->map('/edit/:id', [new Controller\PagesController(), 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\PagesController(), 'create'],['GET','POST']);
        });
        $app->group('/contacts', function ($app, $container) {
            $app->get('', [new Controller\ContactsController, 'index']);
            $app->get('/edit/:id', [new Controller\ContactsController(), 'edit']);
            $app->map('/create', [new Controller\ContactsController(), 'create'],['GET','POST']);
        });
        $app->group('/products', function ($app, $container) {
            $app->get('', [new Controller\ProductsController, 'index']);
            $app->map('/edit/:id', [new Controller\ProductsController, 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\ProductsController, 'create'],['GET','POST']);
            $app->get('/delete', [new Controller\ProductsController, 'delete']);
            $app->get('/info/:id/:name', [new Controller\ProductsController, 'info']);
        });
        $app->group('/files', function ($app, $container) {
            $app->get('', [new Controller\FilesController, 'index']);
            $app->post('/create', [new Controller\FilesController, 'create']);
            $app->map('/edit/:id', [new Controller\FilesController, 'edit'],['GET','POST']);
            $app->get('/delete', [new Controller\FilesController, 'delete']);
        });
        $app->group('/folders',function($app,$container){
            $app->get('/:id/:name', [new Controller\FilesController, 'albums']);
        });
    });



