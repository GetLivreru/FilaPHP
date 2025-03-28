<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\HomeController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

// Публичные маршруты
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/post/{slug}', [PostController::class, 'show'])->name('post.show');

// Маршруты аутентификации
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::post('/post/{post}/comment', [PostController::class, 'comment'])->name('post.comment');
    Route::post('/post/{post}/like', [PostController::class, 'like'])->name('post.like');
});
