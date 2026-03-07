<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RabMenu;
use App\Models\RabKegiatan;

class RabKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        // Mapping: Rincian Menu name => list of Kegiatan names
        $map = [
            'Pelaksanaan Kelas Ibu Hamil dan Kelas Ibu Balita' => [
                'Pelaksanaan Kelas Ibu Balita',
                'Pelaksanaan Kelas Ibu Hamil',
            ],
            'Kelas Ibu Balita' => [
                'Penyuluhan Gizi untuk Ibu Balita',
            ],
            'Kunjungan Rumah KIA' => [
                'Kunjungan Rumah Ibu Hamil Risiko Tinggi',
            ],
            'Inspeksi Kesehatan Lingkungan (Kesling)' => [
                'Inspeksi Kesling di Sarana Air Minum (SAM)',
                'Inspeksi Kesling di Fasilitas Umum',
            ],
            'Surveilans Penyakit Menular' => [
                'Penyelidikan Epidemiologi (PE)',
                'Surveilans TB di Desa',
            ],
            'Pembinaan Kesehatan di SD' => [
                'Penyuluhan PHBS di Sekolah',
            ],
            'Pemberian Makanan Tambahan (PMT) Balita' => [
                'PMT Balita Gizi Kurang',
            ],
            'PMT Ibu Hamil KEK' => [
                'PMT Ibu Hamil KEK',
            ],
            'Rapat Manajemen Puskesmas' => [
                'Rapat Lokakarya Mini Triwulan',
                'Rapat Tinjauan Manajemen',
            ],
            'Pelatihan Internal Pegawai' => [
                'Pelatihan Pencatatan dan Pelaporan',
            ],
            'Insentif UKM Bulanan' => [
                'Pembayaran Insentif UKM Bulanan',
            ],
            'Insentif Kader Posyandu' => [
                'Pembayaran Insentif Kader Posyandu',
            ],
        ];

        $menus = RabMenu::all()->keyBy('name');
        foreach ($map as $menuName => $kegiatans) {
            $menu = $menus->get($menuName);
            if (!$menu) continue;
            foreach ($kegiatans as $name) {
                RabKegiatan::firstOrCreate([
                    'rab_menu_id' => $menu->id,
                    'name' => $name,
                ]);
            }
        }
    }
}

