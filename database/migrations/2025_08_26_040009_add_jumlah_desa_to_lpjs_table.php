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
        Schema::table('lpjs', function (Blueprint $table) {
            $table->integer('jumlah_desa_darat')->default(0);
            $table->integer('jumlah_desa_seberang')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpjs', function (Blueprint $table) {
            $table->dropColumn(['jumlah_desa_darat', 'jumlah_desa_seberang']);
        });
    }
};
