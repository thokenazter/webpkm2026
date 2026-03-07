<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSaldoEntry extends Model
{
    protected $fillable = [
        'employee_id',
        'poa_id',
        'rab_item_id',
        'year',
        'month',
        'category',
        'label',
        'amount',
        'description',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'year' => 'integer',
        'month' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function poa(): BelongsTo
    {
        return $this->belongsTo(Poa::class);
    }

    public function rabItem(): BelongsTo
    {
        return $this->belongsTo(RabItem::class, 'rab_item_id');
    }
}

