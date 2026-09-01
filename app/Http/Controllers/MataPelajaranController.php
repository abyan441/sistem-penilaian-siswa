<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class MataPelajaranController extends ApiController
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
        $mapel = MataPelajaran::tambah($request->only([
            'kode_mapel',
            'nama_pelajaran',
            'kkm',
        ]));

        return $this->successResponse(
            $this->formatData($mapel),
            'Mata pelajaran berhasil ditambahkan.',
            201
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $mapel = MataPelajaran::ubah($id, $request->only([
            'kode_mapel',
            'nama_pelajaran',
            'kkm',
        ]));

        return $this->successResponse(
            $this->formatData($mapel),
            'Mata pelajaran berhasil diperbarui.'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            MataPelajaran::hapus($id);
            return $this->successResponse(
                null,
                'Mata pelajaran berhasil dihapus.'
            );
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Data mata pelajaran');
        } catch (RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage());
        }
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
