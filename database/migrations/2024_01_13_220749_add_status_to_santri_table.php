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
        Schema::table('data_santri', function (Blueprint $table) {
            $table->enum('status',['lulus','belum-lulus'])->after('tanggal_lahir')->default('belum-lulus');
            $table->date('tanggal_lulus')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_santri', function (Blueprint $table) {
            //
        });
    }
};
