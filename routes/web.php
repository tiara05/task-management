<?php

use Illuminate\Support\Facades\Route;


Route::view('/login', 'login')->name('login');
Route::view('/dashboard', 'dashboard')->name('dashboard'); 
Route::view('/admin', 'admin')->name('admin'); 