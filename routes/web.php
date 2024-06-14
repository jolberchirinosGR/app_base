<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

Route::middleware('auth:sanctum')->group(function () {
    //Ajsutes basicos de la WebService
    Route::get('/web/users', [UserController::class, 'index']);//Usuarios para el sistema
    Route::get('/web/profile', [UserController::class, 'profile']);//Usuarios para el sistema
    Route::get('/web/get_users', [UserController::class, 'getAllUsers']); //Todos los datos del usuario 
    Route::post('/web/users', [UserController::class, 'store']);
    Route::put('/web/users/{user}', [UserController::class, 'update']);
    Route::delete('/web/users/{user}', [UserController::class, 'destory']);
    Route::delete('/web/users', [UserController::class, 'bulkDelete']);
    Route::get('/web/change_theme', [UserController::class, 'change_theme']);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
