<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\RaportController;

/*
|--------------------------------------------------------------------------
| PUBLIC / GUEST
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/guru', [GuruController::class, 'index'])->name('guru');
Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa');
Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
Route::get('/kelas/{id}/detail', [KelasController::class, 'detail'])->name('kelas.detail');
Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mapel');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

/*
|--------------------------------------------------------------------------
| GURU + ADMIN + KEPALA SEKOLAH
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:guru,admin,kepala_sekolah',
])->group(function () {

    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
    Route::put('/guru/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('guru.destroy');

    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    Route::post('/mata-pelajaran', [MataPelajaranController::class, 'store'])->name('mapel.store');
    Route::put('/mata-pelajaran/{id}', [MataPelajaranController::class, 'update'])->name('mapel.update');
    Route::delete('/mata-pelajaran/{id}', [MataPelajaranController::class, 'destroy'])->name('mapel.destroy');

    Route::get('/input-nilai', [NilaiController::class, 'index'])->name('input-nilai');
    Route::get('/input-nilai/data', [NilaiController::class, 'data'])->name('input-nilai.data');
    Route::get('/input-nilai/siswa', [NilaiController::class, 'siswa'])->name('input-nilai.siswa');
    Route::post('/input-nilai', [NilaiController::class, 'store'])->name('input-nilai.store');

    /*
    |--------------------------------------------------------------------------
    | RAPORT
    |--------------------------------------------------------------------------
    | Data halaman raport diambil langsung dari database.
    |--------------------------------------------------------------------------
    */

    Route::get('/raport', [RaportController::class, 'index'])->name('raport');
    Route::get('/raport/data', [RaportController::class, 'data'])->name('raport.data');

    Route::get('/raport/{id}/preview', function ($id) {
        return view('raport-preview', ['id' => $id]);
    })->name('raport.preview');

    /*
    |--------------------------------------------------------------------------
    | PENGATURAN AKUN
    |--------------------------------------------------------------------------
    | Semua pengguna yang sudah login dapat mengubah email dan password
    | miliknya sendiri.
    |--------------------------------------------------------------------------
    */

    Route::put('/akun/email', [AkunController::class, 'updateEmail'])
        ->name('akun.email.update');

    Route::put('/akun/password', [AkunController::class, 'updatePassword'])
        ->name('akun.password.update');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN + KEPALA SEKOLAH
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,kepala_sekolah',
])->group(function () {

    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{user}', [PenggunaController::class, 'show'])->name('pengguna.show');
    Route::put('/pengguna/{user}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::patch('/pengguna/{user}/status', [PenggunaController::class, 'updateStatus'])->name('pengguna.status');
    Route::delete('/pengguna/{user}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
});
