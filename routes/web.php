<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RankingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/download', 'pages.download')->name('download');
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
Route::get('/news', [NewsController::class, 'index'])->name('news');

Route::middleware('guest:metin2')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('honeypot');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('honeypot');

    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.forgot.form');
    Route::post('/forgot-password', [PasswordController::class, 'forgot'])->name('password.forgot')->middleware('honeypot');
});

Route::middleware('auth:metin2')->group(function () {
    Route::get('/account', [AccountController::class, 'show'])->name('account');
    Route::post('/account/password', [AccountController::class, 'changePassword'])->name('account.password');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
