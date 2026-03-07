<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Rab;
use App\Models\RabItem;
use App\Models\RabMenu;
use App\Models\RabKegiatan;
use App\Models\User;
use App\Models\Activity;

class RabSeeder extends Seeder
{
    public function run(): void
    {
        $userId = optional(User::first())->id;
        $components = Rab::components();

        // Helper to add items with auto subtotal and accumulate total
        $createWithItems = function (Rab $rab, array $items): void {
            $total = 0;
            foreach ($items as $item) {
                $factors = $item['factors'] ?? [];
                $normalized = [];
                foreach ($factors as $f) {
                    $normalized[] = [
                        'key' => $f['key'] ?? ($f['label'] ?? ''),
                        'label' => $f['label'] ?? ($f['key'] ?? ''),
                        'value' => isset($f['value']) ? (float) $f['value'] : 0,
                    ];
                }
                $ri = new RabItem([
                    'label' => $item['label'],
                    'type' => $item['type'] ?? null,
                    'factors' => $normalized,
                    'unit_price' => (float) $item['unit_price'],
                ]);
                $ri->subtotal = $ri->computeSubtotal();
                $rab->items()->save($ri);
                $total += $ri->subtotal;
            }
            $rab->total = $total;
            $rab->save();
        };

        // RAB 1: Inspeksi Kesling di SAM (komp2)
        $menu1 = RabMenu::where('name', 'Inspeksi Kesehatan Lingkungan (Kesling)')->first();
        $keg1 = RabKegiatan::whereHas('menu', fn($q) => $q->where('id', optional($menu1)->id))
            ->where('name', 'Inspeksi Kesling di Sarana Air Minum (SAM)')
            ->first();

        if ($menu1 && $keg1) {
            $kompName = $components['komp2'] ?? 'Surveilans, respons penyakit dan kesehatan lingkungan';
            $rab = Rab::firstOrCreate([
                'kegiatan' => $keg1->name,
                'rincian_menu' => $menu1->name,
                'komponen' => $kompName,
            ], [
                'rab_menu_id' => $menu1->id,
                'rab_kegiatan_id' => $keg1->id,
                'metadata' => [],
                'created_by' => $userId,
            ]);

            if ($rab->wasRecentlyCreated || $rab->items()->count() === 0) {
                // Clear old items if any, then seed
                $rab->items()->delete();
                $createWithItems($rab, [
                    [
                        'label' => 'Transport Darat',
                        'type' => 'transport_darat',
                        'unit_price' => 70000,
                        'factors' => [
                            ['label' => 'Orang', 'value' => 2],
                            ['label' => 'Hari', 'value' => 1],
                            ['label' => 'Desa', 'value' => 2],
                            ['label' => 'Kali Kegiatan', 'value' => 12],
                        ],
                    ],
                    [
                        'label' => 'Transport Laut/Seberang',
                        'type' => 'transport_laut',
                        'unit_price' => 70000,
                        'factors' => [
                            ['label' => 'Orang', 'value' => 2],
                            ['label' => 'Hari', 'value' => 1],
                            ['label' => 'Desa', 'value' => 3],
                            ['label' => 'Kali Kegiatan', 'value' => 12],
                        ],
                    ],
                    [
                        'label' => 'Uang Harian',
                        'type' => 'uang_harian',
                        'unit_price' => 150000,
                        'factors' => [
                            ['label' => 'Orang', 'value' => 2],
                            ['label' => 'Hari', 'value' => 1],
                            ['label' => 'Desa', 'value' => 3],
                            ['label' => 'Kali Kegiatan', 'value' => 12],
                        ],
                    ],
                    [
                        'label' => 'Snack',
                        'type' => 'snack',
                        'unit_price' => 24000,
                        'factors' => [
                            ['label' => 'Dos', 'value' => 15],
                            ['label' => 'Desa', 'value' => 5],
                            ['label' => 'Kali Kegiatan', 'value' => 12],
                        ],
                    ],
                    [
                        'label' => 'Penggandaan Bahan',
                        'type' => 'penggandaan',
                        'unit_price' => 750,
                        'factors' => [
                            ['label' => 'Lembar', 'value' => 24],
                            ['label' => 'Desa', 'value' => 3],
                            ['label' => 'Kali Kegiatan', 'value' => 3],
                        ],
                    ],
                ]);
            }

            // Pastikan Activity tersedia untuk nama kegiatan ini
            Activity::firstOrCreate(['nama' => $rab->kegiatan], ['nama' => $rab->kegiatan]);
        }

        // RAB 2: Pelaksanaan Kelas Ibu Balita (komp1)
        $menu2 = RabMenu::where('name', 'Pelaksanaan Kelas Ibu Hamil dan Kelas Ibu Balita')->first();
        $keg2 = RabKegiatan::whereHas('menu', fn($q) => $q->where('id', optional($menu2)->id))
            ->where('name', 'Pelaksanaan Kelas Ibu Balita')
            ->first();

        if ($menu2 && $keg2) {
            $kompName = $components['komp1'] ?? 'Peningkatan Layanan Kesehatan Sesuai Siklus Hidup';
            $rab2 = Rab::firstOrCreate([
                'kegiatan' => $keg2->name,
                'rincian_menu' => $menu2->name,
                'komponen' => $kompName,
            ], [
                'rab_menu_id' => $menu2->id,
                'rab_kegiatan_id' => $keg2->id,
                'metadata' => [],
                'created_by' => $userId,
            ]);

            if ($rab2->wasRecentlyCreated || $rab2->items()->count() === 0) {
                $rab2->items()->delete();
                $createWithItems($rab2, [
                    [
                        'label' => 'Transport Darat',
                        'type' => 'transport_darat',
                        'unit_price' => 70000,
                        'factors' => [
                            ['label' => 'Orang', 'value' => 2],
                            ['label' => 'Hari', 'value' => 1],
                            ['label' => 'Desa', 'value' => 2],
                            ['label' => 'Kali Kegiatan', 'value' => 12],
                        ],
                    ],
                    [
                        'label' => 'Uang Harian',
                        'type' => 'uang_harian',
                        'unit_price' => 150000,
                        'factors' => [
                            ['label' => 'Orang', 'value' => 2],
                            ['label' => 'Hari', 'value' => 1],
                            ['label' => 'Desa', 'value' => 2],
                            ['label' => 'Kali Kegiatan', 'value' => 12],
                        ],
                    ],
                    [
                        'label' => 'Konsumsi',
                        'type' => 'konsumsi',
                        'unit_price' => 59000,
                        'factors' => [
                            ['label' => 'Porsi', 'value' => 20],
                            ['label' => 'Desa', 'value' => 2],
                            ['label' => 'Kali Kegiatan', 'value' => 12],
                        ],
                    ],
                ]);
            }

            Activity::firstOrCreate(['nama' => $rab2->kegiatan], ['nama' => $rab2->kegiatan]);
        }
    }
}

