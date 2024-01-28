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
        Schema::table('pelanggaran_pondok', function (Blueprint $table) {
            $table->string('jumlah_kehadiran')->nullable()->after('tanggal_pelanggaran');
            $table->string('jumlah_absen')->nullable()->after('jumlah_kehadiran');
            $table->text('keterangan_hadir')->nullable()->after('jumlah_absen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelanggaran_pondok', function (Blueprint $table) {
            //
        });
    }
};
