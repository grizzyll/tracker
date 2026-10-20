<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('dashboard');
Route::view('/tugas-akhir', 'tugas-akhir.index')->name('thesis');
Route::view('/toefl', 'toefl.index')->name('toefl');
Route::view('/career', 'career.index')->name('career');