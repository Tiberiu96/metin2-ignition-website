<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('/download', 'pages.download')->name('download');
Route::view('/ranking', 'pages.ranking')->name('ranking');
Route::view('/news', 'pages.news')->name('news');

Route::view('/login', 'pages.login')->name('login');
Route::view('/register', 'pages.register')->name('register');
