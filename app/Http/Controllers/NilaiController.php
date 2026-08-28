<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataPelajaran;
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
        $user = Auth::user();

        /*
         * Admin hanya memiliki akses baca.
         * Karena admin tidak mempunyai kelas wali,
         * data halaman disiapkan dari seluruh kelas.
         */
        if ($user->role === 'admin') {
            $kelasOptions = Kelas::query()
                ->orderBy('tahun_ajaran', 'desc')
                ->orderBy('nama_kelas', 'asc')
                ->get();

            $tahunAjaranOptions = $kelasOptions
                ->pluck('tahun_ajaran')
                ->unique()
                ->values();

            $kelas = $kelasOptions->first();

            return view('input-nilai', [
                'kelas' => $kelas,
                'kelasOptions' => $kelasOptions,
                'tahunAjaranOptions' => $tahunAjaranOptions,
                'mataPelajaran' => Nilai::semuaMataPelajaran(),
                'readOnly' => true,
                'isAdmin' => true,
            ]);
        }

        return view(
            'input-nilai',
            array_merge(
                Nilai::dataHalaman($user->id),
                [
                    'kelasOptions' => collect(),
                    'tahunAjaranOptions' => collect(),
                    'readOnly' => false,
                    'isAdmin' => false,
                ]
            )
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

            'kelas_id' => [
                'nullable',
                'integer',
                'exists:kelas,id',
            ],

            'tahun_ajaran' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);

        try {
            $user = Auth::user();

            /*
             * ==========================================================
             * ADMIN: HANYA MELIHAT DATA
             * ==========================================================
             * Admin tidak melewati validasi kelas wali guru karena
             * admin memang tidak mempunyai kelas sebagai wali.
             */
            if ($user->role === 'admin') {
                if (empty($validated['kelas_id'])) {
                    throw new InvalidArgumentException(
                        'Silakan pilih kelas terlebih dahulu.'
                    );
                }

                $kelasQuery = Kelas::query()
                    ->where('id', $validated['kelas_id']);

                if (!empty($validated['tahun_ajaran'])) {
                    $kelasQuery->where(
                        'tahun_ajaran',
                        $validated['tahun_ajaran']
                    );
                }

                $kelas = $kelasQuery->first();

                if (!$kelas) {
                    throw new InvalidArgumentException(
                        'Kelas dan tahun ajaran yang dipilih tidak ditemukan.'
                    );
                }

                $mataPelajaran = MataPelajaran::query()
                    ->find($validated['mapel_id']);

                if (!$mataPelajaran) {
                    throw new InvalidArgumentException(
                        'Mata pelajaran tidak ditemukan.'
                    );
                }

                $siswa = Nilai::daftarSiswa($kelas->id);

                $nilai = Nilai::query()
                    ->where('semester', $validated['semester'])
                    ->whereIn(
                        'siswa_id',
                        $siswa->pluck('id')
                    )
                    ->whereHas('guruMapel', function ($query) use ($validated) {
                        $query->where(
                            'mapel_id',
                            $validated['mapel_id']
                        );
                    })
                    ->orderBy('id', 'asc')
                    ->get()
                    ->keyBy('siswa_id');

                $dataSiswa = $siswa
                    ->values()
                    ->map(function ($siswa, $index) use ($nilai) {
                        $nilaiSiswa = $nilai->get($siswa->id);

                        $tugas = $nilaiSiswa
                            ? (float) $nilaiSiswa->nilai_tugas
                            : 0;

                        $uts = $nilaiSiswa
                            ? (float) $nilaiSiswa->nilai_uts
                            : 0;

                        $uas = $nilaiSiswa
                            ? (float) $nilaiSiswa->nilai_uas
                            : 0;

                        $akhir = $nilaiSiswa &&
                            $nilaiSiswa->nilai_akhir !== null
                            ? (float) $nilaiSiswa->nilai_akhir
                            : Nilai::hitungNilaiAkhir(
                                $tugas,
                                $uts,
                                $uas
                            );

                        return [
                            'id' => $siswa->id,
                            'nisn' => $siswa->nisn,
                            'nama_siswa' => $siswa->nama_siswa,
                            'nomor' => $index + 1,
                            'nilai_id' => $nilaiSiswa?->id,
                            'nilai_tugas' => $tugas,
                            'nilai_uts' => $uts,
                            'nilai_uas' => $uas,
                            'nilai_akhir' => $akhir,
                            'predikat' => Nilai::predikat($akhir),
                        ];
                    });

                return response()->json([
                    'success' => true,
                    'readOnly' => true,
                    'data' => [
                        'kelas_id' => $kelas->id,
                        'kelas' => $kelas->nama_kelas,
                        'tahun_ajaran' => $kelas->tahun_ajaran,
                        'semester' => (int) $validated['semester'],
                        'mapel_id' => (int) $validated['mapel_id'],
                        'guru_mapel_id' => null,
                        'siswa' => $dataSiswa->values()->all(),
                    ],
                ]);
            }

            /*
             * ==========================================================
             * GURU: PERILAKU LAMA TETAP DIPERTAHANKAN
             * ==========================================================
             */
            $data = Nilai::dataNilai(
                $user->id,
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
            $user = Auth::user();

            if ($user->role === 'admin') {
                $validated = $request->validate([
                    'kelas_id' => [
                        'required',
                        'integer',
                        'exists:kelas,id',
                    ],
                ]);

                $kelas = Kelas::query()
                    ->find($validated['kelas_id']);

                if (!$kelas) {
                    throw new InvalidArgumentException(
                        'Kelas tidak ditemukan.'
                    );
                }

                $siswa = Nilai::daftarSiswa($kelas->id);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'kelas_id' => $kelas->id,
                        'kelas' => $kelas->nama_kelas,
                        'tahun_ajaran' => $kelas->tahun_ajaran,
                        'siswa' => $siswa,
                    ],
                ]);
            }

            $kelas = Nilai::pastikanKelasWaliGuru(
                $user->id
            );

            $siswa = Nilai::daftarSiswa(
                $kelas->id
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'kelas_id' => $kelas->id,
                    'kelas' => $kelas->nama_kelas,
                    'tahun_ajaran' => $kelas->tahun_ajaran,
                    'siswa' => $siswa,
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
        /*
         * Admin secara eksplisit hanya memiliki akses baca.
         * Tolak penyimpanan meskipun endpoint dipanggil langsung.
         */
        if (Auth::user()?->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' =>
                    'Admin hanya dapat melihat nilai dan tidak dapat mengubah atau menyimpan nilai.',
            ], 403);
        }

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