<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RaportController extends Controller
{
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
                ->whereHas('kelas', fn ($query) => $query->where('tahun_ajaran', $tahunAjaran))
                ->orderBy('nama_siswa')
                ->orderBy('nisn')
                ->get();
        }

        return view('raport', compact(
            'siswa',
            'tahunAjaranOptions',
            'tahunAjaran',
            'semester'
        ));
    }

    public function data(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        $siswa = Siswa::query()
            ->with('kelas')
            ->whereHas('kelas', fn ($query) => $query->where('tahun_ajaran', $validated['tahun_ajaran']))
            ->orderBy('nama_siswa')
            ->orderBy('nisn')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'nisn' => $item->nisn,
                'nama_siswa' => $item->nama_siswa,
                'kelas_id' => $item->kelas_id,
                'nama_kelas' => $item->kelas?->nama_kelas,
                'tahun_ajaran' => $item->kelas?->tahun_ajaran,
            ]);

        return response()->json([
            'success' => true,
            'data' => $siswa->values()->all(),
        ]);
    }

    /**
     * Menampilkan satu raport berdasarkan siswa dan semester.
     * Semua nilai diambil dari database melalui siswa -> nilai -> guru_mapel -> mata_pelajaran.
     */
    public function preview(Request $request, int $id): View
    {
        $semester = (int) $request->query('semester', 1);
        if (!in_array($semester, [1, 2], true)) {
            $semester = 1;
        }

        $siswa = Siswa::query()
            ->with(['kelas.waliKelas'])
            ->findOrFail($id);

        $nilai = Nilai::query()
            ->with('guruMapel.mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->get()
            ->sortBy(fn ($item) => mb_strtolower($item->guruMapel?->mataPelajaran?->nama_pelajaran ?? ''))
            ->values();

        $rataRata = $nilai->isNotEmpty()
            ? round($nilai->avg(fn ($item) => (float) $item->nilai_akhir), 2)
            : 0;

        $kepalaSekolah = User::query()
            ->where('role', 'kepala_sekolah')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->first();

        return view('raport-preview', [
            'siswa' => $siswa,
            'kelas' => $siswa->kelas,
            'nilai' => $nilai,
            'semester' => $semester,
            'tahunAjaran' => $siswa->kelas?->tahun_ajaran,
            'rataRata' => $rataRata,
            'waliKelas' => $siswa->kelas?->waliKelas,
            'kepalaSekolah' => $kepalaSekolah,
        ]);
    }
}
