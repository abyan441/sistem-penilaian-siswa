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
        $siswaTerpilih = $request->query('siswa');

        if ($siswaTerpilih !== null && $siswaTerpilih !== '') {
            $siswaTerpilih = (int) $siswaTerpilih;

            if (!$siswa->contains('id', $siswaTerpilih)) {
                $siswaTerpilih = null;
            }
        } else {
            $siswaTerpilih = null;
        }

        return view('raport', compact(
            'siswa',
            'tahunAjaranOptions',
            'tahunAjaran',
            'semester',
            'siswaTerpilih'
        ));
    }

    public function data(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran.string' => 'Tahun ajaran harus berupa teks.',
            'tahun_ajaran.max' => 'Tahun ajaran maksimal 20 karakter.',
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
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran.string' => 'Tahun ajaran harus berupa teks.',
            'tahun_ajaran.max' => 'Tahun ajaran maksimal 20 karakter.',
            'semester.integer' => 'Semester tidak valid.',
            'semester.in' => 'Semester tidak valid. Pilih semester 1 atau 2.',
        ]);

        $semester = Nilai::resolveSemester($validated['semester'] ?? 1);
        $tahunAjaran = trim($validated['tahun_ajaran']);

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
        $rataRata = $nilai->isNotEmpty()
            ? Nilai::rataRataRaport($nilai)
            : null;

        return view('raport-preview', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'nilai' => $nilai,
            'semester' => $semester,
            'tahunAjaran' => $tahunAjaran,
            'rataRata' => $rataRata,
            'waliKelas' => $kelas?->waliKelas,
            'kepalaSekolah' => $kepalaSekolah,
        ]);
    }
}
