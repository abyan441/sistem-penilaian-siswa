<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuruController extends ApiController
{
    public function index()
    {
        return view('guru', GuruMapel::dataHalaman());
    }

    public function store(Request $request): JsonResponse
    {
        $guruMapel = GuruMapel::tambah($request->only(['guru_id', 'mapel_id']));

        return $this->successResponse(
            $this->formatGuruMapel($guruMapel),
            'Data guru berhasil ditambahkan.',
            201
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $guruMapel = GuruMapel::ubah($id, $request->only(['guru_id', 'mapel_id']));

        return $this->successResponse(
            $this->formatGuruMapel($guruMapel),
            'Data guru berhasil diperbarui.'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            GuruMapel::hapus($id);
            return $this->successResponse(
                null,
                'Data guru berhasil dihapus.'
            );
        } catch (ValidationException $exception) {
            return $this->errorResponse($exception->getMessage());
        } catch (\RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    private function formatGuruMapel(GuruMapel $guruMapel): array
    {
        $guruMapel->loadMissing(['guru', 'mataPelajaran']);

        return [
            'id' => $guruMapel->id,
            'guru_id' => $guruMapel->guru_id,
            'mapel_id' => $guruMapel->mapel_id,
            'nip' => $guruMapel->guru->nip ?? '-',
            'nama' => $guruMapel->guru->nama_lengkap ?? '-',
            'mapel' => $guruMapel->mataPelajaran->nama_pelajaran ?? '-',
        ];
    }
}
