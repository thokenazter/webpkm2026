<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Lpj extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'type',
        'kegiatan',
        'no_surat',
        'tanggal_surat',
        'tanggal_kegiatan',
        'transport_mode',
        'created_by',
        'jumlah_desa_darat',
        'desa_tujuan_darat',
        'jumlah_desa_seberang',
        'desa_tujuan_seberang',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'tanggal_surat' => 'string',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Activity relationship removed - using manual kegiatan field

    public function villages(): BelongsToMany
    {
        return $this->belongsToMany(Village::class, 'lpj_villages');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(LpjParticipant::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'no_surat', 'tanggal_surat', 'tanggal_kegiatan'])
            ->logOnlyDirty();
    }
}
