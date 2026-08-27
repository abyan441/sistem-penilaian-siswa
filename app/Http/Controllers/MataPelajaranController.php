<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN MATA PELAJARAN
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman Mata Pelajaran.
     *
     * Controller hanya mengambil data dari Model
     * dan meneruskannya ke Blade.
     */
    public function index()
    {
        return view(
            'mapel',
            MataPelajaran::dataHalaman()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    /**
     * Menambahkan Mata Pelajaran.
     *
     * Controller hanya meneruskan Request
     * kepada Model.
     */
    public function store(Request $request)
    {
        return response()->json(
            MataPelajaran::prosesTambah($request),
            201
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UBAH
    |--------------------------------------------------------------------------
    */

    /**
     * Memperbarui Mata Pelajaran.
     *
     * Controller hanya meneruskan Request dan ID
     * kepada Model.
     */
    public function update(Request $request, $id)
    {
        return response()->json(
            MataPelajaran::prosesUbah(
                $id,
                $request
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    /**
     * Menghapus Mata Pelajaran.
     *
     * Controller hanya meneruskan ID
     * kepada Model.
     */
    public function destroy($id)
    {
        return response()->json(
            MataPelajaran::prosesHapus($id)
        );
    }
}