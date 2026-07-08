<?php

use Slim\Routing\RouteCollectorProxy;
use App\Middlewares\Api\GuestMiddleware;
use App\Middlewares\Api\UserMiddleware;

$app->group('/api/v1/guest', function (RouteCollectorProxy $group) {

    $group->post('/login', [App\Controllers\Guest\LoginControllerApi::class, 'verify'])->setName('api.guest.verify');
    $group->post('/register', [App\Controllers\Guest\RegisterController::class, 'verify'])->setName('api.guest.register');
    $group->post('/set-password', [App\Controllers\Guest\SetPasswordController::class, 'verify'])->setName('api.guest.set-password');
    $group->post('/forgot-password', [App\Controllers\Guest\ForgotPasswordController::class, 'verify'])->setName('api.guest.forgot-password');

})->add(new GuestMiddleware);



$app->group('/api/v1/user', function (RouteCollectorProxy $group) {

    $group->get('/dashboard', [App\Controllers\User\DashboardController::class, 'get'])->setName('api.user.dashboard');
    $group->get('/me', [App\Controllers\User\MeController::class, 'index'])->setName('api.user.me');
    $group->post('/refresh', [App\Controllers\User\RefreshController::class, 'index'])->setName('api.user.refresh');

})->add(new UserMiddleware);

$app->group('/api/v1/roles', function (RouteCollectorProxy $group) {

    $group->get('', [App\Controllers\Roles\IndexController::class, 'list'])->setName('api.roles.index');

})->add(new UserMiddleware(['Administrator']));


$app->group('/api/v1/categories', function (RouteCollectorProxy $group) {

    $group->get('', [App\Controllers\Categories\IndexController::class, 'index'])->setName('api.categories.index');

});

$app->group('/api/v1/home', function (RouteCollectorProxy $group) {

    $group->get('', [App\Controllers\Home\IndexController::class, 'index'])->setName('api.home.index');

});

$app->group('/api/v1/businesses', function (RouteCollectorProxy $group) {

    $group->get('',[App\Controllers\Businesses\IndexController::class, 'index'])->setName('api.businesses.index');

    $group->get('/my',[App\Controllers\Businesses\IndexController::class, 'my'])->setName('api.businesses.my');
    $group->get('/{slug}',[App\Controllers\Businesses\IndexController::class, 'business'])->setName('api.businesses.business');
    $group->post('/{slug}/update',[App\Controllers\Businesses\UpdateController::class, 'update'])->setName('api.businesses.update');

    $group->post('/create',[App\Controllers\Businesses\CreateController::class, 'create'])->setName('api.businesses.create');

})->add(new UserMiddleware);

$app->group('/api/v1/keywords', function (RouteCollectorProxy $group) {

    $group->get('',   [App\Controllers\Keywords\IndexController::class, 'index'] )->setName('api.keywords.index');

    $group->get( '/search', [App\Controllers\Keywords\IndexController::class, 'search'])->setName('api.keywords.search');

})->add(new UserMiddleware);

$app->group('/api/v1/upload', function (RouteCollectorProxy $group) {

    $group->post('', [App\Controllers\UploadController::class, 'index'])->setName('upload');
    $group->post('/remove', [App\Controllers\UploadController::class, 'remove'])->setName('upload.remove');
    $group->post('/delete', [App\Controllers\UploadController::class, 'delete'])->setName('upload.delete');
})->add(new UserMiddleware);

$app->group('/api/v1/locations', function (RouteCollectorProxy $group) {

    $group->get('',   [App\Controllers\Locations\IndexController::class, 'index'] )->setName('api.locations.index');

    $group->get( '/search', [App\Controllers\Locations\IndexController::class, 'search'])->setName('api.locations.search');

})->add(new UserMiddleware);
