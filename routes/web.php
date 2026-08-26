<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MataPelajaranController;


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

Route::get('/guru', function () {
    return view('guru');
})->name('guru');


/*
|--------------------------------------------------------------------------
| Data Siswa
|--------------------------------------------------------------------------
*/

Route::get('/siswa', function () {
    return view('siswa');
})->name('siswa');


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