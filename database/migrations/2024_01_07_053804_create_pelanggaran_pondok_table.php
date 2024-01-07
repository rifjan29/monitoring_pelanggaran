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
        Schema::create('pelanggaran_pondok', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('santri_id');
            $table->enum('jenis_pelanggaran',['ringan','sedang','berat']);
            $table->text('keterangan_pelanggaran');
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
        Schema::dropIfExists('pelanggaran_pondok');
    }
};
