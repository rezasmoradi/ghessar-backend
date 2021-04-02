<?php

use App\Http\Controllers\TwitController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function (Router $route) {
    $route->get('/', [TwitController::class, 'index']);
    $route->get('/{twit}', [TwitController::class, 'show']);

    $route->get('/search/{search}', [TwitController::class, 'search']);

    $route->post('/', [TwitController::class, 'create']);
    $route->delete('/{twit}', [TwitController::class, 'delete']);

    $route->get('/like/{twit}', [TwitController::class, 'like']);
    $route->get('/unlike/{twit}', [TwitController::class, 'unlike']);

    $route->get('/retwit/{twit}', [TwitController::class, 'retwit']);
    $route->get('/unretwit/{twit}', [TwitController::class, 'unretwit']);

    $route->get('/report/types', [TwitController::class, 'reportTypes']);
    $route->post('/report/{twit}', [TwitController::class, 'report']);

    $route->get('/bookmark/{twit}', [TwitController::class, 'bookmark']);
    $route->get('/unmark/{twit}', [TwitController::class, 'unmark']);
});
