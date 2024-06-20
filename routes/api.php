<?php

use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(['auth.api'])->group(function () {
    Route::get('/get_users', [UserController::class, 'get_all_users']); //Todos los datos del usuario 
    Route::resource('/issues', IssueController::class)->except(['create', 'edit']);
});

