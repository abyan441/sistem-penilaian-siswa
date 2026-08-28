<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::query()->count();
        $totalGuru = User::query()->where('role', 'guru')->count();
        $totalKelas = Kelas::query()->count();

        $chart = DB::table('nilai')
            ->join('siswa', 'siswa.id', '=', 'nilai.siswa_id')
            ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
            ->whereNotNull('nilai.nilai_akhir')
            ->select('kelas.tahun_ajaran', DB::raw('AVG(nilai.nilai_akhir) as rata_rata'))
            ->groupBy('kelas.tahun_ajaran')
            ->orderBy('kelas.tahun_ajaran')
            ->get();

        $aktivitas = Nilai::query()
            ->with(['siswa.kelas', 'guruMapel.guru', 'guruMapel.mataPelajaran'])
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('dashboard', [
            'totalSiswa' => $totalSiswa,
            'totalGuru' => $totalGuru,
            'totalKelas' => $totalKelas,
            'chartLabels' => $chart->pluck('tahun_ajaran')->values()->all(),
            'chartValues' => $chart->map(fn ($item) => round((float) $item->rata_rata, 2))->values()->all(),
            'aktivitas' => $aktivitas,
        ]);
    }
}
