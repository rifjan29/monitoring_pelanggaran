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
            $table->enum('status_pondok',['lanjut','boyong'])->default('lanjut')->after('status');
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
