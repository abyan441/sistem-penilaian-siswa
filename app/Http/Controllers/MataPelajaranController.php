<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN MATA PELAJARAN
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan halaman Mata Pelajaran.
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
     * Menyimpan data mata pelajaran baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_mapel' => [
                'required',
                'string',
                'max:5',
                'unique:mata_pelajaran,kode_mapel',
            ],

            'nama_pelajaran' => [
                'required',
                'string',
                'max:45',
            ],

            'kkm' => [
                'required',
                'integer',
                'between:0,100',
            ],
        ]);

        $mapel = MataPelajaran::create([
            'kode_mapel' => strtoupper(
                trim($validated['kode_mapel'])
            ),

            'nama_pelajaran' => trim(
                $validated['nama_pelajaran']
            ),

            'kkm' => $validated['kkm'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data' => $this->formatData($mapel),
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | UBAH
    |--------------------------------------------------------------------------
    */

    /**
     * Memperbarui data mata pelajaran.
     */
    public function update(
        Request $request,
        $id
    ): JsonResponse {
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
                Rule::unique('mata_pelajaran', 'kode_mapel')
                    ->ignore($mapel->id),
            ],

            'nama_pelajaran' => [
                'required',
                'string',
                'max:45',
            ],

            'kkm' => [
                'required',
                'integer',
                'between:0,100',
            ],
        ]);

        $mapel->update([
            'kode_mapel' => strtoupper(
                trim($validated['kode_mapel'])
            ),

            'nama_pelajaran' => trim(
                $validated['nama_pelajaran']
            ),

            'kkm' => $validated['kkm'],
        ]);

        $mapel->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui.',
            'data' => $this->formatData($mapel),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    /**
     * Menghapus data mata pelajaran.
     */
    public function destroy($id): JsonResponse
    {
        $mapel = MataPelajaran::find($id);

        if (!$mapel) {
            return response()->json([
                'success' => false,
                'message' => 'Data mata pelajaran tidak ditemukan.',
            ], 404);
        }

        $mapel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DATA
    |--------------------------------------------------------------------------
    */

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