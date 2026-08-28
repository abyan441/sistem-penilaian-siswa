<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RaportController extends Controller
{
    /**
     * Halaman utama raport.
     * Data siswa, kelas dan tahun ajaran berasal dari database.
     */
    public function index(Request $request): View
    {
        $tahunAjaranOptions = Kelas::query()
            ->select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        $tahunAjaran = $request->query('tahun_ajaran');

        if (!$tahunAjaran || !$tahunAjaranOptions->contains($tahunAjaran)) {
            $tahunAjaran = $tahunAjaranOptions->first();
        }

        $semester = (int) $request->query('semester', 1);
        if (!in_array($semester, [1, 2], true)) {
            $semester = 1;
        }

        $siswa = collect();

        if ($tahunAjaran) {
            $siswa = Siswa::query()
                ->with('kelas')
                ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                    $query->where('tahun_ajaran', $tahunAjaran);
                })
                ->orderBy('nama_siswa')
                ->orderBy('nisn')
                ->get();
        }

        return view('raport', [
            'siswa' => $siswa,
            'tahunAjaranOptions' => $tahunAjaranOptions,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $semester,
        ]);
    }

    /**
     * Endpoint AJAX untuk mengganti daftar siswa berdasarkan tahun ajaran.
     */
    public function data(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        $siswa = Siswa::query()
            ->with('kelas')
            ->whereHas('kelas', function ($query) use ($validated) {
                $query->where('tahun_ajaran', $validated['tahun_ajaran']);
            })
            ->orderBy('nama_siswa')
            ->orderBy('nisn')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nisn' => $item->nisn,
                    'nama_siswa' => $item->nama_siswa,
                    'kelas_id' => $item->kelas_id,
                    'nama_kelas' => $item->kelas?->nama_kelas,
                    'tahun_ajaran' => $item->kelas?->tahun_ajaran,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $siswa->values()->all(),
        ]);
    }
}
