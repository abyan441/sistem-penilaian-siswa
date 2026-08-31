<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('nilai')) {
            return;
        }

        if (!Schema::hasColumn('nilai', 'mapel_id')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->unsignedInteger('mapel_id')->nullable()->after('siswa_id');
            });
        }

        // Pindahkan identitas mata pelajaran dari guru_mapel ke nilai.
        // Data nilai lama tetap dipertahankan meskipun penugasan guru_mapel
        // nantinya dihapus.
        if (Schema::hasTable('guru_mapel') && Schema::hasColumn('nilai', 'guru_mapel_id')) {
            DB::statement(<<<'SQL'
                UPDATE nilai n
                INNER JOIN guru_mapel gm ON gm.id = n.guru_mapel_id
                SET n.mapel_id = gm.mapel_id
                WHERE n.mapel_id IS NULL
            SQL);
        }

        // Relasi guru_mapel pada nilai dibuat opsional agar penghapusan
        // penugasan guru tidak ikut menghapus atau menghalangi data nilai.
        if (Schema::hasColumn('nilai', 'guru_mapel_id')) {
            try {
                Schema::table('nilai', function (Blueprint $table) {
                    $table->dropForeign(['guru_mapel_id']);
                });
            } catch (\Throwable $e) {
                // Foreign key mungkin sudah tidak menggunakan nama standar.
                // Perubahan kolom tetap dicoba di bawah.
            }

            Schema::table('nilai', function (Blueprint $table) {
                $table->unsignedInteger('guru_mapel_id')->nullable()->change();
            });
        }

        Schema::table('nilai', function (Blueprint $table) {
            $table->foreign('mapel_id')
                ->references('id')
                ->on('mata_pelajaran')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('nilai') || !Schema::hasColumn('nilai', 'mapel_id')) {
            return;
        }

        try {
            Schema::table('nilai', function (Blueprint $table) {
                $table->dropForeign(['mapel_id']);
            });
        } catch (\Throwable $e) {
            // Abaikan jika foreign key sudah tidak ada.
        }

        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('mapel_id');
        });

        if (Schema::hasColumn('nilai', 'guru_mapel_id')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->unsignedInteger('guru_mapel_id')->nullable(false)->change();
            });
        }
    }
};
