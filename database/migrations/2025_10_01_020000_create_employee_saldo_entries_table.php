<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_saldo_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('poa_id')->nullable()->constrained('poas')->nullOnDelete();
            $table->unsignedBigInteger('rab_item_id')->nullable();
            $table->integer('year');
            $table->unsignedTinyInteger('month');
            $table->string('category', 50)->nullable();
            $table->string('label', 255)->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'year', 'month']);
            $table->index(['poa_id', 'rab_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_saldo_entries');
    }
};

