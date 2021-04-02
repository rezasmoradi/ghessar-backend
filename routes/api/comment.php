<?php

use App\Http\Controllers\CommentController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api']], function (Router $router){

    $router->get('/{twit}', [CommentController::class, 'index']);
    $router->post('/{twit}', [CommentController::class, 'create']);

    $router->get('/like/{comment}', [CommentController::class, 'like']);
    $router->get('/unlike/{comment}', [CommentController::class, 'unlike']);

    $router->get('/retwit/{comment}', [CommentController::class, 'retwit']);
    $router->get('/unretwit/{comment}', [CommentController::class, 'unretwit']);

    $router->post('/report/{comment}', [CommentController::class, 'report']);
});
