<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function statistik(): array
    {
        return [
            'totalSiswa' => Siswa::query()->count(),
            'totalGuru' => User::query()->where('role', 'guru')->count(),
            'totalKelas' => Kelas::query()->count(),
        ];
    }

    public function perkembanganNilai(): array
    {
        $data = DB::table('nilai')
            ->join('siswa', 'siswa.id', '=', 'nilai.siswa_id')
            ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
            ->whereNotNull('nilai.nilai_akhir')
            ->select(
                'kelas.tahun_ajaran',
                DB::raw('AVG(nilai.nilai_akhir) as rata_rata')
            )
            ->groupBy('kelas.tahun_ajaran')
            ->orderBy('kelas.tahun_ajaran')
            ->get();

        return [
            'labels' => $data->pluck('tahun_ajaran')->values()->all(),
            'values' => $data
                ->map(fn ($item) => round((float) $item->rata_rata, 2))
                ->values()
                ->all(),
        ];
    }

    public function aktivitasTerbaru(int $limit = 6): Collection
    {
        return Nilai::query()
            ->with([
                'siswa.kelas',
                'guruMapel.guru',
                'guruMapel.mataPelajaran',
            ])
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function data(): array
    {
        $statistik = $this->statistik();
        $perkembangan = $this->perkembanganNilai();

        return [
            ...$statistik,
            'chartLabels' => $perkembangan['labels'],
            'chartValues' => $perkembangan['values'],
            'aktivitas' => $this->aktivitasTerbaru(),
        ];
    }
}
