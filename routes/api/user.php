<?php

use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function (Router $router) {

    $router->match(['post', 'put'], '/', [UserController::class, 'update']);
    $router->post('/profile', [UserController::class, 'setProfilePhoto']);
    $router->get('/', [UserController::class, 'me']);
    $router->get('/{user}', [UserController::class, 'user']);
    $router->post('/report/{user}', [UserController::class, 'report']);

    $router->get('/check-username/{username}', [UserController::class, 'checkUsername']);

    $router->get('/follow/{user}', [UserController::class, 'follow']);
    $router->get('/unfollow/{user}', [UserController::class, 'unfollow']);
    $router->get('/followers', [UserController::class, 'followers']);
    $router->get('/followings', [UserController::class, 'followings']);

    $router->get('/restrict/{user}', [UserController::class, 'restrict']);
    $router->get('/unrestrict/{user}', [UserController::class, 'unrestrict']);

    $router->get('/mute/{user}', [UserController::class, 'mute']);
    $router->get('/unmute/{user}', [UserController::class, 'unmute']);

    $router->get('/block/{user}', [UserController::class, 'block']);
    $router->get('/unblock/{user}', [UserController::class, 'unblock']);
    $router->get('/blocks', [UserController::class, 'blocks']);
});
