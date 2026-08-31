<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class NilaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $readOnly = in_array($user->role, ['admin', 'kepala_sekolah'], true);

        if ($readOnly) {
            return view('input-nilai', array_merge(
                Nilai::dataHalamanAdmin(),
                [
                    'readOnly' => true,
                    'isAdmin' => true,
                ]
            ));
        }

        return view('input-nilai', array_merge(
            Nilai::dataHalaman($user->id),
            [
                'kelasOptions' => collect(),
                'tahunAjaranOptions' => collect(),
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
        ]);

        try {
            $user = Auth::user();
            $readOnly = in_array($user->role, ['admin', 'kepala_sekolah'], true);

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
                    $validated['mapel_id']
                );

            return response()->json([
                'success' => true,
                'readOnly' => $readOnly,
                'data' => $data,
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data nilai.',
                'data' => [],
            ], 500);
        }
    }

    public function siswa(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $readOnly = in_array($user->role, ['admin', 'kepala_sekolah'], true);

            $kelas = $readOnly
                ? Nilai::kelasAdmin($request->validate([
                    'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
                ])['kelas_id'])
                : Nilai::pastikanKelasWaliGuru($user->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'kelas_id' => $kelas->id,
                    'kelas' => $kelas->nama_kelas,
                    'tahun_ajaran' => $kelas->tahun_ajaran,
                    'siswa' => Nilai::daftarSiswa($kelas->id),
                ],
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil siswa.',
                'data' => [],
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (in_array($user?->role, ['admin', 'kepala_sekolah'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini hanya dapat melihat nilai dan tidak dapat mengubah atau menyimpan nilai.',
            ], 403);
        }

        $validated = $request->validate([
            'semester' => ['required', 'in:1,2'],
            'mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'nilai' => ['required', 'array', 'min:1'],
            'nilai.*.siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'nilai.*.nilai_tugas' => ['required', 'numeric', 'between:0,100'],
            'nilai.*.nilai_uts' => ['required', 'numeric', 'between:0,100'],
            'nilai.*.nilai_uas' => ['required', 'numeric', 'between:0,100'],
        ]);

        try {
            Nilai::simpanNilai(
                Auth::id(),
                $validated['semester'],
                $validated['mapel_id'],
                $validated['nilai']
            );

            return response()->json([
                'success' => true,
                'message' => 'Nilai siswa berhasil disimpan.',
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan nilai.',
            ], 500);
        }
    }
}
