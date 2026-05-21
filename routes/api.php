<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::delete('/user/logout', [UserController::class, 'logout']);
    Route::post('/tasks', [TaskController::class, 'create']);
    Route::get('/tasks', [TaskController::class, 'getList']);
});
