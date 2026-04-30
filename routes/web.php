<?php

use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type,Authorization,X-SELECT');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');

Route::get('/', [HomeController::class, 'index'])->name('get.home');

// Public pages built with the page builder
Route::get('/p/{slug}', [PageController::class, 'show'])->name('page.show');
