<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Menampilkan halaman Mata Pelajaran.
     */
    public function index()
    {
        $data = MataPelajaran::dataHalaman();

        return view('mapel', $data);
    }


    /**
     * Menambahkan Mata Pelajaran.
     */
    public function store(Request $request)
    {
        $mapel = MataPelajaran::tambah(
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data' => $mapel,
        ], 201);
    }


    /**
     * Memperbarui Mata Pelajaran.
     */
    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::ubah(
            $id,
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui.',
            'data' => $mapel,
        ]);
    }


    /**
     * Menghapus Mata Pelajaran.
     */
    public function destroy($id)
    {
        MataPelajaran::hapus($id);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus.',
        ]);
    }
}