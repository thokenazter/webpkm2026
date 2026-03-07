<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annual_budgets', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->index();
            $table->string('name')->default('Pagu BOK');
            $table->decimal('amount', 16, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['year', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_budgets');
    }
};

