<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('nilai') || Schema::hasColumn('nilai', 'tahun_ajaran')) {
            return;
        }

        Schema::table('nilai', function (Blueprint $table) {
            $table->string('tahun_ajaran', 9)->nullable()->after('guru_mapel_id');
        });

        if (Schema::hasTable('siswa') && Schema::hasTable('kelas')) {
            DB::table('nilai')
                ->join('siswa', 'siswa.id', '=', 'nilai.siswa_id')
                ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
                ->select('nilai.id', 'kelas.tahun_ajaran')
                ->orderBy('nilai.id')
                ->get()
                ->each(function ($nilai) {
                    DB::table('nilai')
                        ->where('id', $nilai->id)
                        ->update(['tahun_ajaran' => $nilai->tahun_ajaran]);
                });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('nilai') && Schema::hasColumn('nilai', 'tahun_ajaran')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->dropColumn('tahun_ajaran');
            });
        }
    }
};