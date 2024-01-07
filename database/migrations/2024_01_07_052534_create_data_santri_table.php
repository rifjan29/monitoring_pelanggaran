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
        Schema::create('data_santri', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('foto')->nullable();
            $table->text('asal');
            $table->enum('jenis_kelamin',['l','p']);
            $table->text('alamat_lengkap');
            $table->bigInteger('wali_santri_id');
            $table->bigInteger('asrama_id');
            $table->bigInteger('users_id');
            $table->enum('kategori_sekolah',['smk','mts','sma']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_santri');
    }
};
