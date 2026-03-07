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
            // No changes - using separate migrations instead
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpjs', function (Blueprint $table) {
            // Restore activity_id and foreign key
            $table->unsignedBigInteger('activity_id')->after('type');
            $table->foreign('activity_id')->references('id')->on('activities');
            
            // Drop kegiatan column
            $table->dropColumn('kegiatan');
            
            // Restore catatan column
            $table->text('catatan')->nullable();
        });
    }
};
