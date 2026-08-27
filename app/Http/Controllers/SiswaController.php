<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Menampilkan halaman data siswa.
     */
    public function index()
    {
        return view('siswa', Siswa::dataHalaman());
    }


    /**
     * Menambahkan data siswa.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
            'kelas_id',
        ]);

        return response()->json(
            Siswa::prosesTambah($data)
        );
    }


    /**
     * Mengubah data siswa.
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
            'kelas_id',
        ]);

        return response()->json(
            Siswa::prosesUbah($id, $data)
        );
    }


    /**
     * Menghapus data siswa.
     */
    public function destroy($id)
    {
        return response()->json(
            Siswa::prosesHapus($id)
        );
    }
}