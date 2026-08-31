<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuruController extends Controller
{
    /**
     * Menampilkan halaman data guru.
     */
    public function index()
    {
        return view('guru', GuruMapel::dataHalaman());
    }

    /**
     * Aturan validasi request guru mata pelajaran.
     */
    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'guru_id' => ['required', 'integer', 'exists:users,id'],
            'mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
        ], [
            'guru_id.required' => 'Guru wajib dipilih.',
            'guru_id.integer' => 'Data guru tidak valid.',
            'guru_id.exists' => 'Guru yang dipilih tidak ditemukan.',
            'mapel_id.required' => 'Mata pelajaran wajib dipilih.',
            'mapel_id.integer' => 'Data mata pelajaran tidak valid.',
            'mapel_id.exists' => 'Mata pelajaran yang dipilih tidak ditemukan.',
        ]);
    }

    /**
     * Menyimpan guru mata pelajaran baru.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $this->validateRequest($request);

        $guruMapel = GuruMapel::tambah($data);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan.',
            'data' => $this->formatGuruMapel($guruMapel),
        ]);
    }

    /**
     * Memperbarui guru mata pelajaran.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $this->validateRequest($request);

        $guruMapel = GuruMapel::ubah($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui.',
            'data' => $this->formatGuruMapel($guruMapel),
        ]);
    }

    /**
     * Menghapus guru mata pelajaran.
     */
    public function destroy(int $id): JsonResponse
    {
        GuruMapel::hapus($id);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus.',
        ]);
    }

    /**
     * Menyiapkan data guru mata pelajaran untuk response JSON.
     */
    private function formatGuruMapel(GuruMapel $guruMapel): array
    {
        $guruMapel->loadMissing([
            'guru',
            'mataPelajaran',
        ]);

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
