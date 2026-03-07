<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poas', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->index();
            $table->foreignId('annual_budget_id')->nullable()->constrained('annual_budgets')->nullOnDelete();
            $table->foreignId('rab_id')->constrained('rabs')->cascadeOnDelete();
            $table->string('nomor_surat')->nullable();
            $table->string('kegiatan');
            $table->text('output_target')->nullable();
            $table->json('schedule')->nullable(); // { months: [{month:1,count:1,amount:...}, ...], total_occurrences: 12 }
            $table->decimal('planned_total', 16, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poas');
    }
};

