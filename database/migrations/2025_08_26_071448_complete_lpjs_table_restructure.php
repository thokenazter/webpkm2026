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
            // Drop old columns
            $table->dropForeign(['activity_id']);
            $table->dropColumn(['activity_id', 'tgl_mulai', 'tgl_selesai', 'catatan']);
            
            // Add new columns
            $table->string('tanggal_kegiatan')->nullable()->after('tanggal_surat');
            $table->text('desa_tujuan_darat')->nullable()->after('jumlah_desa_darat');
            $table->text('desa_tujuan_seberang')->nullable()->after('jumlah_desa_seberang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpjs', function (Blueprint $table) {
            // Restore old columns
            $table->unsignedBigInteger('activity_id')->after('kegiatan');
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->date('tgl_mulai')->after('tanggal_surat');
            $table->date('tgl_selesai')->after('tgl_mulai');
            $table->text('catatan')->nullable();
            
            // Drop new columns
            $table->dropColumn(['tanggal_kegiatan', 'desa_tujuan_darat', 'desa_tujuan_seberang']);
        });
    }
};
