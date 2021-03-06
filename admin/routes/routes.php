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
    $app->group('/admin',function($app,$container) {
        $user = $container->user;
        $app->group('/users', function ($app, $container) {
            $app->map('', [new Controller\UsersController, 'index'],['GET','POST']);
            $app->get('/:id', [new Controller\UsersController, 'show']);
            $app->map('/deleted', [new Controller\UsersController, 'deleted'],['GET','POST']);
            if($container->user->hasRole('admin')){
                $app->map('/edit/:id', [new Controller\UsersController, 'edit'],['GET','POST']);
                $app->map('/create', [new Controller\UsersController, 'create'],['GET','POST']);
                $app->post('/action', [new Controller\UsersController, 'action']);
                $app->get('/approve/:id', [new Controller\UsersController, 'approve']);
                $app->get('/hide/:id', [new Controller\UsersController, 'hide']);
                $app->get('/trash/:id', [new Controller\UsersController, 'trash']);
                $app->get('/destroy/:id', [new Controller\UsersController, 'destroy']);
            }
        });
        if ($user->hasRole('admin')) {
            $app->group('/roles', function ($app, $container) {
                $app->map('', [new Controller\RolesController, 'index'], ['GET', 'POST']);
                $app->get('/:id', [new Controller\RolesController, 'show']);
                $app->map('/deleted', [new Controller\RolesController, 'deleted'], ['GET', 'POST']);
                $app->map('/edit/:id', [new Controller\RolesController, 'edit'], ['GET', 'POST']);
                $app->post('/update/:id', [new Controller\RolesController, 'update']);
                $app->map('/create', [new Controller\RolesController, 'create'], ['GET', 'POST']);
                $app->post('/action', [new Controller\RolesController, 'action']);
                $app->get('/approve/:id', [new Controller\RolesController, 'approve']);
                $app->get('/hide/:id', [new Controller\RolesController, 'hide']);
                $app->get('/trash/:id', [new Controller\RolesController, 'trash']);
                $app->get('/destroy/:id', [new Controller\RolesController, 'destroy']);
            });

            $app->group('/permissions', function ($app, $container) {
                $app->map('', [new Controller\PermissionsController, 'index'], ['GET', 'POST']);
                $app->get('/:id', [new Controller\PermissionsController, 'show']);
                $app->map('/deleted', [new Controller\PermissionsController, 'deleted'], ['GET', 'POST']);
                $app->map('/edit/:id', [new Controller\PermissionsController, 'edit'], ['GET', 'POST']);
                $app->post('/update/:id', [new Controller\PermissionsController, 'create']);
                $app->map('/create', [new Controller\PermissionsController, 'create'], ['GET', 'POST']);
                $app->post('/action', [new Controller\PermissionsController, 'action']);
                $app->get('/approve/:id', [new Controller\PermissionsController, 'approve']);
                $app->get('/hide/:id', [new Controller\PermissionsController, 'hide']);
                $app->get('/trash/:id', [new Controller\PermissionsController, 'trash']);
                $app->get('/destroy/:id', [new Controller\PermissionsController, 'destroy']);
            });
        }

        if ($user->hasRole('admin','author')) {
            $app->group('/posts', function ($app, $container) {
                $app->map('', [new Controller\PostsController, 'index'], ['GET', 'POST']);
                $app->map('/deleted', [new Controller\PostsController, 'deleted'], ['GET', 'POST']);
                $app->get('/:id', [new Controller\PostsController, 'show']);
                $app->map('/edit/:id', [new Controller\PostsController, 'edit'], ['GET', 'POST']);
                $app->post('/update/:id', [new Controller\PostsController, 'update']);
                $app->map('/create', [new Controller\PostsController, 'create'], ['GET', 'POST']);
                $app->post('/action', [new Controller\PostsController, 'action']);
                $app->get('/approve/:id', [new Controller\PostsController, 'approve']);
                $app->get('/hide/:id', [new Controller\PostsController, 'hide']);
                $app->get('/trash/:id', [new Controller\PostsController, 'trash']);
                $app->get('/destroy/:id', [new Controller\PostsController, 'destroy']);
                $app->get('/locked', [new Controller\PostsController, 'locked']);
            });
            $app->group('/categories', function ($app, $container) {
                $app->get('', [new Controller\CategoriesController, 'index']);
                $app->get('/:id', [new Controller\CategoriesController, 'show']);
                $app->map('/edit/:id', [new Controller\CategoriesController, 'edit'], ['GET', 'POST']);
                $app->map('/create', [new Controller\CategoriesController, 'create'], ['GET', 'POST']);
                $app->post('/action', [new Controller\PostsController, 'action']);
                $app->get('/approve/:id', [new Controller\PostsController, 'approve']);
                $app->get('/hide/:id', [new Controller\PostsController, 'hide']);
                $app->get('/trash/:id', [new Controller\PostsController, 'trash']);
                $app->get('/destroy/:id', [new Controller\PostsController, 'destroy']);
            });
            $app->group('/tags', function ($app, $container) {
                $app->get('', [new Controller\TagsController, 'index']);
                $app->map('/edit/:id', [new Controller\TagsController, 'edit'], ['GET', 'POST']);
                $app->map('/create', [new Controller\TagsController, 'create'], ['GET', 'POST']);
            });

            $app->group('/comments', function ($app, $container) {
                $app->map('', [new Controller\CommentsController, 'index'], ['GET', 'POST']);
                $app->map('/deleted', [new Controller\CommentsController, 'deleted'], ['GET', 'POST']);
                $app->map('/edit/:id', [new Controller\CommentsController, 'edit'], ['GET', 'POST']);
                $app->map('/create', [new Controller\CommentsController, 'create'], ['GET', 'POST']);
                $app->post('/action', [new Controller\CommentsController, 'action']);
                $app->get('/approve/:id', [new Controller\CommentsController, 'approve']);
                $app->get('/hide/:id', [new Controller\CommentsController, 'hide']);
                $app->get('/trash/:id', [new Controller\CommentsController, 'trash']);
                $app->get('/destroy/:id', [new Controller\CommentsController, 'destroy']);
            });
            $app->group('/replies', function ($app, $container) {
                $app->map('', [new Controller\RepliesController, 'index'], ['GET', 'POST']);
                $app->map('/edit/:id', [new Controller\RepliesController, 'edit'], ['GET', 'POST']);
                $app->map('/create', [new Controller\RepliesController, 'create'], ['GET', 'POST']);
                $app->post('/action', [new Controller\RepliesController, 'action']);
                $app->get('/approve/:id', [new Controller\RepliesController, 'approve']);
                $app->get('/hide/:id', [new Controller\RepliesController, 'hide']);
                $app->get('/trash/:id', [new Controller\RepliesController, 'trash']);
                $app->get('/destroy/:id', [new Controller\RepliesController, 'destroy']);
            });
        }

        $app->group('/pages', function ($app, $container) {
            $app->get('', [new Controller\PagesController, 'index']);
            $app->map('/edit/:id', [new Controller\PagesController(), 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\PagesController(), 'create'],['GET','POST']);
        });
        $app->group('/contacts', function ($app, $container) {
            $app->get('', [new Controller\ContactsController, 'index'],['GET','POST']);
            $app->get('/deleted', [new Controller\ContactsController, 'deleted'],['GET','POST']);
            $app->get('/edit/:id', [new Controller\ContactsController(), 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\ContactsController(), 'create'],['GET','POST']);
            $app->post('/action', [new Controller\ContactsController, 'action']);
            $app->get('/approve/:id', [new Controller\ContactsController, 'approve']);
            $app->get('/hide/:id', [new Controller\ContactsController, 'hide']);
            $app->get('/trash/:id', [new Controller\ContactsController, 'trash']);
            $app->get('/destroy/:id', [new Controller\ContactsController, 'destroy']);
        });
        $app->group('/products', function ($app, $container) {
            $app->get('', [new Controller\ProductsController, 'index']);
            $app->map('/edit/:id', [new Controller\ProductsController, 'edit'],['GET','POST']);
            $app->map('/create', [new Controller\ProductsController, 'create'],['GET','POST']);
            $app->get('/delete', [new Controller\ProductsController, 'delete']);
            $app->get('/info/:id/:name', [new Controller\ProductsController, 'info']);
            $app->post('/action', [new Controller\ProductsController, 'action']);
            $app->get('/approve/:id', [new Controller\ProductsController, 'approve']);
            $app->get('/hide/:id', [new Controller\ProductsController, 'hide']);
            $app->get('/trash/:id', [new Controller\ProductsController, 'trash']);
            $app->get('/destroy/:id', [new Controller\ProductsController, 'destroy']);
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
            $app->get('/destroy/:id', [new Controller\FoldersController, 'destroy']);
            $app->map('/edit/:id', [new Controller\FoldersController, 'edit'],['GET','POST']);
        });
        $app->get('/locked',[new Controller\Locked,'index']);
    });



