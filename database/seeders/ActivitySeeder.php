<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'kode' => 'BOK-001',
                'nama' => 'Pelayanan Kesehatan Dasar',
                'sumber_dana' => 'BOK',
            ],
            [
                'kode' => 'BOK-002',
                'nama' => 'Promosi Kesehatan dan Pemberdayaan Masyarakat',
                'sumber_dana' => 'BOK',
            ],
        ];

        foreach ($activities as $activity) {
            \App\Models\Activity::create($activity);
        }
    }
}
