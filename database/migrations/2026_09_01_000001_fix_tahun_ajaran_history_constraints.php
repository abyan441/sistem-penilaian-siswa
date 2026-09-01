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

    private function dropIndexIfExists(string $table, string $index): void
    {
        if ($this->indexExists($table, $index)) {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$index}`");
        }
    }

    private function addUniqueIfMissing(string $table, string $index, array $columns): void
    {
        if ($this->indexExists($table, $index)) {
            return;
        }

        $columnSql = implode(', ', array_map(
            fn ($column) => "`{$column}`",
            $columns
        ));

        DB::statement(
            "ALTER TABLE `{$table}` ADD UNIQUE INDEX `{$index}` ({$columnSql})"
        );
    }

    public function up(): void
    {
        /*
         * KELAS
         *
         * Nama kelas boleh sama selama tahun ajarannya berbeda.
         * Contoh: 7A 2026/2027 dan 7A 2027/2028 adalah dua record
         * kelas yang berbeda.
         *
         * Wali kelas juga boleh menjadi wali pada tahun ajaran berbeda,
         * tetapi tidak boleh menjadi wali untuk dua kelas dalam tahun
         * ajaran yang sama.
         */
        if ($this->indexExists('kelas', 'nama_kelas_UNIQUE')) {
            $this->dropIndexIfExists('kelas', 'nama_kelas_UNIQUE');
        }

        if ($this->indexExists('kelas', 'wali_kelas_id_UNIQUE')) {
            $this->dropIndexIfExists('kelas', 'wali_kelas_id_UNIQUE');
        }

        $this->addUniqueIfMissing(
            'kelas',
            'unique_kelas_tahun_ajaran',
            ['nama_kelas', 'tahun_ajaran']
        );

        $this->addUniqueIfMissing(
            'kelas',
            'unique_wali_tahun_ajaran',
            ['wali_kelas_id', 'tahun_ajaran']
        );

        /*
         * SISWA
         *
         * NISN tetap identitas siswa, tetapi record siswa dibuat per
         * kelas/tahun ajaran agar riwayat nilai tidak ikut berpindah
         * ketika siswa naik kelas.
         *
         * Karena itu NISN tidak boleh UNIQUE secara global. NISN yang
         * sama boleh muncul pada record siswa untuk kelas yang berbeda,
         * tetapi tidak boleh dua kali pada kelas yang sama.
         */
        if ($this->indexExists('siswa', 'nisn_UNIQUE')) {
            $this->dropIndexIfExists('siswa', 'nisn_UNIQUE');
        }

        $this->addUniqueIfMissing(
            'siswa',
            'unique_siswa_nisn_kelas',
            ['nisn', 'kelas_id']
        );

        /*
         * NILAI
         *
         * Guru mapel yang sama dapat menginput mata pelajaran yang sama
         * pada tahun ajaran berbeda. Karena itu tahun_ajaran harus ikut
         * menjadi bagian dari UNIQUE constraint.
         */
        if ($this->indexExists('nilai', 'unique_nilai_siswa_mapel_semester')) {
            $this->dropIndexIfExists('nilai', 'unique_nilai_siswa_mapel_semester');
        }

        $this->addUniqueIfMissing(
            'nilai',
            'unique_nilai_siswa_mapel_tahun_semester',
            ['siswa_id', 'guru_mapel_id', 'tahun_ajaran', 'semester']
        );
    }

    public function down(): void
    {
        $this->dropIndexIfExists(
            'nilai',
            'unique_nilai_siswa_mapel_tahun_semester'
        );

        $this->addUniqueIfMissing(
            'nilai',
            'unique_nilai_siswa_mapel_semester',
            ['siswa_id', 'guru_mapel_id', 'semester']
        );

        $this->dropIndexIfExists('siswa', 'unique_siswa_nisn_kelas');

        $this->addUniqueIfMissing('siswa', 'nisn_UNIQUE', ['nisn']);

        $this->dropIndexIfExists('kelas', 'unique_kelas_tahun_ajaran');
        $this->dropIndexIfExists('kelas', 'unique_wali_tahun_ajaran');

        $this->addUniqueIfMissing('kelas', 'wali_kelas_id_UNIQUE', ['wali_kelas_id']);
    }
};
