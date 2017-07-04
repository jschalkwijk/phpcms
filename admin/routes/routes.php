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
            $app->map('', [new Controller\PostsController, 'index'],['GET','POST']);
            $app->map('/deleted', [new Controller\PostsController, 'deleted'],['GET','POST']);
            $app->map('/edit/:id', [new Controller\PostsController, 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\PostsController, 'create'],['GET','POST']);
            $app->post('/action', [new Controller\PostsController, 'action']);
            $app->get('/approve/:id', [new Controller\PostsController, 'approve']);
            $app->get('/hide/:id', [new Controller\PostsController, 'hide']);
            $app->get('/trash/:id', [new Controller\PostsController, 'trash']);
            $app->get('/destroy/:id', [new Controller\PostsController, 'destroy']);
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
            $app->map('', [new Controller\UploadsController, 'index'],['GET','POST']);
            $app->post('/create', [new Controller\UploadsController, 'create']);
            $app->map('/edit/:id', [new Controller\UploadsController, 'edit'],['GET','POST']);
            $app->get('/delete', [new Controller\UploadsController, 'delete']);
            $app->post('/action', [new Controller\UploadsController, 'action']);
            $app->get('/destroy/:id', [new Controller\UploadsController, 'destroy']);
        });
        $app->group('/folders',function($app,$container){
            $app->get('',[new Controller\FoldersController,'index']);
            $app->map('/create', [new Controller\FoldersController, 'create'],['GET','POST']);
            $app->get('/:id/:name', [new Controller\FoldersController, 'show']);
            $app->map('/edit/:id', [new Controller\FoldersController, 'edit'],['GET','POST']);
            $app->get('/delete', [new Controller\FoldersController, 'delete']);
            $app->post('/action', [new Controller\FoldersController, 'action']);
        });
    });



