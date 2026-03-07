<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RabItem extends Model
{
    protected $fillable = [
        'rab_id',
        'label',
        'type',
        'factors',
        'unit_price',
        'subtotal',
        'meta',
    ];

    protected $casts = [
        'factors' => 'array',
        'meta' => 'array',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class);
    }

    public function computeSubtotal(): float
    {
        $quantity = 1;
        if (is_array($this->factors)) {
            foreach ($this->factors as $factor) {
                $val = isset($factor['value']) ? (float) $factor['value'] : 1;
                $quantity *= max($val, 0);
            }
        }
        return (float) $quantity * (float) $this->unit_price;
    }
}

