<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RateSetting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'key',
        'name',
        'value',
        'description',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Helper methods untuk mendapatkan rate
    public static function getTransportRate()
    {
        return self::where('key', 'transport_rate')->where('is_active', true)->first()?->value ?? 70000;
    }

    public static function getPerDiemRate()
    {
        return self::where('key', 'per_diem_rate')->where('is_active', true)->first()?->value ?? 150000;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'name', 'value', 'is_active'])
            ->logOnlyDirty();
    }
}
