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
        Schema::create('pelanggaran_sekolah', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('santri_id');
            $table->enum('jenis_pelanggaran',['ringan','sedang','berat']);
            $table->enum('status_pelanggaran',['sp1','sp2','sp3']);
            $table->text('keterangan_pelanggaran');
            $table->string('foto_bukti_pelanggaran')->nullable();
            $table->date('tanggal_pelanggaran')->nullable();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_sekolah');
    }
};
