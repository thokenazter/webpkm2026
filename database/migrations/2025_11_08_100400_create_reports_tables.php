<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_monthlies', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->unsignedTinyInteger('month');
            $table->enum('status', ['OPEN','REVIEW','LOCKED','SUBMITTED','CLOSED'])->default('OPEN')->index();
            $table->json('totals')->nullable();
            $table->json('exports')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['year','month']);
        });

        Schema::create('report_annuals', function (Blueprint $table) {
            $table->id();
            $table->integer('year')->unique();
            $table->enum('status', ['OPEN','REVIEW','LOCKED','SUBMITTED','CLOSED'])->default('OPEN')->index();
            $table->json('totals')->nullable();
            $table->json('exports')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_annuals');
        Schema::dropIfExists('report_monthlies');
    }
};

