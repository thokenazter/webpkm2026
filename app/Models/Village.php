<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Village extends Model
{
    use LogsActivity;

    protected $fillable = [
        'nama',
        'kecamatan',
        'kepala_desa',
        'akses',
        'transport_standard',
    ];

    protected $casts = [
        'transport_standard' => 'decimal:2',
    ];

    public function lpjs(): HasMany
    {
        return $this->hasMany(Lpj::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama', 'kecamatan', 'kepala_desa', 'akses', 'transport_standard'])
            ->logOnlyDirty();
    }
}
