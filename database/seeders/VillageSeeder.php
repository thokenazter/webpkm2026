<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $villages = [
            // 2 desa darat
            [
                'nama' => 'Desa Maju Jaya',
                'kecamatan' => 'Kecamatan Tengah',
                'akses' => 'DARAT',
                'transport_standard' => 70000.00,
            ],
            [
                'nama' => 'Desa Sumber Rejeki',
                'kecamatan' => 'Kecamatan Selatan',
                'akses' => 'DARAT',
                'transport_standard' => 70000.00,
            ],
            // 3 desa seberang
            [
                'nama' => 'Desa Pulau Indah',
                'kecamatan' => 'Kecamatan Utara',
                'akses' => 'SEBERANG',
                'transport_standard' => 70000.00,
            ],
            [
                'nama' => 'Desa Pantai Baru',
                'kecamatan' => 'Kecamatan Timur',
                'akses' => 'SEBERANG',
                'transport_standard' => 70000.00,
            ],
            [
                'nama' => 'Desa Muara Harapan',
                'kecamatan' => 'Kecamatan Barat',
                'akses' => 'SEBERANG',
                'transport_standard' => 70000.00,
            ],
        ];

        foreach ($villages as $village) {
            \App\Models\Village::create($village);
        }
    }
}
