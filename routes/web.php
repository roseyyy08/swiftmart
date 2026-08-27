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
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SelfCheckoutController::class, 'index'])->name('index');
    Route::post('/scan', [\App\Http\Controllers\SelfCheckoutController::class, 'scan'])->name('scan');
    Route::post('/cart/update', [\App\Http\Controllers\SelfCheckoutController::class, 'updateCart'])->name('cart.update');
    Route::post('/member/check', [\App\Http\Controllers\SelfCheckoutController::class, 'checkMember'])->name('member.check');
    Route::post('/process', [\App\Http\Controllers\SelfCheckoutController::class, 'process'])->name('process');
    Route::get('/receipt/{transaction}', [\App\Http\Controllers\SelfCheckoutController::class, 'receipt'])->name('receipt');
});