<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TiketController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/scan/validate', [TiketController::class, 'validateScan'])->name('scan.validate');

Route::get('/validate-nis/{nis}', [PayController::class, 'validateNis'])->name('validate.nis');
Route::get('/search-siswa', [SiswaController::class, 'search'])->name('siswa.search');
Route::post('/scan/manual-checkin', [TiketController::class, 'manualCheckin'])->name('scan.manual.checkin');
