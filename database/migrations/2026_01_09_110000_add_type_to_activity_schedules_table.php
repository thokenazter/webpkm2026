<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new columns
        Schema::table('activity_schedules', function (Blueprint $table) {
            $table->string('type', 10)->default('SPPT')->after('poa_id'); // SPPT or SPPD
            $table->unsignedTinyInteger('desa_count')->default(1)->after('type'); // Number of villages
        });

        // Drop the old unique index using raw SQL (workaround for MySQL constraint issue)
        DB::statement('ALTER TABLE activity_schedules DROP INDEX unique_poa_month_year');
        
        // Add new unique constraint including type
        Schema::table('activity_schedules', function (Blueprint $table) {
            $table->unique(['poa_id', 'month', 'year', 'type'], 'unique_poa_month_year_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_schedules', function (Blueprint $table) {
            $table->dropUnique('unique_poa_month_year_type');
            $table->dropColumn(['type', 'desa_count']);
        });
        
        Schema::table('activity_schedules', function (Blueprint $table) {
            $table->unique(['poa_id', 'month', 'year'], 'unique_poa_month_year');
        });
    }
};
