<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RankingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('throttle:game-read');

Route::view('/download', 'pages.download')->name('download');
Route::get('/download/client', [DownloadController::class, 'client'])->name('download.client')->middleware('throttle:download');
Route::get('/download/patch', [DownloadController::class, 'patch'])->name('download.patch')->middleware('throttle:download');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/refund', 'pages.refund')->name('refund');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/about', 'pages.about')->name('about');
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking')->middleware('throttle:game-read');
Route::get('/news', [NewsController::class, 'index'])->name('news')->middleware('throttle:game-read');

Route::middleware('guest:metin2')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware(['honeypot', 'throttle:auth']);

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware(['honeypot', 'throttle:register']);

    Route::get('/forgot-password', [PasswordController::class, 'showForgotForm'])->name('password.forgot.form');
    Route::post('/forgot-password', [PasswordController::class, 'forgot'])->name('password.forgot')->middleware(['honeypot', 'throttle:password-reset']);
});

Route::middleware('auth:metin2')->group(function () {
    Route::get('/account', [AccountController::class, 'show'])->name('account');
    Route::post('/account/password', [AccountController::class, 'changePassword'])->name('account.password')->middleware('throttle:auth');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
