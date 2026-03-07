<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin LPJ BOK',
            'email' => 'admin@puskesmas.go.id',
        ]);

        // Run all seeders
        $this->call([
            EmployeeSeeder::class,
            VillageSeeder::class,
            ActivitySeeder::class,
            RateSettingSeeder::class,
            AkunPegawaiSeeder::class,
            // RAB master data & sample RAB
            RabMenuSeeder::class,
            RabKegiatanSeeder::class,
            RabSeeder::class,
            // Budget & allocation
            AnnualBudgetSeeder::class,
            BudgetAllocationSeeder::class,
            // PerDiemRateSeeder tidak diperlukan karena uang harian fixed Rp 150.000 per desa
            // LpjSeeder::class, // Sementara dinonaktifkan
        ]);

        // Create sample LPJ data manually
        $this->createSampleLpjData();
    }

    private function createSampleLpjData()
    {
        // Get first user as creator
        $user = \App\Models\User::first();
        
        // Create SPPT for desa darat
        $sppt = \App\Models\Lpj::create([
            'type' => 'SPPT',
            'kegiatan' => 'Pelayanan Kesehatan Rutin',
            'no_surat' => 'SPPT/001/2024',
            'tanggal_surat' => '15 Januari 2024',
            'tanggal_kegiatan' => '20 Januari 2024',
            'transport_mode' => 'DARAT',
            'jumlah_desa_darat' => 2,
            'desa_tujuan_darat' => 'Desa Kabalsiang dan Desa Benjuring',
            'jumlah_desa_seberang' => 0,
            'desa_tujuan_seberang' => null,
            'created_by' => $user->id,
        ]);

        // Add participant for SPPT
        \App\Models\LpjParticipant::create([
            'lpj_id' => $sppt->id,
            'employee_id' => 1,
            'role' => 'KETUA',
            'lama_tugas_hari' => 1,
            'transport_amount' => 140000.00, // 70.000 x 2 desa
            'per_diem_rate' => 0.00,
            'per_diem_days' => 0,
            'per_diem_amount' => 0.00,
            'total_amount' => 140000.00,
        ]);

        // Create SPPD for desa seberang
        $sppd = \App\Models\Lpj::create([
            'type' => 'SPPD',
            'kegiatan' => 'Promosi Kesehatan',
            'no_surat' => 'SPPD/001/2024',
            'tanggal_surat' => '10 Januari 2024',
            'tanggal_kegiatan' => '15 s/d 17 Januari 2024',
            'transport_mode' => 'LAUT',
            'jumlah_desa_darat' => 0,
            'desa_tujuan_darat' => null,
            'jumlah_desa_seberang' => 3,
            'desa_tujuan_seberang' => 'Desa Kumul, Desa Batuley dan Desa Kompane',
            'created_by' => $user->id,
        ]);

        // Add participants for SPPD
        \App\Models\LpjParticipant::create([
            'lpj_id' => $sppd->id,
            'employee_id' => 2,
            'role' => 'KETUA',
            'lama_tugas_hari' => 3,
            'transport_amount' => 210000.00, // 70.000 x 3 desa
            'per_diem_rate' => 450000.00, // 150.000 x 3 desa
            'per_diem_days' => 1,
            'per_diem_amount' => 450000.00,
            'total_amount' => 660000.00,
        ]);

        \App\Models\LpjParticipant::create([
            'lpj_id' => $sppd->id,
            'employee_id' => 3,
            'role' => 'ANGGOTA',
            'lama_tugas_hari' => 3,
            'transport_amount' => 210000.00,
            'per_diem_rate' => 0.00,
            'per_diem_days' => 0,
            'per_diem_amount' => 0.00,
            'total_amount' => 210000.00,
        ]);
    }
}
