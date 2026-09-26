<?php

use App\Http\Controllers\Siswa\AttemptController;
use App\Http\Controllers\Siswa\JoinController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/ui-kit', 'ui-kit')->name('ui-kit');

// Siswa — tanpa akun, cukup kode kelas + nama panggilan.
Route::get('/join', [JoinController::class, 'create'])->name('siswa.join');
Route::post('/join', [JoinController::class, 'store'])->name('siswa.join.store');
Route::get('/kerjakan/{attempt}', [AttemptController::class, 'show'])->name('siswa.kerjakan');
Route::post('/kerjakan/{attempt}', [AttemptController::class, 'answer'])->name('siswa.kerjakan.answer');
Route::get('/hasil/{attempt}', [AttemptController::class, 'result'])->name('siswa.hasil');
