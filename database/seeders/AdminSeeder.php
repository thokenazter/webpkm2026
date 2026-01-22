<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@puskesmas.go.id'],
            [
                'name' => 'Administrator',
                'email' => 'admin@puskesmas.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create default categories for Berita
        $beritaCategories = [
            ['name' => 'Pengumuman', 'slug' => 'pengumuman', 'type' => 'berita', 'description' => 'Pengumuman resmi Puskesmas'],
            ['name' => 'Kegiatan', 'slug' => 'kegiatan', 'type' => 'berita', 'description' => 'Berita kegiatan Puskesmas'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'type' => 'berita', 'description' => 'Tips dan informasi kesehatan'],
            ['name' => 'Layanan', 'slug' => 'layanan', 'type' => 'berita', 'description' => 'Informasi layanan Puskesmas'],
        ];

        foreach ($beritaCategories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create default categories for Galeri
        $galeriCategories = [
            ['name' => 'Posyandu', 'slug' => 'posyandu', 'type' => 'galeri', 'description' => 'Kegiatan Posyandu'],
            ['name' => 'Imunisasi', 'slug' => 'imunisasi', 'type' => 'galeri', 'description' => 'Kegiatan Imunisasi'],
            ['name' => 'Penyuluhan', 'slug' => 'penyuluhan', 'type' => 'galeri', 'description' => 'Kegiatan Penyuluhan Kesehatan'],
            ['name' => 'Kunjungan', 'slug' => 'kunjungan', 'type' => 'galeri', 'description' => 'Kunjungan dan Monitoring'],
            ['name' => 'Acara', 'slug' => 'acara', 'type' => 'galeri', 'description' => 'Acara dan Peringatan'],
        ];

        foreach ($galeriCategories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Admin user created: admin@puskesmas.go.id / admin123');
        $this->command->info('Default categories created successfully!');
    }
}
