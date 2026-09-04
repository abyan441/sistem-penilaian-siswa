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

class RaportController extends ApiController
{
    private function pastikanAksesRaport(): void
    {
        $user = Auth::user();

        if ($user?->role === 'guru' && !Kelas::dapatAksesRaport((int) $user->id)) {
            throw new HttpException(403, 'Menu raport hanya dapat diakses oleh guru yang menjadi wali kelas.');
        }
    }

    private function kelasWaliGuru(?string $tahunAjaran = null): ?Kelas
    {
        $user = Auth::user();

        if ($user?->role !== 'guru') {
            return null;
        }

        return Kelas::kelasWaliGuru((int) $user->id, $tahunAjaran);
    }

    public function index(Request $request): View
    {
        $this->pastikanAksesRaport();

        $user = Auth::user();
        $isGuruWali = $user?->role === 'guru';
        $tahunAjaranDiminta = $request->query('tahun_ajaran');

        if ($isGuruWali) {
            $tahunAjaranOptions = Kelas::tahunAjaranWaliGuru((int) $user->id);

            if ($tahunAjaranOptions->isEmpty()) {
                throw new HttpException(403, 'Menu raport hanya dapat diakses oleh guru yang menjadi wali kelas.');
            }

            $tahunAjaran = ($tahunAjaranDiminta !== null && $tahunAjaranDiminta !== '')
                ? trim($tahunAjaranDiminta)
                : $tahunAjaranOptions->first();

            if (!$tahunAjaranOptions->contains($tahunAjaran)) {
                $tahunAjaran = $tahunAjaranOptions->first();
            }

            $kelasWali = $this->kelasWaliGuru($tahunAjaran);

            if (!$kelasWali) {
                throw new HttpException(403, 'Anda tidak memiliki kelas wali pada tahun ajaran yang dipilih.');
            }

            $siswa = Siswa::query()
                ->with('kelas')
                ->where('kelas_id', $kelasWali->id)
                ->orderBy('nama_siswa')
                ->orderBy('nisn')
                ->get();
        } else {
            $tahunAjaranOptions = Kelas::tahunAjaranOptions();
            $tahunAjaran = Kelas::resolveTahunAjaran($tahunAjaranDiminta);
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
        $tahunAjaran = trim($validated['tahun_ajaran']);

        if ($user?->role === 'guru') {
            $kelasWali = $this->kelasWaliGuru($tahunAjaran);

            if (!$kelasWali) {
                return $this->successResponse([]);
            }

            $siswa = Siswa::query()
                ->where('kelas_id', $kelasWali->id)
                ->orderBy('nama_siswa')
                ->orderBy('nisn')
                ->get();

            $dataRaport = collect(Siswa::dataRaport($tahunAjaran))
                ->whereIn('id', $siswa->pluck('id'))
                ->values();

            return $this->successResponse($dataRaport);
        }

        return $this->successResponse(Siswa::dataRaport($tahunAjaran));
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

        $query = Siswa::query()
            ->with(['kelas.waliKelas'])
            ->whereKey($id)
            ->whereHas('kelas', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran', $tahunAjaran);
            });

        if ($user?->role === 'guru') {
            $kelasWali = $this->kelasWaliGuru($tahunAjaran);

            if (!$kelasWali) {
                throw new HttpException(403, 'Anda tidak memiliki kelas wali pada tahun ajaran yang dipilih.');
            }

            $query->where('kelas_id', $kelasWali->id);
        }

        $siswa = $query->firstOrFail();

        $nilai = Nilai::dataRaportSiswa($siswa->id, $semester, $tahunAjaran);
        $kepalaSekolah = User::kepalaSekolahAktif();
        $kelas = $siswa->kelas ?? throw new HttpException(500, 'Data kelas siswa tidak ditemukan.');

        if (!$kepalaSekolah) {
            \Log::warning('Tidak ada kepala sekolah aktif untuk raport siswa ID: ' . $siswa->id);
        }

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
