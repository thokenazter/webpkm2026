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
        Schema::table('lpj_participants', function (Blueprint $table) {
            if (!Schema::hasColumn('lpj_participants', 'credited_employee_id')) {
                $table->foreignId('credited_employee_id')
                    ->nullable()
                    ->after('employee_id')
                    ->constrained('employees')
                    ->nullOnDelete();
            }
        });

        // Backfill: default credited_employee_id = employee_id for existing records
        try {
            DB::table('lpj_participants')
                ->whereNull('credited_employee_id')
                ->update(['credited_employee_id' => DB::raw('employee_id')]);
        } catch (\Throwable $e) {
            // ignore if table empty or any failure; admins can re-run manually
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lpj_participants', function (Blueprint $table) {
            if (Schema::hasColumn('lpj_participants', 'credited_employee_id')) {
                $table->dropForeign(['credited_employee_id']);
                $table->dropColumn('credited_employee_id');
            }
        });
    }
};
