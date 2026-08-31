<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

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
        ], [
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.string' => 'Kode mata pelajaran harus berupa teks.',
            'kode_mapel.max' => 'Kode mata pelajaran maksimal 5 karakter.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
            'nama_pelajaran.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_pelajaran.string' => 'Nama mata pelajaran harus berupa teks.',
            'nama_pelajaran.max' => 'Nama mata pelajaran maksimal 45 karakter.',
            'kkm.required' => 'KKM wajib diisi.',
            'kkm.integer' => 'KKM harus berupa angka.',
            'kkm.between' => 'KKM harus berada di antara 0 sampai 100.',
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
        ], [
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.string' => 'Kode mata pelajaran harus berupa teks.',
            'kode_mapel.max' => 'Kode mata pelajaran maksimal 5 karakter.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
            'nama_pelajaran.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_pelajaran.string' => 'Nama mata pelajaran harus berupa teks.',
            'nama_pelajaran.max' => 'Nama mata pelajaran maksimal 45 karakter.',
            'kkm.required' => 'KKM wajib diisi.',
            'kkm.integer' => 'KKM harus berupa angka.',
            'kkm.between' => 'KKM harus berada di antara 0 sampai 100.',
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

        try {
            MataPelajaran::hapus($id);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

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
