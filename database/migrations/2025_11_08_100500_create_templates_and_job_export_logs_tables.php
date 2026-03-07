<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['LPJ','TB','SPPT','RAB','OTHER'])->index();
            $table->integer('version')->default(1);
            $table->string('disk')->nullable();
            $table->string('path');
            $table->boolean('is_active')->default(false)->index();
            $table->json('meta')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('job_export_logs', function (Blueprint $table) {
            $table->id();
            $table->string('job_id')->unique();
            $table->string('type');
            $table->enum('status', ['QUEUED','RUNNING','SUCCESS','FAILED'])->default('QUEUED')->index();
            $table->json('progress')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->longText('log')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_export_logs');
        Schema::dropIfExists('templates');
    }
};

