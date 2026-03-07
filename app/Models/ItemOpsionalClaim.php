<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemOpsionalClaim extends Model
{
    protected $fillable = [
        'poa_id',
        'rab_item_id',
        'month',
        'label',
        'type',
        'amount',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function poa(): BelongsTo
    {
        return $this->belongsTo(Poa::class);
    }

    public function rabItem(): BelongsTo
    {
        return $this->belongsTo(RabItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Optional RAB item types that generate kwitansi documents.
     */
    public static function optionalTypes(): array
    {
        return ['konsumsi', 'snack', 'penggandaan', 'bahan_makanan', 'lainnya'];
    }
}
