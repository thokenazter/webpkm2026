<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rab_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_id')->constrained('rabs')->cascadeOnDelete();
            $table->string('label'); // e.g., Transport Darat, Uang Harian, Snack, dll
            $table->string('type')->nullable(); // e.g., transport_darat, uang_harian, snack, dll
            $table->json('factors')->nullable(); // [{key:'orang', label:'Orang', value:2}, ...]
            $table->decimal('unit_price', 16, 2)->default(0);
            $table->decimal('subtotal', 16, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_items');
    }
};

