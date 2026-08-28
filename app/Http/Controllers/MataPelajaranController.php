<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    /**
     * Menampilkan halaman Mata Pelajaran.
     */
    public function index()
    {
        return view('mapel', MataPelajaran::dataHalaman());
    }

    /**
     * Menyimpan data mata pelajaran baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:5', 'unique:mata_pelajaran,kode_mapel'],
            'nama_pelajaran' => ['required', 'string', 'max:45'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);

        $mapel = MataPelajaran::tambah($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data' => $this->formatData($mapel),
        ], 201);
    }

    /**
     * Memperbarui data mata pelajaran.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $mapel = MataPelajaran::find($id);

        if (!$mapel) {
            return response()->json([
                'success' => false,
                'message' => 'Data mata pelajaran tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'kode_mapel' => [
                'required',
                'string',
                'max:5',
                Rule::unique('mata_pelajaran', 'kode_mapel')->ignore($mapel->id),
            ],
            'nama_pelajaran' => ['required', 'string', 'max:45'],
            'kkm' => ['required', 'integer', 'between:0,100'],
        ]);

        $mapel = MataPelajaran::ubah($id, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui.',
            'data' => $this->formatData($mapel),
        ]);
    }

    /**
     * Menghapus data mata pelajaran.
     */
    public function destroy(int $id): JsonResponse
    {
        if (!MataPelajaran::find($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Data mata pelajaran tidak ditemukan.',
            ], 404);
        }

        MataPelajaran::hapus($id);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus.',
        ]);
    }

    /**
     * Format data untuk response JavaScript.
     */
    private function formatData(MataPelajaran $mapel): array
    {
        return [
            'id' => $mapel->id,
            'kode_mapel' => $mapel->kode_mapel,
            'nama_pelajaran' => $mapel->nama_pelajaran,
            'kkm' => $mapel->kkm,
        ];
    }
}
