<?php

namespace Database\Seeders;

use App\Models\Cluster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clusters = [
            [
                'name' => 'Manajemen dan Dukungan',
                'slug' => 'manajemen-dukungan',
                'description' => 'Klaster yang menangani tata kelola administrasi, kepegawaian, keuangan, perencanaan, dan manajemen mutu Puskesmas.',
                'icon' => 'settings',
                'color' => 'blue',
                'order' => 1,
            ],
            [
                'name' => 'Kesehatan Ibu, Anak, dan Remaja',
                'slug' => 'kia-remaja',
                'description' => 'Klaster pelayanan kesehatan untuk ibu hamil, bersalin, nifas, bayi, balita, anak prasekolah, anak usia sekolah, dan remaja.',
                'icon' => 'baby',
                'color' => 'pink',
                'order' => 2,
            ],
            [
                'name' => 'Dewasa dan Lansia',
                'slug' => 'dewasa-lansia',
                'description' => 'Klaster pelayanan kesehatan untuk usia dewasa dan lanjut usia, termasuk penanganan penyakit tidak menular (PTM) dan kesehatan jiwa.',
                'icon' => 'users',
                'color' => 'amber',
                'order' => 3,
            ],
            [
                'name' => 'Penanggulangan Penyakit dan Kesehatan Lingkungan',
                'slug' => 'p2p-kesling',
                'description' => 'Klaster untuk penanggulangan penyakit menular, surveilans epidemiologi, imunisasi, dan pengawasan kualitas lingkungan.',
                'icon' => 'shield-check',
                'color' => 'red',
                'order' => 4,
            ],
            [
                'name' => 'Lintas Klaster',
                'slug' => 'lintas-klaster',
                'description' => 'Pelayanan penunjang klinis yang mendukung semua klaster, meliputi Unit Gawat Darurat (UGD), Kefarmasian, Laboratorium, dan Rawat Inap.',
                'icon' => 'layers',
                'color' => 'primary',
                'order' => 5,
            ],
        ];

        foreach ($clusters as $cluster) {
            Cluster::create($cluster);
        }
    }
}
