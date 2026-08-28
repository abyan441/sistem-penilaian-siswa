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
        $tahunAjaranOptions = Kelas::tahunAjaranOptions();
        $tahunAjaran = Kelas::resolveTahunAjaran($request->query('tahun_ajaran'));
        $semester = Nilai::resolveSemester($request->query('semester', 1));
        $siswa = Kelas::siswaUntukRaport($tahunAjaran);

        return view('raport', compact('siswa', 'tahunAjaranOptions', 'tahunAjaran', 'semester'));
    }

    public function data(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        return response()->json([
            'success' => true,
            'data' => Siswa::dataRaport($validated['tahun_ajaran']),
        ]);
    }

    public function preview(Request $request, int $id): View
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'semester' => ['nullable', 'integer', 'in:1,2'],
        ]);

        $semester = Nilai::resolveSemester($validated['semester'] ?? 1);
        $tahunAjaran = $validated['tahun_ajaran'];

        $siswa = Siswa::query()
            ->with(['kelas.waliKelas'])
            ->whereKey($id)
            ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran', $tahunAjaran);
            })
            ->firstOrFail();

        $nilai = Nilai::dataRaportSiswa($siswa->id, $semester);
        $kepalaSekolah = User::kepalaSekolahAktif();
        $kelas = $siswa->kelas;

        return view('raport-preview', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'nilai' => $nilai,
            'semester' => $semester,
            'tahunAjaran' => $tahunAjaran,
            'rataRata' => Nilai::rataRataRaport($nilai),
            'waliKelas' => $kelas?->waliKelas,
            'kepalaSekolah' => $kepalaSekolah,
        ]);
    }
}
