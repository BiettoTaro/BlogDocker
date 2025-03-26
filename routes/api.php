<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;


// Public routes
Route::post('/users', [UserController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'login']);






// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::get('/users/{user}', [UserController::class, 'getUser']);
    Route::put('/users/{user}', [UserController::class, 'updateUser']);
    Route::delete('/users/{user}', [UserController::class, 'deleteUser']);
    Route::post('/users/{id}/restore', [UserController::class, 'restore']);
    Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete']);

    // Blog routes
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{id}', [BlogController::class, 'show']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
    Route::post('/blogs/{id}/restore', [BlogController::class, 'restore']);
    Route::delete('/blogs/{id}/force-delete', [BlogController::class, 'forceDelete']);

    // Comment route
    Route::post('/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    Route::post('/comments/{id}/restore', [CommentController::class, 'restore']);
    Route::delete('/comments/{id}/force-delete', [CommentController::class, 'forceDelete']);
});