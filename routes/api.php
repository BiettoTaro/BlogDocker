<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;

// User routes
Route::get('/users', [UserController::class, 'getAllUsers']);
Route::post('/users', [UserController::class, 'createUser']);
Route::get('/users/{user}', [UserController::class, 'getUser']);
Route::put('/users/{user}', [UserController::class, 'updateUser']);
Route::delete('/users/{user}', [UserController::class, 'deleteUser']);

// Blog routes
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);
Route::post('/blogs', [BlogController::class, 'store']);
Route::put('/blogs/{id}', [BlogController::class, 'update']);
Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

// Comment route
Route::post('/comments', [CommentController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::post('/blogs', [BlogController::class, 'store']);
});