<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| PUBLIC / GUEST
|--------------------------------------------------------------------------
| Halaman berikut dapat diakses tanpa login.
| Guest hanya dapat melihat data.
|--------------------------------------------------------------------------
*/

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
| Data Guru - PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/guru', [GuruController::class, 'index'])
    ->name('guru');


/*
|--------------------------------------------------------------------------
| Data Siswa - PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/siswa', [SiswaController::class, 'index'])
    ->name('siswa');


/*
|--------------------------------------------------------------------------
| Data Kelas - PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/kelas', [KelasController::class, 'index'])
    ->name('kelas');

Route::get('/kelas/{id}/detail', [KelasController::class, 'detail'])
    ->name('kelas.detail');


/*
|--------------------------------------------------------------------------
| Mata Pelajaran - PUBLIC
|--------------------------------------------------------------------------
*/

Route::get(
    '/mata-pelajaran',
    [MataPelajaranController::class, 'index']
)->name('mapel');


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');


/*
|--------------------------------------------------------------------------
| GURU + ADMIN + KEPALA SEKOLAH
|--------------------------------------------------------------------------
| Semua user yang sudah login dan memiliki salah satu role berikut
| dapat melakukan CRUD data utama.
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:guru,admin,kepala_sekolah',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Data Guru - CRUD
    |--------------------------------------------------------------------------
    */

    Route::post('/guru', [GuruController::class, 'store'])
        ->name('guru.store');

    Route::put('/guru/{id}', [GuruController::class, 'update'])
        ->name('guru.update');

    Route::delete('/guru/{id}', [GuruController::class, 'destroy'])
        ->name('guru.destroy');


    /*
    |--------------------------------------------------------------------------
    | Data Siswa - CRUD
    |--------------------------------------------------------------------------
    */

    Route::post('/siswa', [SiswaController::class, 'store'])
        ->name('siswa.store');

    Route::put('/siswa/{id}', [SiswaController::class, 'update'])
        ->name('siswa.update');

    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])
        ->name('siswa.destroy');


    /*
    |--------------------------------------------------------------------------
    | Data Kelas - CRUD
    |--------------------------------------------------------------------------
    */

    Route::post('/kelas', [KelasController::class, 'store'])
        ->name('kelas.store');

    Route::put('/kelas/{id}', [KelasController::class, 'update'])
        ->name('kelas.update');

    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])
        ->name('kelas.destroy');


    /*
    |--------------------------------------------------------------------------
    | Mata Pelajaran - CRUD
    |--------------------------------------------------------------------------
    */

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
    | INPUT NILAI
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/input-nilai',
        [NilaiController::class, 'index']
    )->name('input-nilai');

    Route::get(
        '/input-nilai/data',
        [NilaiController::class, 'data']
    )->name('input-nilai.data');

    Route::get(
        '/input-nilai/siswa',
        [NilaiController::class, 'siswa']
    )->name('input-nilai.siswa');

    Route::post(
        '/input-nilai',
        [NilaiController::class, 'store']
    )->name('input-nilai.store');


    /*
    |--------------------------------------------------------------------------
    | RAPORT
    |--------------------------------------------------------------------------
    */

    Route::get('/raport', function () {
        return view('raport');
    })->name('raport');

    Route::get('/raport/{id}/preview', function ($id) {
        return view('raport-preview', [
            'id' => $id,
        ]);
    })->name('raport.preview');


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});


/*
|--------------------------------------------------------------------------
| ADMIN + KEPALA SEKOLAH
|--------------------------------------------------------------------------
| Halaman Pengguna hanya dapat diakses oleh Admin dan Kepala Sekolah.
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,kepala_sekolah',
])->group(function () {

    Route::get('/pengguna', function () {
        return view('pengguna');
    })->name('pengguna');
});
