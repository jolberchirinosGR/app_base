<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LostObjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

Route::middleware('auth:sanctum')->group(function () {
    //Ajsutes basicos de la WebService
    Route::get('/web/users', [UserController::class, 'index']);//Usuarios para el sistema
    Route::get('/web/get_users', [UserController::class, 'getAllUsers']); //Todos los datos del usuario 
    Route::post('/web/users', [UserController::class, 'store']);
    Route::put('/web/users/{user}', [UserController::class, 'update']);
    Route::delete('/web/users/{user}', [UserController::class, 'destory']);
    Route::delete('/web/users', [UserController::class, 'bulkDelete']);

    //Indcidencias
    Route::resource('/web/issues', IssueController::class)->except(['create', 'edit']);

    //Objectos perdidos
    Route::resource('/web/lost_objects', LostObjectController::class)->except(['create', 'edit']);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
