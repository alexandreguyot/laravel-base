<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Site\HomeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['log'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('get.home');
    Route::get('/stats/{date?}', [HomeController::class, 'stats'])->name('get.stats');
});

Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('auth.google.callback');
Route::post('/auth/logout', [SocialiteController::class, 'logout'])->name('auth.logout');
Route::post('/auth/login', [LoginController::class, 'login'])->name('auth.login');
Route::post('/auth/register', [LoginController::class, 'register'])->name('auth.register');
