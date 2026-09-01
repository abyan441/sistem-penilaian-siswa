<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function indexExists(string $table, string $index): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->exists();
    }

    public function up(): void
    {
        if (!$this->indexExists('kelas', 'unique_kelas_tahun_ajaran')) {
            if ($this->indexExists('kelas', 'nama_kelas_UNIQUE')) {
                DB::statement('ALTER TABLE `kelas` DROP INDEX `nama_kelas_UNIQUE`');
            }

            DB::statement(
                'ALTER TABLE `kelas` ADD UNIQUE INDEX `unique_kelas_tahun_ajaran` (`nama_kelas`, `tahun_ajaran`)'
            );
        }

        if (!$this->indexExists('kelas', 'unique_wali_tahun_ajaran')) {
            if ($this->indexExists('kelas', 'wali_kelas_id_UNIQUE')) {
                DB::statement('ALTER TABLE `kelas` DROP INDEX `wali_kelas_id_UNIQUE`');
            }

            DB::statement(
                'ALTER TABLE `kelas` ADD UNIQUE INDEX `unique_wali_tahun_ajaran` (`wali_kelas_id`, `tahun_ajaran`)'
            );
        }
    }

    public function down(): void
    {
        if ($this->indexExists('kelas', 'unique_wali_tahun_ajaran')) {
            DB::statement('ALTER TABLE `kelas` DROP INDEX `unique_wali_tahun_ajaran`');
        }

        if ($this->indexExists('kelas', 'unique_kelas_tahun_ajaran')) {
            DB::statement('ALTER TABLE `kelas` DROP INDEX `unique_kelas_tahun_ajaran`');
        }

        if (!$this->indexExists('kelas', 'nama_kelas_UNIQUE')) {
            DB::statement('ALTER TABLE `kelas` ADD UNIQUE INDEX `nama_kelas_UNIQUE` (`nama_kelas`)');
        }

        if (!$this->indexExists('kelas', 'wali_kelas_id_UNIQUE')) {
            DB::statement('ALTER TABLE `kelas` ADD UNIQUE INDEX `wali_kelas_id_UNIQUE` (`wali_kelas_id`)');
        }
    }
};
