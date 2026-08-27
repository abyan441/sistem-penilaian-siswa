<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class NilaiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN INPUT NILAI
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $guru = Auth::user();

        return view(
            'input-nilai',
            Nilai::dataHalaman($guru->id)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATA NILAI
    |--------------------------------------------------------------------------
    */

    public function data(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'semester' => [
                'required',
                'in:1,2',
            ],

            'mapel_id' => [
                'required',
                'integer',
                'exists:mata_pelajaran,id',
            ],
        ]);

        try {

            $guru = Auth::user();

            $data = Nilai::dataNilai(
                $guru->id,
                $validated['semester'],
                $validated['mapel_id']
            );

            return response()->json([
                'success' => true,
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
                'message' =>
                    'Terjadi kesalahan saat mengambil data nilai.',
                'data' => [],
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DAFTAR SISWA
    |--------------------------------------------------------------------------
    */

    public function siswa(
        Request $request
    ): JsonResponse {
        try {

            $guru = Auth::user();

            $kelas = Nilai::pastikanKelasWaliGuru(
                $guru->id
            );

            $siswa = Nilai::daftarSiswa(
                $kelas->id
            );

            return response()->json([
                'success' => true,

                'data' => [
                    'kelas_id' =>
                        $kelas->id,

                    'kelas' =>
                        $kelas->nama_kelas,

                    'tahun_ajaran' =>
                        $kelas->tahun_ajaran,

                    'siswa' =>
                        $siswa,
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
                'message' =>
                    'Terjadi kesalahan saat mengambil siswa.',
                'data' => [],
            ], 500);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN NILAI
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'semester' => [
                'required',
                'in:1,2',
            ],

            'mapel_id' => [
                'required',
                'integer',
                'exists:mata_pelajaran,id',
            ],

            'nilai' => [
                'required',
                'array',
                'min:1',
            ],

            'nilai.*.siswa_id' => [
                'required',
                'integer',
                'exists:siswa,id',
            ],

            'nilai.*.nilai_tugas' => [
                'required',
                'numeric',
                'between:0,100',
            ],

            'nilai.*.nilai_uts' => [
                'required',
                'numeric',
                'between:0,100',
            ],

            'nilai.*.nilai_uas' => [
                'required',
                'numeric',
                'between:0,100',
            ],
        ]);

        try {

            $guru = Auth::user();

            Nilai::simpanNilai(
                $guru->id,
                $validated['semester'],
                $validated['mapel_id'],
                $validated['nilai']
            );

            return response()->json([
                'success' => true,
                'message' =>
                    'Nilai siswa berhasil disimpan.',
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
                'message' =>
                    'Terjadi kesalahan saat menyimpan nilai.',
            ], 500);
        }
    }
}