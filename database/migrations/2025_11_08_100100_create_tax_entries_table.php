<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tax_entries', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('source');
            $table->enum('tax_type', ['PPN','PPh21','PPh22','PPh23'])->index();
            $table->decimal('base_amount', 16, 2)->default(0);
            $table->decimal('tax_amount', 16, 2)->default(0);
            $table->enum('status', ['Pending','Paid','Verified'])->default('Pending')->index();
            $table->date('due_date')->nullable()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('evidence_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_entries');
    }
};

