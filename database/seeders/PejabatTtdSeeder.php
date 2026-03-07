<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PejabatTtdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pejabats = [
            [
                'nama' => 'H. Ahmad Suryadi',
                'desa' => 'Desa Kabalsiang',
                'jabatan' => 'Kepala Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Hj. Siti Aminah',
                'desa' => 'Desa Benjuring',
                'jabatan' => 'Kepala Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bapak Ruslan',
                'desa' => 'Desa Kumul',
                'jabatan' => 'Kepala Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ibu Fatimah',
                'desa' => 'Desa Sungai Raya',
                'jabatan' => 'Kepala Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bapak Hendri',
                'desa' => 'Desa Teluk Bayur',
                'jabatan' => 'Kepala Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Drs. Muhammad Ali',
                'desa' => 'Desa Kabalsiang',
                'jabatan' => 'Sekretaris Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'S.Pd. Nurhayati',
                'desa' => 'Desa Benjuring',
                'jabatan' => 'Sekretaris Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\PejabatTtd::insert($pejabats);
    }
}
