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
            // Change tanggal_surat from date to string
            $table->string('tanggal_surat')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpjs', function (Blueprint $table) {
            // Revert back to date
            $table->date('tanggal_surat')->change();
        });
    }
};
