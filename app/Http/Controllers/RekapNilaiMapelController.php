<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RekapNilaiMapelController extends ApiController
{
    private function pastikanAkses(): void
    {
        $user = Auth::user();

        if ($user?->role === 'guru' && !Kelas::dapatAksesRaport((int) $user->id)) {
            throw new HttpException(403, 'Menu rekap nilai mapel hanya dapat diakses oleh guru yang menjadi wali kelas.');
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
        $this->pastikanAkses();

        $user = Auth::user();
        $tahunAjaranDiminta = $request->query('tahun_ajaran');

        if ($user?->role === 'guru') {
            $tahunAjaranOptions = Kelas::tahunAjaranWaliGuru((int) $user->id);

            if ($tahunAjaranOptions->isEmpty()) {
                throw new HttpException(403, 'Menu rekap nilai mapel hanya dapat diakses oleh guru yang menjadi wali kelas.');
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

        return view('rekap-nilai-mapel', compact(
            'siswa',
            'tahunAjaranOptions',
            'tahunAjaran',
            'semester',
            'siswaTerpilih'
        ));
    }
}
