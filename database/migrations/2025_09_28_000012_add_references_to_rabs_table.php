<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rabs', function (Blueprint $table) {
            if (!Schema::hasColumn('rabs', 'rab_menu_id')) {
                $table->foreignId('rab_menu_id')->nullable()->after('komponen')->constrained('rab_menus')->nullOnDelete();
            }
            if (!Schema::hasColumn('rabs', 'rab_kegiatan_id')) {
                $table->foreignId('rab_kegiatan_id')->nullable()->after('rab_menu_id')->constrained('rab_kegiatans')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('rabs', function (Blueprint $table) {
            if (Schema::hasColumn('rabs', 'rab_kegiatan_id')) {
                $table->dropConstrainedForeignId('rab_kegiatan_id');
            }
            if (Schema::hasColumn('rabs', 'rab_menu_id')) {
                $table->dropConstrainedForeignId('rab_menu_id');
            }
        });
    }
};

