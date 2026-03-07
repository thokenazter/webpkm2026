<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poas', function (Blueprint $table) {
            // Prevent duplicate POA for the same RAB in the same year
            $table->unique(['year', 'rab_id'], 'poas_year_rab_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('poas', function (Blueprint $table) {
            $table->dropUnique('poas_year_rab_id_unique');
        });
    }
};
