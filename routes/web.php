<?php

use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

Route::middleware('auth:sanctum')->group(function () {
    //Ajsutes basicos de la WebService
    Route::get('/web/users', [UserController::class, 'index']);//Usuarios para el sistema
    Route::get('/web/profile', [UserController::class, 'profile']);//Usuarios para el sistema
    Route::get('/web/get_users', [UserController::class, 'get_all_users']); //Todos los datos del usuario 
    Route::post('/web/users', [UserController::class, 'store']);
    Route::put('/web/users/{user}', [UserController::class, 'update']);
    Route::delete('/web/users/{user}', [UserController::class, 'destroy']);
    Route::delete('/web/users', [UserController::class, 'bulkDelete']);
    Route::get('/web/change_theme', [UserController::class, 'change_theme']);
    Route::get('/web/roles', [UserController::class, 'get_roles']);

    //Tareas todos los metodos
    Route::resource('/web/tasks', TaskController::class)->except('show', 'create');
    Route::get('/web/get_tasks_today', [TaskController::class, 'get_tasks_today']);
    Route::get('/web/get_tasks_week', [TaskController::class, 'get_tasks_week']);
    Route::get('/web/get_tasks_two_weeks', [TaskController::class, 'get_tasks_two_weeks']);
    Route::get('/web/get_tasks_month', [TaskController::class, 'get_tasks_month']);
    Route::get('/web/get_tasks_year', [TaskController::class, 'get_tasks_year']);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
