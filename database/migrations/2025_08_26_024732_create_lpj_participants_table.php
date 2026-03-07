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
        Schema::create('lpj_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['KETUA', 'ANGGOTA', 'PENDAMPING', 'LAINNYA'])->nullable();
            $table->smallInteger('lama_tugas_hari');
            $table->decimal('transport_amount', 16, 2);
            $table->decimal('per_diem_rate', 16, 2);
            $table->smallInteger('per_diem_days');
            $table->decimal('per_diem_amount', 16, 2);
            $table->decimal('total_amount', 16, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpj_participants');
    }
};
