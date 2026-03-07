<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\StaffResponsibility;
use Illuminate\Database\Seeder;

class ResponsibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data berdasarkan SK Kepala Puskesmas (SK-PJ.md)
     */
    public function run(): void
    {
        // Helper: find staff by partial name match
        $findStaff = function (string $name) {
            return Staff::where('name', 'LIKE', "%{$name}%")->first()?->id;
        };

        // Staff IDs
        $makdalena   = $findStaff('Makdalena Ilely');
        $onalin      = $findStaff('Onalin');
        $thobias     = $findStaff('Thobias');
        $cindi       = $findStaff('Cindi');
        $kardioka    = $findStaff('Kardioka');
        $istika      = $findStaff('Istika');
        $rahima      = $findStaff('Rahima');
        $gilyan      = $findStaff('Gilyan');
        // Ns. M. Ilely = Ns. Makdalena Ilely (orang yang sama, gunakan $makdalena)
        $jacob       = $findStaff('Jacob');
        $rahmatan    = $findStaff('Rahmatan');
        $amos        = $findStaff('Amos');
        $nunuk       = $findStaff('Nunuk');
        $ireneN      = $findStaff('Irene Ngarbinan');
        $ardiansah   = $findStaff('Ardiansah');
        $yolanda     = $findStaff('Yolanda');
        $waode       = $findStaff('Waode');
        $ireneF      = $findStaff('Irene Fordatkosu');
        $since       = $findStaff('Since');
        $hetreda     = $findStaff('Hetreda');
        $margareta   = $findStaff('Margareta');

        // ==========================================
        // ADMIN APLIKASI (17 entries)
        // ==========================================
        $adminApps = [
            ['staff_id' => $cindi, 'title' => 'P-Care'],
            ['staff_id' => $thobias, 'title' => 'ASPAK'],
            ['staff_id' => $thobias, 'title' => 'DFO'],
            ['staff_id' => $makdalena, 'title' => 'SISDMK'],
            ['staff_id' => $thobias, 'title' => 'SISDMK'],
            ['staff_id' => $jacob, 'title' => 'INM & IKP'],
            ['staff_id' => $kardioka, 'title' => 'INM & IKP'],
            ['staff_id' => $nunuk, 'title' => 'INM & IKP'],
            ['staff_id' => $kardioka, 'title' => 'E-Kohort, NPDM'],
            ['staff_id' => $jacob, 'title' => 'SIMKESWA'],
            ['staff_id' => $rahima, 'title' => 'SISRUTE'],
            ['staff_id' => $makdalena, 'title' => 'E-RENGGAR'],
            ['staff_id' => $thobias, 'title' => 'E-RENGGAR'],
            ['staff_id' => $makdalena, 'title' => 'SIPD'],
            ['staff_id' => $thobias, 'title' => 'SIPD'],
            ['staff_id' => $cindi, 'title' => 'SIPD'],
            ['staff_id' => $makdalena, 'title' => 'BNI Direct'],
            ['staff_id' => $thobias, 'title' => 'BNI Direct'],
            ['staff_id' => $makdalena, 'title' => 'KRISNA'],
            ['staff_id' => $thobias, 'title' => 'KRISNA'],
            ['staff_id' => $makdalena, 'title' => 'RENBUT'],
            ['staff_id' => $thobias, 'title' => 'RENBUT'],
            ['staff_id' => $cindi, 'title' => 'HFIS'],
            ['staff_id' => $thobias, 'title' => 'MICROSITE'],
            ['staff_id' => $gilyan, 'title' => 'EPPGBM'],
            ['staff_id' => $ireneF, 'title' => 'RME'],
        ];

        foreach ($adminApps as $item) {
            if ($item['staff_id']) {
                StaffResponsibility::create([
                    'staff_id' => $item['staff_id'],
                    'category' => 'admin_app',
                    'title' => $item['title'],
                ]);
            }
        }

        // ==========================================
        // KOORDINATOR (6 entries)
        // ==========================================
        $koordinators = [
            ['staff_id' => $kardioka, 'title' => 'Bidan Koordinator'],
            ['staff_id' => $makdalena, 'title' => 'Perawat Koordinator'],
            ['staff_id' => $kardioka, 'title' => 'Koordinator UKM'],
            ['staff_id' => $thobias, 'title' => 'Koordinator Admin'],
            ['staff_id' => $makdalena, 'title' => 'Koordinator Jaringan'],
            ['staff_id' => $cindi, 'title' => 'Koordinator SP2TP'],
        ];

        foreach ($koordinators as $item) {
            if ($item['staff_id']) {
                StaffResponsibility::create([
                    'staff_id' => $item['staff_id'],
                    'category' => 'koordinator',
                    'title' => $item['title'],
                ]);
            }
        }

        // ==========================================
        // LAPORAN SP2TP (8 entries, some shared)
        // ==========================================
        $laporans = [
            ['staff_id' => $rahmatan, 'title' => 'LB1'],
            ['staff_id' => $ardiansah, 'title' => 'LB2'],
            ['staff_id' => $istika, 'title' => 'LB3'],
            ['staff_id' => $nunuk, 'title' => 'LB4'],
            ['staff_id' => $gilyan, 'title' => 'LB5'],
            ['staff_id' => $rahmatan, 'title' => '10 Penyakit Terbesar'],
            ['staff_id' => $rahima, 'title' => 'Rujukan'],
            ['staff_id' => $amos, 'title' => 'Rujukan'],
            ['staff_id' => $cindi, 'title' => 'Klem BPJS'],
        ];

        foreach ($laporans as $item) {
            if ($item['staff_id']) {
                StaffResponsibility::create([
                    'staff_id' => $item['staff_id'],
                    'category' => 'laporan',
                    'title' => $item['title'],
                ]);
            }
        }

        // ==========================================
        // JARINGAN PELAYANAN (2 entries)
        // ==========================================
        $jaringans = [
            // POD Kompane - belum ditetapkan
            ['staff_id' => $since, 'title' => 'Pustu Kumul'],
        ];

        foreach ($jaringans as $item) {
            if ($item['staff_id']) {
                StaffResponsibility::create([
                    'staff_id' => $item['staff_id'],
                    'category' => 'jaringan',
                    'title' => $item['title'],
                ]);
            }
        }

        // ==========================================
        // RUANGAN (12 entries)
        // ==========================================
        $ruangans = [
            ['staff_id' => $onalin, 'title' => 'Klaster 1 Manajemen'],
            ['staff_id' => $rahmatan, 'title' => 'Ruangan Pemeriksaan Klaster 1 dan 2'],
            ['staff_id' => $kardioka, 'title' => 'Klaster 2 KIA'],
            ['staff_id' => $makdalena, 'title' => 'Klaster 3 Dewasa & Lansia'],
            ['staff_id' => $jacob, 'title' => 'Klaster 4 P2P dan Kesling'],
            ['staff_id' => $amos, 'title' => 'Klaster 5 IGD'],
            ['staff_id' => $nunuk, 'title' => 'Klaster 5 Laboratorium'],
            ['staff_id' => $ireneN, 'title' => 'Klaster 5 Apotik dan Gudang Obat'],
            ['staff_id' => $yolanda, 'title' => 'Gudang'],
            ['staff_id' => $waode, 'title' => 'Klaster 5 Persalinan'],
            ['staff_id' => $makdalena, 'title' => 'Ruangan Kepala Puskesmas'],
            ['staff_id' => $ireneF, 'title' => 'Auditorium'],
        ];

        foreach ($ruangans as $item) {
            if ($item['staff_id']) {
                StaffResponsibility::create([
                    'staff_id' => $item['staff_id'],
                    'category' => 'ruangan',
                    'title' => $item['title'],
                ]);
            }
        }

        // ==========================================
        // PJ PROGRAM (29 entries)
        // ==========================================
        $programs = [
            ['staff_id' => $kardioka, 'title' => 'KIA'],
            ['staff_id' => $istika, 'title' => 'KB'],
            ['staff_id' => $waode, 'title' => 'PosRem'],
            ['staff_id' => $nunuk, 'title' => 'Hepatitis'],
            ['staff_id' => $nunuk, 'title' => 'HIV'],
            ['staff_id' => $nunuk, 'title' => 'TB'],
            ['staff_id' => $waode, 'title' => 'Tumbang'],
            ['staff_id' => $waode, 'title' => 'MTBS'],
            ['staff_id' => $gilyan, 'title' => 'Gizi'],
            ['staff_id' => $rahima, 'title' => 'Imunisasi'],
            ['staff_id' => $ireneF, 'title' => 'Imunisasi'],
            ['staff_id' => $thobias, 'title' => 'Promkes'],
            ['staff_id' => $onalin, 'title' => 'UKS'],
            ['staff_id' => $jacob, 'title' => 'KesWa'],
            ['staff_id' => $cindi, 'title' => 'Kesling'],
            ['staff_id' => $cindi, 'title' => 'K3'],
            ['staff_id' => $amos, 'title' => 'Kes. Lansia'],
            ['staff_id' => $onalin, 'title' => 'Kesorga'],
            ['staff_id' => $yolanda, 'title' => 'Malaria'],
            ['staff_id' => $ireneN, 'title' => 'Malaria'],
            ['staff_id' => $ireneN, 'title' => 'ISPA & Diare'],
            ['staff_id' => $makdalena, 'title' => 'PTM'],
            ['staff_id' => $hetreda, 'title' => 'PTM'],
            ['staff_id' => $jacob, 'title' => 'Surveilans'],
            ['staff_id' => $jacob, 'title' => 'KUSTA'],
            ['staff_id' => $amos, 'title' => 'PerKesMas'],
            ['staff_id' => $yolanda, 'title' => 'TOGA'],
            ['staff_id' => $ireneN, 'title' => 'POPM Kecacingan'],
            ['staff_id' => $amos, 'title' => 'PAGHBTB'],
            ['staff_id' => $hetreda, 'title' => 'Skrining BPJS'],
            ['staff_id' => $ireneF, 'title' => 'Skrining BPJS'],
            ['staff_id' => $margareta, 'title' => 'Pustu Kumul'],
        ];

        foreach ($programs as $item) {
            if ($item['staff_id']) {
                StaffResponsibility::create([
                    'staff_id' => $item['staff_id'],
                    'category' => 'program',
                    'title' => $item['title'],
                ]);
            }
        }
    }
}
