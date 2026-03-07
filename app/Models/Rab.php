<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Rab extends Model
{
    use LogsActivity;

    protected $fillable = [
        'komponen',
        'rab_menu_id',
        'rab_kegiatan_id',
        'rincian_menu',
        'kegiatan',
        'total',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(RabItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(RabMenu::class, 'rab_menu_id');
    }

    public function kegiatanRef(): BelongsTo
    {
        return $this->belongsTo(RabKegiatan::class, 'rab_kegiatan_id');
    }

    public function recalculateTotal(): void
    {
        $this->total = $this->items->sum('subtotal');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['komponen', 'rincian_menu', 'kegiatan', 'total'])
            ->logOnlyDirty();
    }

    /**
     * Fixed 5 main components per spec.
     */
    public static function components(): array
    {
        return [
            'komp1' => 'Peningkatan Layanan Kesehatan Sesuai Siklus Hidup',
            'komp2' => 'Surveilans, respons penyakit dan kesehatan lingkungan',
            'komp3' => 'Pemberian Makanan Tambahan (PMT) berbahan pangan lokal',
            'komp4' => 'MANAGEMEN PUSKESMAS',
            'komp5' => 'INSENTIF UKM',
        ];
    }
}
