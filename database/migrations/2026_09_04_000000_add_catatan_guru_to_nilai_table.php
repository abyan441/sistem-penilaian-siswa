<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('nilai', 'catatan_guru')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->text('catatan_guru')->nullable()->after('nilai_akhir');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('nilai', 'catatan_guru')) {
            Schema::table('nilai', function (Blueprint $table) {
                $table->dropColumn('catatan_guru');
            });
        }
    }
};
