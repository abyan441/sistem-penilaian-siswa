<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class NilaiController extends ApiController
{
    public function index()
    {
        $user = Auth::user();
        $readOnly = Nilai::readOnlyUntukRole($user?->role);

        if ($readOnly) {
            return view('input-nilai', array_merge(
                Nilai::dataHalamanAdmin(),
                ['readOnly' => true, 'isAdmin' => true]
            ));
        }

        return view('input-nilai', array_merge(
            Nilai::dataHalaman($user->id),
            [
                'readOnly' => false,
                'isAdmin' => false,
            ]
        ));
    }

    public function data(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'semester' => ['required', 'in:1,2'],
            'mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
            'tahun_ajaran' => ['nullable', 'string', 'max:20'],
        ], $this->validationMessages());

        try {
            $user = Auth::user();
            $readOnly = Nilai::readOnlyUntukRole($user?->role);

            $data = $readOnly
                ? Nilai::dataNilaiAdmin(
                    $validated['kelas_id'] ?? null,
                    $validated['tahun_ajaran'] ?? null,
                    $validated['semester'],
                    $validated['mapel_id']
                )
                : Nilai::dataNilai(
                    $user->id,
                    $validated['semester'],
                    $validated['mapel_id'],
                    $validated['kelas_id'] ?? null,
                    $validated['tahun_ajaran'] ?? null
                );

            return $this->successResponse($data, '', 200, ['readOnly' => $readOnly]);
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return $this->errorResponse('Terjadi kesalahan saat mengambil data nilai.', 500);
        }
    }

    public function siswa(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $readOnly = Nilai::readOnlyUntukRole($user?->role);

            if ($readOnly) {
                $validated = $request->validate([
                    'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
                ], $this->validationMessages());

                $kelas = Nilai::kelasAdmin($validated['kelas_id']);
                $siswa = Nilai::daftarSiswa($kelas->id);
            } else {
                $kelas = $request->filled('kelas_id')
                    ? Nilai::kelasAdmin((int) $request->kelas_id)
                    : null;
                $siswa = $kelas ? Nilai::daftarSiswa($kelas->id) : Nilai::semuaSiswa();
            }

            return $this->successResponse([
                'kelas_id' => $kelas?->id,
                'kelas' => $kelas?->nama_kelas ?? 'Semua Kelas',
                'tahun_ajaran' => $kelas?->tahun_ajaran ?? 'Semua Tahun Ajaran',
                'siswa' => $siswa,
            ]);
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return $this->errorResponse('Terjadi kesalahan saat mengambil siswa.', 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            Nilai::pastikanDapatMengelolaNilai($user?->role);
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 403);
        }

        $validated = $request->validate([
            'semester' => ['required', 'in:1,2'],
            'mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'nilai' => ['required', 'array', 'min:1'],
            'nilai.*.siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'nilai.*.nilai_tugas' => ['required', 'numeric', 'between:0,100'],
            'nilai.*.nilai_uts' => ['required', 'numeric', 'between:0,100'],
            'nilai.*.nilai_uas' => ['required', 'numeric', 'between:0,100'],
        ], $this->validationMessages());

        try {
            Nilai::simpanNilai(
                $user->id,
                $validated['semester'],
                $validated['mapel_id'],
                $validated['nilai']
            );

            return $this->successResponse(null, 'Nilai siswa berhasil disimpan.');
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (\Throwable $e) {
            report($e);
            return $this->errorResponse('Terjadi kesalahan saat menyimpan nilai.', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            Nilai::hapus($id);

            return $this->successResponse(null, 'Nilai siswa berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            return $this->notFoundResponse('Data nilai');
        } catch (\Throwable $exception) {
            report($exception);
            return $this->errorResponse('Terjadi kesalahan saat menghapus nilai.', 500);
        }
    }

    private function validationMessages(): array
    {
        return [
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester tidak valid. Pilih semester 1 atau 2.',
            'mapel_id.required' => 'Mata pelajaran wajib dipilih.',
            'mapel_id.integer' => 'Data mata pelajaran tidak valid.',
            'mapel_id.exists' => 'Mata pelajaran yang dipilih tidak ditemukan.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.integer' => 'Data kelas tidak valid.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
            'tahun_ajaran.string' => 'Tahun ajaran harus berupa teks.',
            'tahun_ajaran.max' => 'Tahun ajaran maksimal 20 karakter.',
            'nilai.required' => 'Data nilai wajib diisi.',
            'nilai.array' => 'Format data nilai tidak valid.',
            'nilai.min' => 'Minimal satu data nilai harus diisi.',
            'nilai.*.siswa_id.required' => 'Data siswa wajib dipilih.',
            'nilai.*.siswa_id.integer' => 'ID siswa tidak valid.',
            'nilai.*.siswa_id.exists' => 'Siswa yang dipilih tidak ditemukan.',
            'nilai.*.nilai_tugas.required' => 'Nilai tugas wajib diisi.',
            'nilai.*.nilai_tugas.numeric' => 'Nilai tugas harus berupa angka.',
            'nilai.*.nilai_tugas.between' => 'Nilai tugas harus berada pada rentang 0 sampai 100.',
            'nilai.*.nilai_uts.required' => 'Nilai UTS wajib diisi.',
            'nilai.*.nilai_uts.numeric' => 'Nilai UTS harus berupa angka.',
            'nilai.*.nilai_uts.between' => 'Nilai UTS harus berada pada rentang 0 sampai 100.',
            'nilai.*.nilai_uas.required' => 'Nilai UAS wajib diisi.',
            'nilai.*.nilai_uas.numeric' => 'Nilai UAS harus berupa angka.',
            'nilai.*.nilai_uas.between' => 'Nilai UAS harus berada pada rentang 0 sampai 100.',
        ];
    }
}
