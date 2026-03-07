<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Mapping bulan Indonesia ke bahasa Inggris
     */
    private static $monthMap = [
        'Januari' => 'January',
        'Februari' => 'February', 
        'Maret' => 'March',
        'April' => 'April',
        'Mei' => 'May',
        'Juni' => 'June',
        'Juli' => 'July',
        'Agustus' => 'August',
        'September' => 'September',
        'Oktober' => 'October',
        'November' => 'November',
        'Desember' => 'December'
    ];

    private static $monthMapLower = null; // built on first use

    private static function indoMonthToEnglish(string $token): string
    {
        if (self::$monthMapLower === null) {
            self::$monthMapLower = [];
            foreach (self::$monthMap as $indo => $eng) {
                self::$monthMapLower[mb_strtolower($indo, 'UTF-8')] = $eng;
            }
            // Common Indonesian month abbreviations
            $abbr = [
                'jan' => 'January',
                'feb' => 'February',
                'mar' => 'March',
                'apr' => 'April',
                'mei' => 'May',
                'jun' => 'June',
                'jul' => 'July',
                'agu' => 'August',
                'sep' => 'September',
                'okt' => 'October',
                'nov' => 'November',
                'des' => 'December',
            ];
            foreach ($abbr as $k => $v) self::$monthMapLower[$k] = $v;
        }
        $key = mb_strtolower(trim($token), 'UTF-8');
        return self::$monthMapLower[$key] ?? $token; // return original if not matched
    }

    /**
     * Parse tanggal kegiatan manual ke format Carbon
     * 
     * @param string $dateString Format: "27 s/d 29 Agustus 2025" atau "26 Januari 2025"
     * @return Carbon|null
     */
    public static function parseActivityDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            // Bersihkan string dari karakter yang tidak perlu
            $dateString = trim($dateString);
            
            // Pattern 1: "DD s/d DD Bulan YYYY" or variations: s/d, s.d., sd, hyphen, or "dan"
            if (preg_match('/(\d{1,2})\s*(?:s\/?d|s\.?d\.?|-|sd|dan)\s*(\d{1,2})\s+(\w+)\s+(\d{4})/iu', $dateString, $matches)) {
                $startDay = $matches[1];
                $endDay = $matches[2];
                $month = self::indoMonthToEnglish($matches[3]);
                $year = $matches[4];
                
                // Gunakan tanggal mulai untuk trend
                return Carbon::createFromFormat('j F Y', "$startDay $month $year");
            }
            
            // Pattern 2: explicit list: "14 dan 18 Mei 2025" or "14, 18, 20 Mei 2025"
            if (preg_match('/^([\d\s,dan]+)\s+(\w+)\s+(\d{4})$/iu', $dateString, $matches)) {
                if (preg_match('/\d{1,2}/u', $matches[1], $dm)) {
                    $firstDay = (int) $dm[0];
                    $month = self::indoMonthToEnglish($matches[2]);
                    $year = $matches[3];
                    return Carbon::createFromFormat('j F Y', "$firstDay $month $year");
                }
            }

            // Pattern 3: "DD Bulan YYYY"
            if (preg_match('/(\d{1,2})\s+(\w+)\s+(\d{4})/iu', $dateString, $matches)) {
                $day = $matches[1];
                $month = self::indoMonthToEnglish($matches[2]);
                $year = $matches[3];
                
                return Carbon::createFromFormat('j F Y', "$day $month $year");
            }
            
            // Pattern 4: Format DD-MM-YYYY atau DD/MM/YYYY
            if (preg_match('/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})/', $dateString, $matches)) {
                $day = $matches[1];
                $month = $matches[2];
                $year = $matches[3];
                
                return Carbon::createFromFormat('d-m-Y', "$day-$month-$year");
            }
            
            return null;
            
        } catch (\Exception $e) {
            // Log error tapi jangan crash
            \Log::warning("Failed to parse activity date: $dateString", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Parse tanggal surat manual ke format Carbon
     * 
     * @param string $dateString Format: "27 Agustus 2025"
     * @return Carbon|null
     */
    public static function parseDocumentDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            // Bersihkan string
            $dateString = trim($dateString);
            
            // Pattern: "DD Bulan YYYY"
            if (preg_match('/(\d{1,2})\s+(\w+)\s+(\d{4})/iu', $dateString, $matches)) {
                $day = $matches[1];
                $month = self::indoMonthToEnglish($matches[2]);
                $year = $matches[3];
                
                return Carbon::createFromFormat('j F Y', "$day $month $year");
            }
            
            // Pattern: DD-MM-YYYY or DD/MM/YYYY
            if (preg_match('/(\d{1,2})[\/-](\d{1,2})-(\d{4})/', $dateString, $matches)) {
                $day = $matches[1];
                $month = $matches[2];
                $year = $matches[3];
                
                return Carbon::createFromFormat('d-m-Y', "$day-$month-$year");
            }
            if (preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $dateString, $matches)) {
                $day = $matches[1];
                $month = $matches[2];
                $year = $matches[3];
                return Carbon::createFromFormat('d/m/Y', "$day/$month/$year");
            }
            
            return null;
            
        } catch (\Exception $e) {
            \Log::warning("Failed to parse document date: $dateString", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get month and year from activity date for trending
     * 
     * @param string $dateString
     * @return array ['month' => int, 'year' => int] or null
     */
    public static function getMonthYearFromActivity($dateString)
    {
        $date = self::parseActivityDate($dateString);
        
        if ($date) {
            return [
                'month' => $date->month,
                'year' => $date->year
            ];
        }
        
        return null;
    }

    /**
     * Get month and year from document date for trending fallback
     * 
     * @param string $dateString
     * @return array ['month' => int, 'year' => int] or null
     */
    public static function getMonthYearFromDocument($dateString)
    {
        $date = self::parseDocumentDate($dateString);
        
        if ($date) {
            return [
                'month' => $date->month,
                'year' => $date->year
            ];
        }
        
        return null;
    }

    /**
     * Format tanggal ke bahasa Indonesia
     * 
     * @param Carbon $date
     * @param string $format Format yang diinginkan (default: 'd F Y')
     * @return string
     */
    public static function formatIndonesian(Carbon $date, $format = 'd F Y')
    {
        // Mapping bulan Inggris ke Indonesia (kebalikan dari $monthMap)
        $indonesianMonths = [
            'January' => 'Januari',
            'February' => 'Februari', 
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        // Format tanggal dengan format bahasa Inggris terlebih dahulu
        $formattedDate = $date->format($format);
        
        // Ganti nama bulan Inggris dengan Indonesia
        foreach ($indonesianMonths as $english => $indonesian) {
            $formattedDate = str_replace($english, $indonesian, $formattedDate);
        }
        
        return $formattedDate;
    }

    /**
     * Parse a human-written Indonesian date string into a date range or list.
     * Returns ['start' => Carbon, 'end' => Carbon|null] when possible.
     * Supports formats like:
     * - "14 s/d 18 Mei 2025"
     * - "14-18 Mei 2025"
     * - "14 dan 15 Mei 2025"
     * - "14, 15, 16 Mei 2025"
     * - "14 Mei 2025"
     */
    public static function parseDateRange(string $dateString): array
    {
        $dateString = trim($dateString);
        if ($dateString === '') return [];

        try {
            // Normalize separators
            $s = preg_replace('/\s+/u', ' ', $dateString);

            // 1) Range with s/d or hyphen: "14 s/d 18 Mei 2025" or "14-18 Mei 2025"
            // Return inclusive list of days to avoid gaps/duplication when mapping by index
            if (preg_match('/(\d{1,2})\s*(?:s\/?d|s\.d\.|-|sd)\s*(\d{1,2})\s+(\w+)\s+(\d{4})/iu', $s, $m)) {
                $startDay = (int) $m[1];
                $endDay = (int) $m[2];
                $month = self::indoMonthToEnglish($m[3]);
                $year = (int) $m[4];
                $start = Carbon::createFromFormat('j F Y', "$startDay $month $year");
                $end = Carbon::createFromFormat('j F Y', "$endDay $month $year");

                // Build inclusive day list [start..end]
                $days = [];
                $cursor = $start->copy();
                while ($cursor->lessThanOrEqualTo($end)) {
                    $days[] = $cursor->copy();
                    $cursor->addDay();
                }
                return ['start' => $start, 'end' => $end, 'days' => $days];
            }

            // 2) Explicit list with "dan" or commas: "14 dan 18 Mei 2025" or "14, 18, 20 Mei 2025"
            if (preg_match('/^([\d\s,dan]+)\s+(\w+)\s+(\d{4})$/iu', $s, $m)) {
                $daysStr = $m[1];
                $month = self::indoMonthToEnglish($m[2]);
                $year = (int) $m[3];
                if (preg_match_all('/\d{1,2}/u', $daysStr, $dm)) {
                    $carbons = [];
                    foreach ($dm[0] as $d) {
                        $carbons[] = Carbon::createFromFormat('j F Y', ((int)$d) . ' ' . $month . ' ' . $year);
                    }
                    if (!empty($carbons)) {
                        return ['start' => $carbons[0], 'end' => (count($carbons) > 1 ? end($carbons) : null), 'days' => $carbons];
                    }
                }
            }

            // 3) Single date: "14 Mei 2025"
            if (preg_match('/(\d{1,2})\s+(\w+)\s+(\d{4})/iu', $s, $m)) {
                $day = (int) $m[1];
                $month = self::indoMonthToEnglish($m[2]);
                $year = (int) $m[3];
                $start = Carbon::createFromFormat('j F Y', "$day $month $year");
                return ['start' => $start, 'end' => null, 'days' => [$start]];
            }

            // 4) Numeric date dd-mm-yyyy
            if (preg_match('/(\d{1,2})-(\d{1,2})-(\d{4})/', $s, $m)) {
                $start = Carbon::createFromFormat('d-m-Y', $m[0]);
                return ['start' => $start, 'end' => null, 'days' => [$start]];
            }

            return [];
        } catch (\Throwable $e) {
            \Log::warning('parseDateRange failed', ['input' => $dateString, 'error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Hitung jumlah hari kegiatan dari string tanggal Indonesia.
     * Mendukung format seperti:
     * - "14 s/d 18 Mei 2025" (rentang inklusif -> 5 hari)
     * - "14-18 Mei 2025" (rentang inklusif)
     * - "14 dan 18 Mei 2025" (daftar diskrit -> 2 hari)
     * - "14, 18, 20 Mei 2025" (daftar diskrit -> 3 hari)
     * - "14 Mei 2025" (1 hari)
     * - "14-05-2025" atau "14/05/2025" (1 hari)
     */
    public static function countActivityDays(string $dateString): int
    {
        $dateString = trim($dateString);
        if ($dateString === '') return 0;
        try {
            $s = preg_replace('/\s+/u', ' ', $dateString);

            // Range with s/d or hyphen
            if (preg_match('/(\d{1,2})\s*(?:s\/?d|s\.d\.|-|sd)\s*(\d{1,2})\s+(\w+)\s+(\d{4})/iu', $s, $m)) {
                $startDay = (int) $m[1];
                $endDay = (int) $m[2];
                $month = self::indoMonthToEnglish($m[3]);
                $year = (int) $m[4];
                $start = \Carbon\Carbon::createFromFormat('j F Y', "$startDay $month $year");
                $end = \Carbon\Carbon::createFromFormat('j F Y', "$endDay $month $year");
                return max(1, $start->diffInDays($end) + 1);
            }

            // Explicit list with "dan" or commas
            if (preg_match('/^([\d\s,dan]+)\s+(\w+)\s+(\d{4})$/iu', $s, $m)) {
                if (preg_match_all('/\d{1,2}/u', $m[1], $dm)) {
                    return max(1, count($dm[0]));
                }
            }

            // Single date like "14 Mei 2025"
            if (preg_match('/(\d{1,2})\s+(\w+)\s+(\d{4})/iu', $s)) {
                return 1;
            }

            // Numeric date
            if (preg_match('/(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})/', $s)) {
                return 1;
            }
        } catch (\Throwable $e) {
            \Log::warning('countActivityDays failed', ['input' => $dateString, 'error' => $e->getMessage()]);
        }
        return 0;
    }
}
