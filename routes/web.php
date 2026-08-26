<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



Route::middleware('auth')->group(function (){
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
});