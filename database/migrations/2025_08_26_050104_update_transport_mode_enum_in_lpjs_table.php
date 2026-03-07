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
        // Change transport_mode from enum to string to support new transport options
        Schema::table('lpjs', function (Blueprint $table) {
            $table->string('transport_mode')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpjs', function (Blueprint $table) {
            // Revert back to enum if needed
            $table->enum('transport_mode', ['DARAT', 'LAUT'])->nullable()->change();
        });
    }
};
