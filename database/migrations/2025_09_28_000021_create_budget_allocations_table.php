<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annual_budget_id')->constrained('annual_budgets')->cascadeOnDelete();
            $table->foreignId('rab_id')->constrained('rabs')->cascadeOnDelete();
            $table->decimal('allocated_amount', 16, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['annual_budget_id', 'rab_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
    }
};

