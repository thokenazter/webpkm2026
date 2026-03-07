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
        Schema::create('lpjs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['SPPT', 'SPPD']);
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            // Menghapus village_id karena LPJ bisa untuk multiple desa
            // $table->foreignId('village_id')->constrained()->onDelete('cascade');
            $table->string('no_surat')->unique();
            $table->date('tanggal_surat');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('transport_mode')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpjs');
    }
};
