<?php

namespace App\Modules\BOK\Services;

use App\Models\Lpj;
use App\Models\Poa;

class NonParticipantPostingService
{
    /**
     * Hitung biaya non-peserta (snack/konsumsi/penggandaan, dll.) untuk 1 LPJ berdasarkan POA & RAB.
     * - Tidak mengubah LPJ; hanya menghitung alokasi per-bulan dari RAB item selain transport & uang harian.
     * - Mencegah double-posting: hanya kembalikan angka saat LPJ adalah SPPT (jika ada pasangan SPPD) atau satu-satunya LPJ.
     */
    public function computeForLpj(Lpj $lpj): float
    {
        // Temukan POA yang terkait dengan LPJ melalui schedule (sppt_lpj_id / sppd_lpj_id)
        $poa = $this->findPoaForLpj($lpj);
        if (!$poa) return 0.0;

        $schedule = $poa->schedule ?: [];
        $months = $schedule['months'] ?? [];
        $targetMonth = null;
        $monthMeta = null;
        foreach ($months as $m => $meta) {
            $sid = (int) ($meta['sppt_lpj_id'] ?? 0);
            $lid = (int) ($meta['sppd_lpj_id'] ?? 0);
            if ($sid === (int) $lpj->id || $lid === (int) $lpj->id) {
                $targetMonth = (int) $m;
                $monthMeta = $meta;
                break;
            }
        }
        if (!$monthMeta) return 0.0;

        // Hanya posting sekali per bulan klaim: prioritas SPPT
        $preferredId = (int) ($monthMeta['sppt_lpj_id'] ?? 0) ?: (int) ($monthMeta['sppd_lpj_id'] ?? 0);
        if ((int) $lpj->id !== $preferredId) return 0.0;

        $count = (int) ($monthMeta['count'] ?? 1);
        if ($count <= 0) $count = 1;

        // Tentukan total occurrences tahunan
        $totalOcc = (int) ($schedule['total_occurrences'] ?? 0);
        if ($totalOcc <= 0) {
            $totalOcc = $this->estimateOccurrencesFromRab($poa);
            if ($totalOcc <= 0) $totalOcc = 1;
        }

        // Kumpulkan data LPJ pasangannya untuk mengetahui total desa bulan ini
        $spptId = (int) ($monthMeta['sppt_lpj_id'] ?? 0);
        $sppdId = (int) ($monthMeta['sppd_lpj_id'] ?? 0);
        $sppt = $spptId ? Lpj::find($spptId) : null;
        $sppd = $sppdId ? Lpj::find($sppdId) : null;
        $monthlyDesa = 0;
        if ($sppt && (int)($sppt->jumlah_desa_darat ?? 0) > 0) $monthlyDesa += (int) $sppt->jumlah_desa_darat;
        if ($sppd && (int)($sppd->jumlah_desa_seberang ?? 0) > 0) $monthlyDesa += (int) $sppd->jumlah_desa_seberang;
        if ($monthlyDesa <= 0) $monthlyDesa = 1; // fallback aman

        // Jumlahkan subtotal item RAB yang bukan transport/uang_harian, dialokasikan per-bulan berdasarkan occ & desa
        $sum = 0.0;
        $exclude = (array) config('bok.ledger.exclude_types', []);
        $kw = (array) config('bok.ledger.include_label_keywords', []);
        $poa->loadMissing('rab.items');
        foreach ($poa->rab->items as $item) {
            $type = strtolower((string) ($item->type ?? ''));
            $label = strtolower((string) ($item->label ?? ''));
            $include = true;
            if ($type !== '' && in_array($type, $exclude, true)) {
                $include = false;
            }
            if ($type === '' && !empty($kw)) {
                $matched = false;
                foreach ($kw as $k) {
                    if ($k !== '' && str_contains($label, strtolower($k))) { $matched = true; break; }
                }
                $include = $matched;
            }
            if ($include) {
                $itemSubtotal = (float) $item->subtotal;
                $occRatio = $totalOcc > 0 ? min(1.0, $count / $totalOcc) : 1.0;
                // Skala berdasarkan faktor 'desa' pada item
                $desaFactorVal = 0;
                $factors = is_array($item->factors) ? $item->factors : [];
                foreach ($factors as $f) {
                    $fl = strtolower((string) ($f['label'] ?? $f['key'] ?? ''));
                    $fv = (int) round((float) ($f['value'] ?? 0));
                    if ($fv > 0 && str_contains($fl, 'desa')) { $desaFactorVal = max($desaFactorVal, $fv); }
                }
                $desaRatio = $desaFactorVal > 0 ? min(1.0, $monthlyDesa / $desaFactorVal) : 1.0;

                $sum += $itemSubtotal * $occRatio * $desaRatio;
            }
        }

        if ($sum <= 0) return 0.0;

        return round($sum, 2);
    }

    private function estimateOccurrencesFromRab(Poa $poa): int
    {
        $occ = 0;
        $poa->loadMissing('rab.items');
        foreach ($poa->rab->items as $item) {
            $factors = is_array($item->factors) ? $item->factors : [];
            foreach ($factors as $f) {
                $label = strtolower((string) ($f['label'] ?? $f['key'] ?? ''));
                $val = (int) round((float) ($f['value'] ?? 0));
                if ($val > 0 && str_contains($label, 'kali')) {
                    $occ = max($occ, $val);
                }
            }
        }
        return $occ;
    }

    private function findPoaForLpj(Lpj $lpj): ?Poa
    {
        // Cari POA dengan kegiatan sama, lalu cek apakah schedule berisi lpj_id ini
        $candidates = Poa::where('kegiatan', $lpj->kegiatan)->get();
        foreach ($candidates as $poa) {
            $schedule = $poa->schedule ?: [];
            $months = $schedule['months'] ?? [];
            foreach ($months as $meta) {
                if ((int) ($meta['sppt_lpj_id'] ?? 0) === (int) $lpj->id) return $poa;
                if ((int) ($meta['sppd_lpj_id'] ?? 0) === (int) $lpj->id) return $poa;
            }
        }
        // Fallback: cari dengan LIKE pada schedule JSON (tidak ideal namun non-invasif)
        $id = (int) $lpj->id;
        $poa = Poa::where('schedule', 'like', '%"sppt_lpj_id":'.$id.'%')
            ->orWhere('schedule', 'like', '%"sppd_lpj_id":'.$id.'%')
            ->first();
        return $poa ?: null;
    }
}
