<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tiba_berangkat') && !Schema::hasColumn('tiba_berangkat', 'created_by')) {
            Schema::table('tiba_berangkat', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->after('no_surat')->constrained('users')->nullOnDelete();
                $table->index('created_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tiba_berangkat') && Schema::hasColumn('tiba_berangkat', 'created_by')) {
            Schema::table('tiba_berangkat', function (Blueprint $table) {
                $table->dropConstrainedForeignId('created_by');
            });
        }
    }
};

