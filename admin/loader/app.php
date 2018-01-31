<?php
    /*
    |--------------------------------------------------------------------------
    | Starting the App.
    |--------------------------------------------------------------------------
    */
    /*
     * Start filling the Container with dependencies.
     * Feed the container to the new App instance.
     * Return the app.
     */
    use CMS\App;
    use CMS\Core\Container\Container;
    use CMS\Router\Router;
    use CMS\Router\Response;

    $container =  new Container([
        'router' => function () {
            return new Router;
        },
        'response' => function () {
            return new Response;
        },
        'errorHandler' => function () {
            return function ($response) {
                return $response->setBody('Page not found')->withStatus(404);
            };
        },
        'config' => function () {
            return require 'config/database.php';
        },
        'user' => function(){
            return \CMS\Core\Auth\Auth::user();
        }
    ]);

    $app = new App($container);

    return $app;