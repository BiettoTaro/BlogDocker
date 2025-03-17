<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'getAllUsers']);
Route::post('/users', [UserController::class, 'createUser']);
Route::get('/users/{user}', [UserController::class, 'getUser']);
Route::put('/users/{user}', [UserController::class, 'updateUser']);
Route::delete('/users/{user}', [UserController::class, 'deleteUser']);
