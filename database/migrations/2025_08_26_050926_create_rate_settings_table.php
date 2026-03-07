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
        Schema::create('rate_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'transport_rate', 'per_diem_rate'
            $table->string('name'); // 'Uang Transport', 'Uang Harian'
            $table->decimal('value', 16, 2); // Nilai dalam rupiah
            $table->text('description')->nullable(); // Deskripsi penggunaan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_settings');
    }
};
