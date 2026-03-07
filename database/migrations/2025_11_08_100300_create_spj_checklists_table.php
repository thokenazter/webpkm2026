<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spj_checklists', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('subject');
            $table->enum('spj_type', ['TRAVEL','MEETING','PURCHASE','HONORARIUM'])->index();
            $table->enum('status', ['DRAFT','SUBMITTED','VERIFIED','APPROVED','SIGNED','PAID','POSTED'])->default('DRAFT')->index();
            $table->integer('completed_count')->default(0);
            $table->integer('items_required')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('spj_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spj_checklist_id')->constrained('spj_checklists')->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->string('label');
            $table->boolean('is_required')->default(true);
            $table->boolean('is_completed')->default(false);
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['spj_checklist_id','is_completed']);
        });

        Schema::create('spj_verification_logs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('subject');
            $table->string('from_state')->nullable();
            $table->string('to_state');
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spj_verification_logs');
        Schema::dropIfExists('spj_checklist_items');
        Schema::dropIfExists('spj_checklists');
    }
};

