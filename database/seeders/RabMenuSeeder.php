<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RabMenu;
use App\Models\Rab;

class RabMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Menus per component (key per Rab::components())
        $menusByComponent = [
            'komp1' => [
                'Pelaksanaan Kelas Ibu Hamil dan Kelas Ibu Balita',
                'Kelas Ibu Balita',
                'Kunjungan Rumah KIA',
            ],
            'komp2' => [
                'Inspeksi Kesehatan Lingkungan (Kesling)',
                'Surveilans Penyakit Menular',
                'Pembinaan Kesehatan di SD',
            ],
            'komp3' => [
                'Pemberian Makanan Tambahan (PMT) Balita',
                'PMT Ibu Hamil KEK',
            ],
            'komp4' => [
                'Rapat Manajemen Puskesmas',
                'Pelatihan Internal Pegawai',
            ],
            'komp5' => [
                'Insentif UKM Bulanan',
                'Insentif Kader Posyandu',
            ],
        ];

        $validComponents = array_keys(Rab::components());

        foreach ($menusByComponent as $componentKey => $menus) {
            if (!in_array($componentKey, $validComponents, true)) continue;
            foreach ($menus as $name) {
                RabMenu::firstOrCreate([
                    'component_key' => $componentKey,
                    'name' => $name,
                ]);
            }
        }
    }
}

