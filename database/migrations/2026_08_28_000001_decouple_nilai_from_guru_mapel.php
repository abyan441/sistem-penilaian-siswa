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

        // Lepaskan semua foreign key yang mengikat nilai ke guru_mapel.
        // Nama constraint dapat berbeda karena database dibuat melalui
        // MySQL Workbench, sehingga tidak cukup hanya memakai nama standar Laravel.
        if (Schema::hasColumn('nilai', 'guru_mapel_id')) {
            $foreignKeys = DB::select(<<<'SQL'
                SELECT DISTINCT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'nilai'
                  AND COLUMN_NAME = 'guru_mapel_id'
                  AND REFERENCED_TABLE_NAME = 'guru_mapel'
            SQL);

            foreach ($foreignKeys as $foreignKey) {
                $constraint = str_replace('`', '``', $foreignKey->CONSTRAINT_NAME);
                DB::statement("ALTER TABLE `nilai` DROP FOREIGN KEY `{$constraint}`");
            }

            Schema::table('nilai', function (Blueprint $table) {
                $table->unsignedInteger('guru_mapel_id')->nullable()->change();
            });
        }

        // Nilai sekarang mempunyai relasi langsung ke mata pelajaran.
        $mapelForeignKeyExists = DB::selectOne(<<<'SQL'
            SELECT COUNT(*) AS jumlah
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'nilai'
              AND COLUMN_NAME = 'mapel_id'
              AND REFERENCED_TABLE_NAME = 'mata_pelajaran'
        SQL);

        if ((int) ($mapelForeignKeyExists->jumlah ?? 0) === 0) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->foreign('mapel_id')
                    ->references('id')
                    ->on('mata_pelajaran')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('nilai') || !Schema::hasColumn('nilai', 'mapel_id')) {
            return;
        }

        $mapelForeignKeys = DB::select(<<<'SQL'
            SELECT DISTINCT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'nilai'
              AND COLUMN_NAME = 'mapel_id'
              AND REFERENCED_TABLE_NAME = 'mata_pelajaran'
        SQL);

        foreach ($mapelForeignKeys as $foreignKey) {
            $constraint = str_replace('`', '``', $foreignKey->CONSTRAINT_NAME);
            DB::statement("ALTER TABLE `nilai` DROP FOREIGN KEY `{$constraint}`");
        }

        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('mapel_id');
        });
    }
};
