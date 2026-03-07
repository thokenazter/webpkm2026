<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rabs', function (Blueprint $table) {
            $table->id();
            $table->string('komponen');
            $table->string('rincian_menu');
            $table->string('kegiatan');
            $table->decimal('total', 16, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rabs');
    }
};

