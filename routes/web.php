<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::middleware(['auth'])->group(function () {
    Route::resource('/posts', PostController::class);

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/index', [PostController::class, 'index'])->name('posts.index');
Route::get('/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

