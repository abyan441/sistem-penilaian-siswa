<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RekapNilaiMapelController extends ApiController
{
    public function index(Request $request): View
    {
        $this->pastikanAkses();

        $tahunAjaranOptions = $this->ambilTahunAjaranOptions();
        $tahunAjaran = $this->tentukanTahunAjaran(
            $request->query('tahun_ajaran'),
            $tahunAjaranOptions
        );

        return view('rekap-nilai-mapel', [
            'mataPelajaran' => GuruMapel::daftarMataPelajaran(),
            'tahunAjaranOptions' => $tahunAjaranOptions,
            'tahunAjaran' => $tahunAjaran,
            'semester' => Nilai::resolveSemester($request->query('semester', 1)),
        ]);
    }

    private function pastikanAkses(): void
    {
        $user = auth()->user();

        if ($user?->role === 'guru' && !Kelas::dapatAksesRaport((int) $user->id)) {
            throw new HttpException(
                403,
                'Menu rekap nilai mapel hanya dapat diakses oleh guru yang menjadi wali kelas.'
            );
        }
    }

    private function ambilTahunAjaranOptions()
    {
        $user = auth()->user();

        if ($user?->role === 'guru') {
            $options = Kelas::tahunAjaranWaliGuru((int) $user->id);

            if ($options->isEmpty()) {
                throw new HttpException(
                    403,
                    'Menu rekap nilai mapel hanya dapat diakses oleh guru yang menjadi wali kelas.'
                );
            }

            return $options;
        }

        return Kelas::tahunAjaranOptions();
    }

    private function tentukanTahunAjaran(?string $tahunAjaran, $options): ?string
    {
        if ($options->isEmpty()) {
            return Kelas::resolveTahunAjaran($tahunAjaran);
        }

        $tahunAjaran = is_string($tahunAjaran)
            ? trim($tahunAjaran)
            : $tahunAjaran;

        if ($tahunAjaran !== null && $tahunAjaran !== '' && $options->contains($tahunAjaran)) {
            return $tahunAjaran;
        }

        return $options->first();
    }
}
