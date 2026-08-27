<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;

class KelasController extends Controller
{
    /**
     * Menampilkan halaman data kelas.
     */
    public function index(): View
    {
        $kelas = Kelas::semuaKelas();
        $ringkasan = Kelas::ringkasan();
        $guru = Kelas::semuaWaliKelas();

        return view('kelas', compact(
            'kelas',
            'ringkasan',
            'guru'
        ));
    }

    /**
     * Menyimpan data kelas baru.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $kelas = Kelas::tambahKelas(
                $request->only([
                    'nama_kelas',
                    'tahun_ajaran',
                    'wali_kelas_id',
                ])
            );

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil ditambahkan.',
                'data' => $this->formatKelas($kelas),
            ], 201);

        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);

        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan data kelas.',
            ], 500);
        }
    }

    /**
     * Mengubah data kelas.
     */
    public function update(
        Request $request,
        $id
    ): JsonResponse {
        try {
            $kelas = Kelas::ubahKelas(
                $id,
                $request->only([
                    'nama_kelas',
                    'tahun_ajaran',
                    'wali_kelas_id',
                ])
            );

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil diperbarui.',
                'data' => $this->formatKelas($kelas),
            ]);

        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);

        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data kelas.',
            ], 500);
        }
    }

    /**
     * Menghapus data kelas.
     */
    public function destroy($id): JsonResponse
    {
        try {
            Kelas::hapusKelas($id);

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil dihapus.',
            ]);

        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);

        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data kelas.',
            ], 500);
        }
    }

    /**
     * Menampilkan detail kelas dan daftar siswa.
     */
    public function detail($id): JsonResponse
    {
        try {
            $kelas = Kelas::detailKelas($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $kelas->id,
                    'nama_kelas' => $kelas->nama_kelas,
                    'tahun_ajaran' => $kelas->tahun_ajaran,
                    'wali_kelas_id' => $kelas->wali_kelas_id,
                    'wali_kelas' => $kelas->waliKelas?->nama_lengkap ?? '-',
                    'jumlah_siswa' => $kelas->siswa_count,
                    'siswa' => $kelas->siswa->map(function ($siswa) {
                        return [
                            'id' => $siswa->id,
                            'nisn' => $siswa->nisn,
                            'nama_siswa' => $siswa->nama_siswa,
                            'jenis_kelamin' => $siswa->jenis_kelamin,
                        ];
                    })->values(),
                ],
            ]);

        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail kelas dari database.',
            ], 500);
        }
    }

    /**
     * Membentuk data kelas yang dikirim ke JavaScript.
     *
     * Method ini hanya bertugas membentuk response API,
     * bukan menjalankan business logic.
     */
    private function formatKelas(Kelas $kelas): array
    {
        return [
            'id' => $kelas->id,
            'nama_kelas' => $kelas->nama_kelas,
            'tahun_ajaran' => $kelas->tahun_ajaran,
            'wali_kelas_id' => $kelas->wali_kelas_id,
            'wali_kelas' => $kelas->waliKelas?->nama_lengkap ?? '-',
            'jumlah_siswa' => (int) ($kelas->siswa_count ?? 0),
        ];
    }
}