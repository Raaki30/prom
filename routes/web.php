<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayController;
use Illuminate\Http\Request;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TiketController;
use App\Models\Tiket;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/scan', function () {
        return view('scan');
    })->name('scan');
    

Route::get('/tiket', function () {
    $data = Tiket::orderBy('created_at', 'desc')->paginate(request('perPage', 10));
    return view('tiket.index', compact('data'));
})->name('tiket.index');

    Route::post('/tiket/{id}/verifikasi', [TiketController::class, 'verifikasi'])->name('tiket.verifikasi');
});




Route::get('/', function () {
    return view('welcome');
});


Route::get('/pesan', function () {
    return view('payment.pesan');
})->name('pesan');

Route::get('/payment/afterpay', function () {
    return view('payment.success');
})->name('success');

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

Route::get('/eticket/{id}', [TiketController::class, 'show'])->name('eticket.show');



require __DIR__.'/auth.php';