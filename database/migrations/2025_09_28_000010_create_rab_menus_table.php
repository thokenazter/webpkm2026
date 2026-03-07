<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rab_menus', function (Blueprint $table) {
            $table->id();
            $table->string('component_key'); // komp1..komp5 per App\Models\Rab::components()
            $table->string('name');
            $table->timestamps();
            $table->index(['component_key', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rab_menus');
    }
};

