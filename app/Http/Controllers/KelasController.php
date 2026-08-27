<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Menampilkan halaman data kelas.
     */
    public function index()
    {
        return view('kelas', Kelas::dataHalaman());
    }

    /**
     * Menambahkan data kelas.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'nama_kelas',
            'tahun_ajaran',
            'wali_kelas_id',
        ]);

        return response()->json(
            Kelas::prosesTambah($data)
        );
    }

    /**
     * Mengubah data kelas.
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'nama_kelas',
            'tahun_ajaran',
            'wali_kelas_id',
        ]);

        return response()->json(
            Kelas::prosesUbah($id, $data)
        );
    }

    /**
     * Menghapus data kelas.
     */
    public function destroy($id)
    {
        return response()->json(
            Kelas::prosesHapus($id)
        );
    }

    /**
     * Menampilkan detail kelas beserta siswanya.
     */
    public function detail($id)
    {
        return response()->json(
            Kelas::detail($id)
        );
    }
}
