<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PejabatTtd extends Model
{
    protected $table = 'pejabat_ttd';

    protected $fillable = [
        'nama',
        'desa',
        'jabatan',
    ];

    public function tibaBerangkatDetails(): HasMany
    {
        return $this->hasMany(TibaBerangkatDetail::class);
    }
}
