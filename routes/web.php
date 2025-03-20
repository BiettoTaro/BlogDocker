<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'show'])->name('comments.show');
