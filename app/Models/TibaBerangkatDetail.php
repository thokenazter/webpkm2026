<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TibaBerangkatDetail extends Model
{
    protected $table = 'tiba_berangkat_detail';

    protected $fillable = [
        'tiba_berangkat_id',
        'pejabat_ttd_id',
        'tanggal_kunjungan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function tibaBerangkat(): BelongsTo
    {
        return $this->belongsTo(TibaBerangkat::class);
    }

    public function pejabatTtd(): BelongsTo
    {
        return $this->belongsTo(PejabatTtd::class);
    }
}
