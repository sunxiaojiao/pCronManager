<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('/jobs/{id}/copy', 'JobController@copy');
    $router->resource('jobs', JobController::class);
    $router->resource('vars', VarsController::class);
    $router->resource('logs', LogController::class);
    $router->resource('tags', TagController::class);

});
