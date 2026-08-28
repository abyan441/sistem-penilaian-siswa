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

        $semester = Nilai::resolveSemester($request->query('semester', 1));
        $siswa = $tahunAjaran ? Siswa::berdasarkanTahunAjaran($tahunAjaran) : collect();

        return view('raport', compact('siswa','tahunAjaranOptions','tahunAjaran','semester'));
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
        $semester = Nilai::resolveSemester($request->query('semester', 1));
        $siswa = Siswa::query()->with(['kelas.waliKelas'])->findOrFail($id);
        $nilai = Nilai::untukRaport($siswa->id, $semester);
        $kepalaSekolah = User::kepalaSekolahAktif();

        return view('raport-preview', [
            'siswa' => $siswa,
            'kelas' => $siswa->kelas,
            'nilai' => $nilai,
            'semester' => $semester,
            'tahunAjaran' => $siswa->kelas?->tahun_ajaran,
            'rataRata' => Nilai::rataRataRaport($nilai),
            'waliKelas' => $siswa->kelas?->waliKelas,
            'kepalaSekolah' => $kepalaSekolah,
        ]);
    }
}
