<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_opsional_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poa_id')->constrained('poas')->cascadeOnDelete();
            $table->foreignId('rab_item_id')->nullable()->constrained('rab_items')->nullOnDelete();
            $table->unsignedTinyInteger('month'); // 1-12
            $table->string('label');              // e.g. "Konsumsi", "Snack"
            $table->string('type');               // e.g. "konsumsi", "snack"
            $table->decimal('amount', 15, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['poa_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_opsional_claims');
    }
};
