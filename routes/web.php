<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThesisController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/', 'dashboard')->name('dashboard');
    Route::view('/tugas-akhir', 'tugas-akhir.index')->name('thesis');
    Route::view('/toefl', 'toefl.index')->name('toefl');
    Route::view('/career', 'career.index')->name('career');
});

Route::get('/tugas-akhir', [ThesisController::class, 'index'])->name('thesis');
Route::put('/tugas-akhir/info', [ThesisController::class, 'saveInfo'])->name('thesis.info');
Route::post('/tugas-akhir/tahap', [ThesisController::class, 'storeStage'])->name('thesis.stage.store');
Route::patch('/tugas-akhir/tahap/{stage}', [ThesisController::class, 'updateStage'])->name('thesis.stage.update');
Route::delete('/tugas-akhir/tahap/{stage}', [ThesisController::class, 'destroyStage'])->name('thesis.stage.destroy');
Route::post('/tugas-akhir/bimbingan', [ThesisController::class, 'storeLog'])->name('thesis.log.store');
Route::patch('/tugas-akhir/bimbingan/{log}', [ThesisController::class, 'toggleLog'])->name('thesis.log.toggle');
Route::delete('/tugas-akhir/bimbingan/{log}', [ThesisController::class, 'destroyLog'])->name('thesis.log.destroy');