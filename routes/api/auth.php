<?php


use App\Http\Controllers\AuthController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:api']], function (Router $router) {
    $router->post('/auth', [AuthController::class, 'auth']);
    $router->post('/logout', [AuthController::class, 'logout']);
});
