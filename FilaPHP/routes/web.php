<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\HomeController;
use App\Http\Controllers\Blog\PostController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/post/{slug}', [PostController::class, 'show'])->name('post.show');
Route::post('/post/{post}/comment', [PostController::class, 'comment'])->name('post.comment');
Route::post('/post/{post}/like', [PostController::class, 'like'])->name('post.like');
