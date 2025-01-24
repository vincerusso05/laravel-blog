<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::middleware(['auth'])->group(function () {
    Route::resource('/posts', PostController::class);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
});


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
