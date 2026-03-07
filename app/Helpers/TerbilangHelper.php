<?php

namespace App\Helpers;

class TerbilangHelper
{
    /**
     * Convert number to Indonesian words (terbilang)
     * This is a standalone helper that can be used anywhere
     */
    public static function convert($number)
    {
        if ($number == 0) {
            return 'nol';
        }
        
        $number = abs($number); // Handle negative numbers
        
        // Array untuk angka dasar
        $ones = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
            'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
            'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
        ];
        
        $tens = [
            '', '', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh',
            'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'
        ];
        
        // Fungsi untuk konversi ratusan
        $convertHundreds = function($num) use ($ones, $tens) {
            $result = '';
            
            if ($num >= 100) {
                $hundreds = intval($num / 100);
                if ($hundreds == 1) {
                    $result .= 'seratus';
                } else {
                    $result .= $ones[$hundreds] . ' ratus';
                }
                $num %= 100;
                if ($num > 0) $result .= ' ';
            }
            
            if ($num >= 20) {
                $tensDigit = intval($num / 10);
                $result .= $tens[$tensDigit];
                $num %= 10;
                if ($num > 0) $result .= ' ';
            }
            
            if ($num > 0) {
                if ($num < 20) {
                    $result .= $ones[$num];
                }
            }
            
            return $result;
        };
        
        // Handle different ranges
        if ($number < 1000) {
            return $convertHundreds($number);
        }
        
        if ($number < 1000000) {
            $thousands = intval($number / 1000);
            $remainder = $number % 1000;
            
            $result = '';
            if ($thousands == 1) {
                $result = 'seribu';
            } else {
                $result = $convertHundreds($thousands) . ' ribu';
            }
            
            if ($remainder > 0) {
                $result .= ' ' . $convertHundreds($remainder);
            }
            
            return $result;
        }
        
        if ($number < 1000000000) {
            $millions = intval($number / 1000000);
            $remainder = $number % 1000000;
            
            $result = $convertHundreds($millions) . ' juta';
            
            if ($remainder > 0) {
                if ($remainder >= 1000) {
                    $thousands = intval($remainder / 1000);
                    $hundreds = $remainder % 1000;
                    
                    if ($thousands == 1) {
                        $result .= ' seribu';
                    } else {
                        $result .= ' ' . $convertHundreds($thousands) . ' ribu';
                    }
                    
                    if ($hundreds > 0) {
                        $result .= ' ' . $convertHundreds($hundreds);
                    }
                } else {
                    $result .= ' ' . $convertHundreds($remainder);
                }
            }
            
            return $result;
        }
        
        if ($number < 1000000000000) {
            $billions = intval($number / 1000000000);
            $remainder = $number % 1000000000;
            
            $result = $convertHundreds($billions) . ' miliar';
            
            if ($remainder > 0) {
                if ($remainder >= 1000000) {
                    $millions = intval($remainder / 1000000);
                    $result .= ' ' . $convertHundreds($millions) . ' juta';
                    $remainder %= 1000000;
                }
                
                if ($remainder >= 1000) {
                    $thousands = intval($remainder / 1000);
                    if ($thousands == 1) {
                        $result .= ' seribu';
                    } else {
                        $result .= ' ' . $convertHundreds($thousands) . ' ribu';
                    }
                    $remainder %= 1000;
                }
                
                if ($remainder > 0) {
                    $result .= ' ' . $convertHundreds($remainder);
                }
            }
            
            return $result;
        }
        
        // For numbers larger than trillion, return formatted number
        return number_format($number, 0, ',', '.');
    }
    
    /**
     * Convert number to Indonesian words with "rupiah" suffix
     */
    public static function rupiah($number)
    {
        return self::convert($number) . ' rupiah';
    }
    
    /**
     * Convert number to Indonesian words with Title Case
     */
    public static function convertTitle($number)
    {
        return ucwords(self::convert($number));
    }
    
    /**
     * Convert number to Indonesian words with "rupiah" suffix in Title Case
     */
    public static function rupiahTitle($number)
    {
        return ucwords(self::convert($number)) . ' Rupiah';
    }
    
    /**
     * Convert number to Indonesian words with Title Case
     */
    public static function convertTitleCase($number)
    {
        return self::toTitleCase(self::convert($number));
    }
    
    /**
     * Convert number to Indonesian words with "rupiah" suffix in Title Case
     */
    public static function rupiahTitleCase($number)
    {
        return self::toTitleCase(self::convert($number) . ' rupiah');
    }
    
    /**
     * Convert string to Title Case (capitalize each word)
     */
    private static function toTitleCase($string)
    {
        // Split by spaces and capitalize each word
        $words = explode(' ', $string);
        $titleCaseWords = array_map(function($word) {
            return ucfirst(strtolower($word));
        }, $words);
        
        return implode(' ', $titleCaseWords);
    }
}