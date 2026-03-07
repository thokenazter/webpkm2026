<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rab_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rab_menu_id')->constrained('rab_menus')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->index(['rab_menu_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_kegiatans');
    }
};

