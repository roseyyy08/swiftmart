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
    Route::post('/products/restock-lookup', [\App\Http\Controllers\ProductController::class, 'restockLookup'])->name('products.restock-lookup');
    Route::post('/products/restock-confirm', [\App\Http\Controllers\ProductController::class, 'restockConfirm'])->name('products.restock-confirm');
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('members', \App\Http\Controllers\MemberController::class);
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.export-pdf');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [\App\Http\Controllers\SelfCheckoutController::class, 'index'])->name('index');
    Route::post('/scan', [\App\Http\Controllers\SelfCheckoutController::class, 'scan'])->name('scan');
    Route::post('/cart/update', [\App\Http\Controllers\SelfCheckoutController::class, 'updateCart'])->name('cart.update');
    Route::post('/member/check', [\App\Http\Controllers\SelfCheckoutController::class, 'checkMember'])->name('member.check');
    Route::post('/process', [\App\Http\Controllers\SelfCheckoutController::class, 'process'])->name('process');
    Route::get('/receipt/{transaction}', [\App\Http\Controllers\SelfCheckoutController::class, 'receipt'])->name('receipt');
    Route::get('/pair/{token}', [\App\Http\Controllers\SelfCheckoutController::class, 'scanDevice'])->name('scan-device');
    Route::get('/cart-state/{token}', [\App\Http\Controllers\SelfCheckoutController::class, 'cartState'])->name('cart-state');
});

// Khusus buat HP admin scan barcode (bukan buat customer, beda dari checkout).
Route::get('/admin-scan/{token}', [\App\Http\Controllers\AdminScanController::class, 'page'])->name('admin-scan.page');
Route::post('/admin-scan/push', [\App\Http\Controllers\AdminScanController::class, 'push'])->name('admin-scan.push');

// poll() dipanggil dari LAPTOP (halaman admin Produk), makanya wajib login.
Route::middleware('auth')->get('/admin-scan/{token}/poll', [\App\Http\Controllers\AdminScanController::class, 'poll'])->name('admin-scan.poll');