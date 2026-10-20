<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ThesisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToeflController;
use App\Http\Controllers\CareerController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tugas-akhir', [ThesisController::class, 'index'])->name('thesis');
    Route::put('/tugas-akhir/info', [ThesisController::class, 'saveInfo'])->name('thesis.info');
    Route::post('/tugas-akhir/tahap', [ThesisController::class, 'storeStage'])->name('thesis.stage.store');
    Route::patch('/tugas-akhir/tahap/{stage}', [ThesisController::class, 'updateStage'])->name('thesis.stage.update');
    Route::delete('/tugas-akhir/tahap/{stage}', [ThesisController::class, 'destroyStage'])->name('thesis.stage.destroy');
    Route::post('/tugas-akhir/bimbingan', [ThesisController::class, 'storeLog'])->name('thesis.log.store');
    Route::patch('/tugas-akhir/bimbingan/{log}', [ThesisController::class, 'toggleLog'])->name('thesis.log.toggle');
    Route::delete('/tugas-akhir/bimbingan/{log}', [ThesisController::class, 'destroyLog'])->name('thesis.log.destroy');

    Route::view('/toefl', 'toefl.index')->name('toefl');
    Route::view('/career', 'career.index')->name('career');

    Route::get('/toefl', [ToeflController::class, 'index'])->name('toefl');
    Route::put('/toefl/pengaturan', [ToeflController::class, 'saveSetting'])->name('toefl.setting');
    Route::post('/toefl/tryout', [ToeflController::class, 'storeTryout'])->name('toefl.tryout.store');
    Route::delete('/toefl/tryout/{tryout}', [ToeflController::class, 'destroyTryout'])->name('toefl.tryout.destroy');
    Route::post('/toefl/tugas', [ToeflController::class, 'storeTask'])->name('toefl.task.store');
    Route::patch('/toefl/tugas/{task}', [ToeflController::class, 'toggleTask'])->name('toefl.task.toggle');
    Route::delete('/toefl/tugas/{task}', [ToeflController::class, 'destroyTask'])->name('toefl.task.destroy');
    Route::put('/toefl/catatan', [ToeflController::class, 'saveNote'])->name('toefl.note');

    Route::get('/career', [CareerController::class, 'index'])->name('career');
    Route::post('/career/lamaran', [CareerController::class, 'storeApp'])->name('career.app.store');
    Route::patch('/career/lamaran/{application}', [CareerController::class, 'updateApp'])->name('career.app.update');
    Route::delete('/career/lamaran/{application}', [CareerController::class, 'destroyApp'])->name('career.app.destroy');
    Route::post('/career/checklist', [CareerController::class, 'storeItem'])->name('career.item.store');
    Route::patch('/career/checklist/{item}', [CareerController::class, 'toggleItem'])->name('career.item.toggle');
    Route::delete('/career/checklist/{item}', [CareerController::class, 'destroyItem'])->name('career.item.destroy');
});