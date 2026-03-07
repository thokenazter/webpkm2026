<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poa_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poa_id')->constrained('poas')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('role')->nullable(); // PJ/PENDAMPING etc
            $table->foreignId('borrowed_employee_id')->nullable()->constrained('employees')->nullOnDelete(); // if using ASN name
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poa_participants');
    }
};

