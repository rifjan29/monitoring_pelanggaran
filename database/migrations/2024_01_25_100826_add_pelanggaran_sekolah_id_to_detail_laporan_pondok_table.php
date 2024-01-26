<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detail_laporan_pondok', function (Blueprint $table) {
            $table->bigInteger('pelanggaran_pondok_id')->nullable()->change();
            $table->bigInteger('pelanggaran_sekolah_id')->nullable()->after('pelanggaran_pondok_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_laporan_pondok', function (Blueprint $table) {
            //
        });
    }
};
