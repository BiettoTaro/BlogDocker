<?php

use Illuminate\Support\Facades\Route;
use MLL\GraphiQL\GraphiQLController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'show'])->name('comments.show');
Route::get('/graphiql', GraphiQLController::class);