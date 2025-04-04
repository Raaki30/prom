<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayController;
use Illuminate\Http\Request;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return view('welcome');
});

// Public Routes
Route::get('/pesan', function () {
    return view('pay.pesan');
})->name('pesan');

Route::get('/validate-nis/{nis}', [PayController::class, 'validateNis'])->name('validate.nis');
Route::get('/search-siswa', [SiswaController::class, 'search'])->name('siswa.search');

// Payment Routes with middleware
Route::middleware('payment')->group(function () {
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/init', [PayController::class, 'initPayment'])->name('init');
        Route::get('/init', [PayController::class, 'initPayment'])->name('init');
        Route::post('/process', [PayController::class, 'processPayment'])->name('process');
        Route::get('/process', [PayController::class, 'processPayment'])->name('process');
        Route::post('/upload', [PayController::class, 'uploadbukti'])->name('upload');
        Route::get('/upload', [PayController::class, 'uploadbukti'])->name('upload');
        Route::get('/status/{order_id}', [PayController::class, 'checkStatus'])->name('status');
    });

    
});

// Scanner Routes
Route::get('/scan', function () {
    return view('scan');
})->name('scan');

Route::post('/scan/validate', [PayController::class, 'validateScan'])->name('scan.validate');
