<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lpj;
use App\Models\LpjParticipant;
use App\Models\Employee;
use App\Models\Village;
use Carbon\Carbon;

class DashboardDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data pegawai dan desa minimal
        if (Employee::count() < 5) {
            Employee::factory()->count(10)->create();
        }
        
        if (Village::count() < 5) {
            Village::factory()->count(8)->create();
        }

        // Buat LPJ demo untuk 6 bulan terakhir
        $activities = [
            'Posyandu Balita dan Lansia',
            'Imunisasi Dasar Lengkap',
            'Pemeriksaan Kesehatan Ibu Hamil', 
            'Penyuluhan Gizi Seimbang',
            'Screening TB dan HIV',
            'Program KB dan Kesehatan Reproduksi',
            'Pemeriksaan Kesehatan Anak Sekolah',
            'Surveilans Epidemiologi',
        ];

        $globalCounter = 1;
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $lpjCount = rand(2, 5); // 2-5 LPJ per bulan
            
            for ($j = 0; $j < $lpjCount; $j++) {
                $lpj = Lpj::create([
                    'type' => rand(0, 1) ? 'SPPT' : 'SPPD',
                    'kegiatan' => $activities[array_rand($activities)],
                    'no_surat' => 'DEMO/' . $date->format('m/Y') . '/' . sprintf('%03d', $globalCounter++),
                    'tanggal_surat' => $date->format('d-m-Y'),
                    'tanggal_kegiatan' => $date->addDays(rand(1, 28))->format('d-m-Y'),
                    'transport_mode' => 'Darat',
                    'created_by' => 1,
                    'jumlah_desa_darat' => rand(1, 4),
                    'desa_tujuan_darat' => 'Desa Demo ' . rand(1, 8),
                    'jumlah_desa_seberang' => rand(0, 2),
                    'desa_tujuan_seberang' => rand(0, 1) ? 'Desa Seberang ' . rand(1, 3) : null,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Tambahkan peserta untuk setiap LPJ
                $participantCount = rand(2, 6);
                $employees = Employee::inRandomOrder()->take($participantCount)->get();
                
                foreach ($employees as $employee) {
                    $lamaTugas = rand(1, 3);
                    $transportAmount = 70000; // Rate transport standar
                    $perDiemRate = 150000; // Rate uang harian standar
                    $perDiemDays = $lamaTugas;
                    $perDiemAmount = $perDiemRate * $perDiemDays;
                    $totalAmount = $transportAmount + $perDiemAmount;

                    LpjParticipant::create([
                        'lpj_id' => $lpj->id,
                        'employee_id' => $employee->id,
                        'role' => ['KETUA', 'ANGGOTA', 'PENDAMPING', 'LAINNYA'][array_rand(['KETUA', 'ANGGOTA', 'PENDAMPING', 'LAINNYA'])],
                        'lama_tugas_hari' => $lamaTugas,
                        'transport_amount' => $transportAmount,
                        'per_diem_rate' => $perDiemRate,
                        'per_diem_days' => $perDiemDays,
                        'per_diem_amount' => $perDiemAmount,
                        'total_amount' => $totalAmount,
                    ]);
                }
            }
        }

        $this->command->info('Dashboard demo data created successfully!');
    }
}