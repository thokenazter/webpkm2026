<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poas', function (Blueprint $table) {
            if (!Schema::hasColumn('poas', 'item_progress')) {
                $table->json('item_progress')->nullable()->after('schedule');
            }
        });
    }

    public function down(): void
    {
        Schema::table('poas', function (Blueprint $table) {
            if (Schema::hasColumn('poas', 'item_progress')) {
                $table->dropColumn('item_progress');
            }
        });
    }
};

