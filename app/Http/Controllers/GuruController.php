<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * =====================================================
     * HALAMAN DATA GURU
     * =====================================================
     *
     * Controller hanya mengambil data dari Model
     * kemudian mengirimkannya ke Blade.
     */
    public function index()
    {
        $data = GuruMapel::dataHalaman();

        return view('guru', $data);
    }


    /**
     * =====================================================
     * TAMBAH GURU MAPEL
     * =====================================================
     *
     * Controller hanya menerima request dan memanggil Model.
     */
    public function store(Request $request)
    {
        $guruMapel = GuruMapel::tambah([
            'guru_id' => $request->input('guru_id'),
            'mapel_id' => $request->input('mapel_id'),
        ]);

        /*
         * Ambil kembali data lengkap beserta relasi
         * supaya JavaScript mendapatkan data terbaru
         * dari database.
         */
        $guruMapel->load([
            'guru',
            'mataPelajaran',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan.',
            'data' => [
                'id' => $guruMapel->id,
                'guru_id' => $guruMapel->guru_id,
                'mapel_id' => $guruMapel->mapel_id,
                'nip' => $guruMapel->guru->nip ?? '-',
                'nama' => $guruMapel->guru->nama_lengkap ?? '-',
                'mapel' => $guruMapel->mataPelajaran->nama_pelajaran ?? '-',
            ],
        ]);
    }


    /**
     * =====================================================
     * UPDATE GURU MAPEL
     * =====================================================
     *
     * Controller hanya meneruskan data ke Model.
     */
    public function update(Request $request, $id)
    {
        $guruMapel = GuruMapel::ubah($id, [
            'guru_id' => $request->input('guru_id'),
            'mapel_id' => $request->input('mapel_id'),
        ]);

        /*
         * Ambil ulang relasi setelah perubahan database.
         */
        $guruMapel->load([
            'guru',
            'mataPelajaran',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui.',
            'data' => [
                'id' => $guruMapel->id,
                'guru_id' => $guruMapel->guru_id,
                'mapel_id' => $guruMapel->mapel_id,
                'nip' => $guruMapel->guru->nip ?? '-',
                'nama' => $guruMapel->guru->nama_lengkap ?? '-',
                'mapel' => $guruMapel->mataPelajaran->nama_pelajaran ?? '-',
            ],
        ]);
    }


    /**
     * =====================================================
     * HAPUS GURU MAPEL
     * =====================================================
     *
     * Controller hanya memanggil Model.
     */
    public function destroy($id)
    {
        GuruMapel::hapus($id);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus.',
        ]);
    }
}