<?php

namespace App\Services;

use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RekapNilaiMapelService
{
    public function getIndexData(Request $request): array
    {
        $this->pastikanAkses();

        $tahunAjaranOptions = $this->getTahunAjaranOptions();
        $tahunAjaran = $this->resolveTahunAjaran($request, $tahunAjaranOptions);

        return [
            'mataPelajaran' => GuruMapel::daftarMataPelajaran(),
            'tahunAjaranOptions' => $tahunAjaranOptions,
            'tahunAjaran' => $tahunAjaran,
            'semester' => Nilai::resolveSemester($request->query('semester', 1)),
        ];
    }

    private function pastikanAkses(): void
    {
        $user = Auth::user();

        if ($user?->role === 'guru' && !Kelas::dapatAksesRaport((int) $user->id)) {
            throw new HttpException(
                403,
                'Menu rekap nilai mapel hanya dapat diakses oleh guru yang menjadi wali kelas.'
            );
        }
    }

    private function getTahunAjaranOptions()
    {
        $user = Auth::user();

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

    private function resolveTahunAjaran(Request $request, $options): string
    {
        $requested = $request->query('tahun_ajaran');

        if ($options->isEmpty()) {
            return Kelas::resolveTahunAjaran($requested);
        }

        $requested = is_string($requested) ? trim($requested) : $requested;

        if ($requested !== null && $requested !== '' && $options->contains($requested)) {
            return $requested;
        }

        return (string) $options->first();
    }
}
