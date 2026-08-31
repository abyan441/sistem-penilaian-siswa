<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RaportController extends Controller
{
    private function pastikanAksesRaport(): void
    {
        $user = Auth::user();

        if ($user?->role === 'guru' && !Kelas::query()->where('wali_kelas_id', $user->id)->exists()) {
            throw new HttpException(403, 'Menu raport hanya dapat diakses oleh guru yang menjadi wali kelas.');
        }
    }

    private function kelasWaliGuru(): ?Kelas
    {
        $user = Auth::user();

        if ($user?->role !== 'guru') {
            return null;
        }

        return Kelas::query()
            ->where('wali_kelas_id', $user->id)
            ->first();
    }

    public function index(Request $request): View
    {
        $this->pastikanAksesRaport();

        $user = Auth::user();
        $kelasWali = $this->kelasWaliGuru();
        $isGuruWali = $user?->role === 'guru';

        if ($isGuruWali && !$kelasWali) {
            throw new HttpException(403, 'Menu raport hanya dapat diakses oleh guru yang menjadi wali kelas.');
        }

        if ($isGuruWali) {
            $tahunAjaranOptions = collect([$kelasWali->tahun_ajaran]);
            $tahunAjaran = $kelasWali->tahun_ajaran;
            $siswa = Siswa::query()
                ->with('kelas')
                ->where('kelas_id', $kelasWali->id)
                ->orderBy('nama_siswa')
                ->orderBy('nisn')
                ->get();
        } else {
            $tahunAjaranOptions = Kelas::tahunAjaranOptions();
            $tahunAjaran = Kelas::resolveTahunAjaran($request->query('tahun_ajaran'));
            $siswa = Kelas::siswaUntukRaport($tahunAjaran);
        }

        $semester = Nilai::resolveSemester($request->query('semester', 1));
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
        $this->pastikanAksesRaport();

        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ], [
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih.',
            'tahun_ajaran.string' => 'Tahun ajaran harus berupa teks.',
            'tahun_ajaran.max' => 'Tahun ajaran maksimal 20 karakter.',
        ]);

        $user = Auth::user();
        $kelasWali = $this->kelasWaliGuru();

        if ($user?->role === 'guru') {
            if (!$kelasWali || $kelasWali->tahun_ajaran !== trim($validated['tahun_ajaran'])) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                ]);
            }

            $siswa = Siswa::query()
                ->where('kelas_id', $kelasWali->id)
                ->orderBy('nama_siswa')
                ->orderBy('nisn')
                ->get();

            return response()->json([
                'success' => true,
                'data' => Siswa::dataRaport($validated['tahun_ajaran'])
                    ->whereIn('id', $siswa->pluck('id'))
                    ->values(),
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => Siswa::dataRaport($validated['tahun_ajaran']),
        ]);
    }

    public function preview(Request $request, int $id): View
    {
        $this->pastikanAksesRaport();

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
        $user = Auth::user();
        $kelasWali = $this->kelasWaliGuru();

        $query = Siswa::query()
            ->with(['kelas.waliKelas'])
            ->whereKey($id)
            ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran', $tahunAjaran);
            });

        if ($user?->role === 'guru') {
            if (!$kelasWali) {
                throw new HttpException(403, 'Menu raport hanya dapat diakses oleh guru yang menjadi wali kelas.');
            }

            $query->where('kelas_id', $kelasWali->id);
        }

        $siswa = $query->firstOrFail();

        $nilai = Nilai::dataRaportSiswa($siswa->id, $semester);
        $kepalaSekolah = User::kepalaSekolahAktif();
        $kelas = $siswa->kelas;
        $rataRata = $nilai->isNotEmpty() ? Nilai::rataRataRaport($nilai) : null;

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
