<?php

namespace App\Services;

use App\Models\Lpj;
use App\Models\TibaBerangkat;
use App\Models\TibaBerangkatDetail;
use App\Models\PejabatTtd;
use App\Helpers\DateHelper;
use Carbon\Carbon;

class TibaBerangkatService
{
    /**
     * Create Tiba Berangkat from a pair of LPJs (SPPT + SPPD).
     * Returns the created TibaBerangkat or null on failure.
     */
    public function createFromLpjs(Lpj $sppt, Lpj $sppd): ?TibaBerangkat
    {
        try {
            // Ensure correct types order regardless of input
            if ($sppt->type !== 'SPPT' || $sppd->type !== 'SPPD') {
                // try swap
                if ($sppt->type === 'SPPD' && $sppd->type === 'SPPT') {
                    [$sppt, $sppd] = [$sppd, $sppt];
                } else {
                    return null;
                }
            }

            $spptDesas = $this->parseDesaList($sppt->desa_tujuan_darat ?? '');
            $sppdDesas = $this->parseDesaList($sppd->desa_tujuan_seberang ?? '');

            if (empty($spptDesas) && empty($sppdDesas)) {
                return null; // nothing to create
            }

            // Allocate dates
            $dates = $this->allocateDates($sppt, $spptDesas, $sppd, $sppdDesas);

            // Create Tiba Berangkat header
            $tb = TibaBerangkat::create([
                'no_surat' => $this->generateAutoNoSurat($sppt, $sppd),
                'created_by' => $sppt->created_by ?? $sppd->created_by ?? auth()->id(),
            ]);

            // Persist details for each desa with mapped pejabat and date
            foreach ($dates as $item) {
                [$desaName, $visitDate] = $item; // [string, Carbon]
                $pejabat = $this->findPejabatByDesa($desaName);
                if (!$pejabat) {
                    // Skip if no pejabat mapping; user can add later
                    continue;
                }
                $tb->details()->create([
                    'pejabat_ttd_id' => $pejabat->id,
                    'tanggal_kunjungan' => $visitDate->format('Y-m-d'),
                ]);
            }

            return $tb;
        } catch (\Throwable $e) {
            \Log::warning('Failed to auto-create TibaBerangkat', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create Tiba Berangkat from a single LPJ (SPPT or SPPD).
     * Uses only the available desa list and allocates sequential dates from tanggal_kegiatan.
     */
    public function createFromSingleLpj(Lpj $lpj): ?TibaBerangkat
    {
        try {
            $desas = [];
            if ($lpj->type === 'SPPT') {
                $desas = $this->parseDesaList($lpj->desa_tujuan_darat ?? '');
            } else {
                $desas = $this->parseDesaList($lpj->desa_tujuan_seberang ?? '');
            }
            if (empty($desas)) {
                return null; // nothing to create
            }

            // Determine start date from tanggal_kegiatan
            $range = \App\Helpers\DateHelper::parseDateRange($lpj->tanggal_kegiatan ?? '');
            $start = $range['start'] ?? now();

            // Create TB header
            $tb = TibaBerangkat::create([
                'no_surat' => $this->generateAutoNoSuratSingle($lpj),
                'created_by' => $lpj->created_by ?? auth()->id(),
            ]);

            if (!empty($range['days']) && is_array($range['days'])) {
                // Map explicit days to each desa for SPPT/SPPD
                foreach ($desas as $i => $desaName) {
                    $pejabat = $this->findPejabatByDesa($desaName);
                    if (!$pejabat) continue;
                    $date = $range['days'][$i] ?? $start->copy()->addDays($i);
                    $tb->details()->create([
                        'pejabat_ttd_id' => $pejabat->id,
                        'tanggal_kunjungan' => ($date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date))->format('Y-m-d'),
                    ]);
                }
            } else {
                foreach ($desas as $i => $desaName) {
                    $pejabat = $this->findPejabatByDesa($desaName);
                    if (!$pejabat) continue;
                    $tb->details()->create([
                        'pejabat_ttd_id' => $pejabat->id,
                        'tanggal_kunjungan' => $start->copy()->addDays($i)->format('Y-m-d'),
                    ]);
                }
            }

            return $tb;
        } catch (\Throwable $e) {
            \Log::warning('Failed to auto-create single TB', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function generateAutoNoSuratSingle(Lpj $lpj): string
    {
        $stamp = now()->format('Ymd-His');
        $no = trim((string) ($lpj->no_surat ?? ''));
        $kegiatan = trim((string) ($lpj->kegiatan ?? ''));
        if ($no !== '' && $kegiatan !== '') {
            $candidate = $no . ' ' . $kegiatan;
        } elseif ($no !== '') {
            $candidate = $no;
        } elseif ($kegiatan !== '') {
            $candidate = $kegiatan . ' ' . $stamp;
        } else {
            $candidate = 'TB-AUTO-' . $stamp;
        }
        $candidate = preg_replace('/\s+/', ' ', $candidate);
        if (TibaBerangkat::where('no_surat', $candidate)->exists()) {
            $candidate .= ' - ' . $stamp;
        }
        return $candidate;
    }

    private function generateAutoNoSurat(Lpj $sppt, Lpj $sppd): string
    {
        $stamp = now()->format('Ymd-His');
        $noSppt = trim((string) ($sppt->no_surat ?? ''));
        $noSppd = trim((string) ($sppd->no_surat ?? ''));

        // Build prefix from both numbers when available: e.g., "888-889"
        $prefix = '';
        if ($noSppt !== '' && $noSppd !== '') {
            $prefix = $noSppt . '-' . $noSppd;
        } elseif ($noSppd !== '') {
            $prefix = $noSppd;
        } elseif ($noSppt !== '') {
            $prefix = $noSppt;
        }

        // Append kegiatan if available: e.g., "888-889 Pembinaan Kesehatan di SD"
        $kegiatan = trim((string) ($sppd->kegiatan ?: $sppt->kegiatan ?: ''));
        if ($prefix !== '' && $kegiatan !== '') {
            $candidate = $prefix . ' ' . $kegiatan;
        } elseif ($prefix !== '') {
            $candidate = $prefix;
        } elseif ($kegiatan !== '') {
            $candidate = $kegiatan . ' ' . $stamp; // ensure uniqueness if only kegiatan exists
        } else {
            $candidate = 'TB-AUTO-' . $stamp;
        }

        // Normalize whitespace
        $candidate = preg_replace('/\s+/', ' ', $candidate);

        // Ensure uniqueness if needed
        if (TibaBerangkat::where('no_surat', $candidate)->exists()) {
            $candidate .= ' - ' . $stamp;
        }

        return $candidate;
    }

    /**
     * Parse comma/"dan" separated desa list, and normalize name (remove leading "Desa ").
     */
    public function parseDesaList(string $text): array
    {
        $text = trim($text);
        if ($text === '') return [];

        // Replace " dan " with comma for easier split
        $normalized = preg_replace('/\s+dan\s+/iu', ',', $text);
        // Replace ";" with comma, too
        $normalized = str_replace([';', ' ,', ', '], [',', ',', ','], $normalized);
        $parts = array_filter(array_map('trim', explode(',', $normalized)));
        $clean = [];
        foreach ($parts as $p) {
            // Remove leading label like "Desa xxx"
            $p = preg_replace('/^desa\s+/iu', '', $p);
            if ($p !== '') $clean[] = $p;
        }
        return $clean;
    }

    /**
     * Allocate sequential dates for SPPT and SPPD desa lists based on tanggal_kegiatan strings.
     * Returns array of [desaName, Carbon date] in order.
     */
    private function allocateDates(Lpj $sppt, array $spptDesas, Lpj $sppd, array $sppdDesas): array
    {
        $result = [];

        // Determine SPPT start date (from range/list/single). If none, use today.
        $spptRange = DateHelper::parseDateRange($sppt->tanggal_kegiatan ?? '');
        $spptStart = $spptRange['start'] ?? now();

        // If SPPT provides explicit list of dates (e.g., "14 dan 18 Juni 2025"), map exactly by index.
        $spptExplicitDays = isset($spptRange['days']) && is_array($spptRange['days']) ? $spptRange['days'] : [];
        if (!empty($spptExplicitDays)) {
            foreach ($spptDesas as $i => $desa) {
                $date = $spptExplicitDays[$i] ?? ($spptStart->copy()->addDays($i));
                $result[] = [$desa, $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date)];
            }
        } else {
            // Fallback: assign sequentially from start
            foreach ($spptDesas as $i => $desa) {
                $result[] = [$desa, $spptStart->copy()->addDays($i)];
            }
        }

        // Determine SPPD start date, prefer parsed start or next day after last SPPT
        $lastSpptDate = empty($result) ? $spptStart->copy()->subDay() : end($result)[1];
        $sppdRange = DateHelper::parseDateRange($sppd->tanggal_kegiatan ?? '');
        $sppdStart = $sppdRange['start'] ?? $lastSpptDate->copy()->addDay();
        if ($sppdStart->lessThanOrEqualTo($lastSpptDate)) {
            $sppdStart = $lastSpptDate->copy()->addDay();
        }
        $sppdExplicitDays = isset($sppdRange['days']) && is_array($sppdRange['days']) ? $sppdRange['days'] : [];
        if (!empty($sppdExplicitDays)) {
            foreach ($sppdDesas as $i => $desa) {
                $date = $sppdExplicitDays[$i] ?? ($sppdStart->copy()->addDays($i));
                $result[] = [$desa, $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date)];
            }
        } else {
            foreach ($sppdDesas as $i => $desa) {
                $result[] = [$desa, $sppdStart->copy()->addDays($i)];
            }
        }

        return $result;
    }

    /**
     * Find PejabatTtd by desa name (case-insensitive, ignoring leading "Desa ").
     */
    private function findPejabatByDesa(string $desa): ?PejabatTtd
    {
        $desa = trim($desa);
        if ($desa === '') return null;
        $desa = preg_replace('/^desa\s+/iu', '', $desa);
        // Try exact case-insensitive match
        $found = PejabatTtd::whereRaw('LOWER(desa) = ?', [strtolower($desa)])->first();
        if ($found) return $found;
        // Try contains
        return PejabatTtd::where('desa', 'LIKE', "%{$desa}%")->first();
    }
}
