<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use Illuminate\Http\JsonResponse;
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

    public function preview(Request $request, int $kelasId): View
    {
        $this->pastikanAkses();

        $validated = $request->validate([
            'mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'semester' => ['required', 'integer', 'in:1,2'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        $tahunAjaranOptions = $this->ambilTahunAjaranOptions();
        $tahunAjaran = trim($validated['tahun_ajaran']);

        if (!$tahunAjaranOptions->contains($tahunAjaran)) {
            throw new HttpException(422, 'Tahun ajaran yang dipilih tidak tersedia.');
        }

        $kelas = Kelas::query()
            ->whereKey($kelasId)
            ->where('tahun_ajaran', $tahunAjaran)
            ->with('waliKelas')
            ->firstOrFail();

        $user = auth()->user();

        if ($user?->role === 'guru' && (int) $kelas->wali_kelas_id !== (int) $user->id) {
            throw new HttpException(403, 'Anda hanya dapat melihat rekap nilai kelas yang menjadi kelas wali Anda.');
        }

        $mapel = MataPelajaran::query()->findOrFail((int) $validated['mapel_id']);
        $semester = (int) $validated['semester'];

        $data = Nilai::dataRekapNilaiMapel(
            (int) $kelas->id,
            (int) $mapel->id,
            $semester,
            $tahunAjaran
        );

        return view('rekap-nilai-mapel-preview', [
            'kelas' => $kelas,
            'mapel' => $mapel,
            'semester' => $semester,
            'tahunAjaran' => $tahunAjaran,
            'data' => $data,
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->pastikanAkses();

        $validated = $request->validate([
            'mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'semester' => ['required', 'integer', 'in:1,2'],
            'tahun_ajaran' => ['required', 'string', 'max:20'],
        ]);

        $tahunAjaranOptions = $this->ambilTahunAjaranOptions();
        $tahunAjaran = trim($validated['tahun_ajaran']);

        if (!$tahunAjaranOptions->contains($tahunAjaran)) {
            return $this->errorResponse('Tahun ajaran yang dipilih tidak tersedia.', 422);
        }

        $mapel = MataPelajaran::query()->find((int) $validated['mapel_id']);

        if (!$mapel) {
            return $this->notFoundResponse('Mata pelajaran');
        }

        $kelas = Kelas::dataRekapNilaiMapel($tahunAjaran);

        $data = $kelas->values()->map(function ($item, $index) use ($mapel, $validated) {
            return [
                'nomor' => $index + 1,
                'mapel_id' => (int) $mapel->id,
                'mata_pelajaran' => $mapel->nama_pelajaran,
                'kelas_id' => (int) $item->id,
                'kelas' => $item->nama_kelas,
                'semester' => (int) $validated['semester'],
                'tahun_ajaran' => $item->tahun_ajaran,
            ];
        })->all();

        return $this->successResponse($data);
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
