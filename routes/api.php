<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\MediaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);


// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('posts', PostController::class);
    Route::apiResource('games', GameController::class);
    Route::apiResource('media', MediaController::class);

});