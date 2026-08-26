<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function (){
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
});