<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';
    
    protected $fillable = [
        'judul',
        'isi',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',
        'prioritas',
        'warna_tema'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('tanggal_mulai')
                          ->orWhere('tanggal_mulai', '<=', Carbon::now());
                    })
                    ->where(function($q) {
                        $q->whereNull('tanggal_selesai')
                          ->orWhere('tanggal_selesai', '>=', Carbon::now());
                    });
    }

    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        
        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai)) {
            return false;
        }
        
        if ($this->tanggal_selesai && $now->gt($this->tanggal_selesai)) {
            return false;
        }
        
        return true;
    }
}