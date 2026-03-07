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
        if (!Schema::hasTable('tiba_berangkat_detail')) {
            Schema::create('tiba_berangkat_detail', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tiba_berangkat_id')->constrained('tiba_berangkat')->onDelete('cascade');
                $table->foreignId('pejabat_ttd_id')->constrained('pejabat_ttd');
                $table->date('tanggal_kunjungan');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiba_berangkat_detail');
    }
};
