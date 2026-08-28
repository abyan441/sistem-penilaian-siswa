<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $perkembangan = Nilai::perkembanganDashboard();

        return view('dashboard', [
            'totalSiswa' => Siswa::total(),
            'totalGuru' => User::totalGuru(),
            'totalKelas' => Kelas::total(),
            'chartLabels' => $perkembangan['labels'],
            'chartValues' => $perkembangan['values'],
            'aktivitas' => Nilai::aktivitasTerbaru(),
        ]);
    }
}
