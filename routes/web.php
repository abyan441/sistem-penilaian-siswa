<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| Data Guru
|--------------------------------------------------------------------------
*/

Route::get('/guru', [GuruController::class, 'index'])
    ->name('guru');

Route::post('/guru', [GuruController::class, 'store'])
    ->name('guru.store');

Route::put('/guru/{id}', [GuruController::class, 'update'])
    ->name('guru.update');

Route::delete('/guru/{id}', [GuruController::class, 'destroy'])
    ->name('guru.destroy');


/*
|--------------------------------------------------------------------------
| Data Siswa
|--------------------------------------------------------------------------
*/

Route::get('/siswa', [SiswaController::class, 'index'])
    ->name('siswa');

Route::post('/siswa', [SiswaController::class, 'store'])
    ->name('siswa.store');

Route::put('/siswa/{id}', [SiswaController::class, 'update'])
    ->name('siswa.update');

Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])
    ->name('siswa.destroy');


/*
|--------------------------------------------------------------------------
| Data Kelas
|--------------------------------------------------------------------------
*/

Route::get('/kelas', function () {
    return view('kelas');
})->name('kelas');


/*
|--------------------------------------------------------------------------
| Mata Pelajaran
|--------------------------------------------------------------------------
*/

Route::get(
    '/mata-pelajaran',
    [MataPelajaranController::class, 'index']
)->name('mapel');

Route::post(
    '/mata-pelajaran',
    [MataPelajaranController::class, 'store']
)->name('mapel.store');

Route::put(
    '/mata-pelajaran/{id}',
    [MataPelajaranController::class, 'update']
)->name('mapel.update');

Route::delete(
    '/mata-pelajaran/{id}',
    [MataPelajaranController::class, 'destroy']
)->name('mapel.destroy');


/*
|--------------------------------------------------------------------------
| Input Nilai
|--------------------------------------------------------------------------
*/

Route::get('/input-nilai', function () {
    return view('input-nilai');
})->name('input-nilai');


/*
|--------------------------------------------------------------------------
| Raport
|--------------------------------------------------------------------------
*/

Route::get('/raport', function () {
    return view('raport');
})->name('raport');


/*
|--------------------------------------------------------------------------
| Preview / Cetak Raport
|--------------------------------------------------------------------------
*/

Route::get('/raport/{id}/preview', function ($id) {
    return view('raport-preview', [
        'id' => $id,
    ]);
})->name('raport.preview');


/*
|--------------------------------------------------------------------------
| Pengguna
|--------------------------------------------------------------------------
*/

Route::get('/pengguna', function () {
    return view('pengguna');
})->name('pengguna');