<?php

namespace Database\Seeders;

use App\Models\Cluster;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data berdasarkan SK Kepala Puskesmas dan tabel penugasan pegawai.
     */
    public function run(): void
    {
        // Get cluster IDs
        $manajemen = Cluster::where('slug', 'manajemen-dukungan')->first();
        $kia = Cluster::where('slug', 'kia-remaja')->first();
        $dewasaLansia = Cluster::where('slug', 'dewasa-lansia')->first();
        $p2pKesling = Cluster::where('slug', 'p2p-kesling')->first();
        $lintasKlaster = Cluster::where('slug', 'lintas-klaster')->first();

        // Kepala Puskesmas (Leadership - no cluster)
        Staff::create([
            'name' => 'Ns. Makdalena Ilely, S.Kep',
            'role' => 'Kepala Puskesmas',
            'cluster_id' => null,
            'photo' => '/images/staff/makdalena.jpg',
            'is_leader' => true,
            'order' => 1,
        ]);

        // Klaster I Manajemen
        $manajemenStaff = [
            ['name' => 'Onalin E.E. Habibuw, S.Kep., Ners', 'role' => 'Penanggung Jawab Klaster Manajemen', 'photo' => '/images/staff/onalin.jpg', 'is_leader' => true, 'order' => 1],
            ['name' => 'Thobias Edwin Dasmaselah, S.KM', 'role' => 'Admin', 'photo' => '/images/staff/thobias.jpg', 'is_leader' => false, 'order' => 2],
        ];

        foreach ($manajemenStaff as $staff) {
            Staff::create(array_merge($staff, ['cluster_id' => $manajemen->id]));
        }

        // Klaster II KIA
        $kiaStaff = [
            ['name' => 'Kardioka Silaban, A.Md.Keb', 'role' => 'Penanggung Jawab Klaster KIA', 'photo' => '/images/staff/kardioka.jpg', 'is_leader' => true, 'order' => 1],
            ['name' => 'Istika Sari Barend, A.Md.Keb', 'role' => 'Bidan Pelaksana', 'photo' => '/images/staff/istika.jpg', 'is_leader' => false, 'order' => 2],
            ['name' => 'Rahima, A.Md.Keb', 'role' => 'Bidan Pelaksana', 'photo' => '/images/staff/rahima.jpg', 'is_leader' => false, 'order' => 3],
            ['name' => 'Gilyan Terri, A.Md.Gz', 'role' => 'Tenaga Gizi', 'photo' => '/images/staff/gilyan.jpg', 'is_leader' => false, 'order' => 4],
        ];

        foreach ($kiaStaff as $staff) {
            Staff::create(array_merge($staff, ['cluster_id' => $kia->id]));
        }

        // Klaster III Dewasa & Lansia
        // PJ: Ns. Makdalena Ilely (sama dengan Kepala Puskesmas, tidak perlu entry terpisah)
        // Tanggung jawab ruangan Klaster 3 di-assign via ResponsibilitySeeder

        // Placeholder to keep cluster active (no staff needed since PJ = Kepala Puskesmas)
        // If other staff are added to this cluster later, add them here
        $dewasaLansiaStaff = [];

        foreach ($dewasaLansiaStaff as $staff) {
            Staff::create(array_merge($staff, ['cluster_id' => $dewasaLansia->id]));
        }

        // Klaster IV P2P & Kesling
        $p2pKeslingStaff = [
            ['name' => 'Jacob Galandjindjinay, S.Kep., Ners', 'role' => 'Penanggung Jawab Klaster P2P & Kesling', 'photo' => '/images/staff/jacob.jpg', 'is_leader' => true, 'order' => 1],
            ['name' => 'Cindi Claudia Latusanay, A.Md.Kes', 'role' => 'Sanitarian', 'photo' => '/images/staff/cindi.jpg', 'is_leader' => false, 'order' => 2],
        ];

        foreach ($p2pKeslingStaff as $staff) {
            Staff::create(array_merge($staff, ['cluster_id' => $p2pKesling->id]));
        }

        // Klaster V Lintas Klaster
        $lintasKlasterStaff = [
            ['name' => 'Amos N. Djabutafuan, S.Kep., Ners', 'role' => 'PJ Lintas Klaster / Perawat IGD', 'photo' => '/images/staff/amos.jpg', 'is_leader' => true, 'order' => 1],
            ['name' => 'dr. Rahmatan', 'role' => 'Dokter Umum (Pemeriksaan)', 'photo' => '/images/staff/rahmatan.jpg', 'is_leader' => false, 'order' => 2],
            ['name' => 'Nunuk Puspaningrum, A.Md.AK', 'role' => 'Analis Laboratorium', 'photo' => '/images/staff/nunuk.jpg', 'is_leader' => false, 'order' => 3],
            ['name' => 'Irene Ngarbinan, A.Md.Kep', 'role' => 'Perawat - Petugas Apotik & Obat', 'photo' => '/images/staff/irene-n.jpg', 'is_leader' => false, 'order' => 4],
            ['name' => 'Apt. Ardiansah, S.Farm', 'role' => 'Apoteker', 'photo' => '/images/staff/ardiansah.jpg', 'is_leader' => false, 'order' => 5],
            ['name' => 'Yolanda Boger, A.Md.Kep', 'role' => 'Perawat', 'photo' => '/images/staff/yolanda.jpg', 'is_leader' => false, 'order' => 6],
            ['name' => 'Waode Kurniati Jan Jan, A.Md.Keb', 'role' => 'Bidan Pelaksana', 'photo' => '/images/staff/waode.jpg', 'is_leader' => false, 'order' => 7],
            // Onalin sudah ada di Klaster Manajemen (PJ), tidak perlu duplikat
            ['name' => 'Irene Fordatkosu, S.KM', 'role' => 'Administrasi Kesehatan', 'photo' => '/images/staff/irene-f.jpg', 'is_leader' => false, 'order' => 8],
            ['name' => 'Since Korsen, A.Md.Kep', 'role' => 'Perawat', 'photo' => '/images/staff/since.jpg', 'is_leader' => false, 'order' => 10],
            ['name' => 'Hetreda Ketno, S.Kep., Ners', 'role' => 'Perawat Ners', 'photo' => '/images/staff/hetreda.jpg', 'is_leader' => false, 'order' => 11],
        ];

        foreach ($lintasKlasterStaff as $staff) {
            Staff::create(array_merge($staff, ['cluster_id' => $lintasKlaster->id]));
        }

        // Pegawai Pustu Kumul (tanpa klaster)
        Staff::create([
            'name' => 'Margareta Mangar, A.Md.Keb',
            'role' => 'Bidan Pustu Kumul',
            'cluster_id' => null,
            'photo' => '/images/staff/margareta.jpg',
            'is_leader' => false,
            'order' => 2,
        ]);
    }
}
