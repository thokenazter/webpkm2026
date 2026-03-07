<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoaParticipant extends Model
{
    protected $fillable = [
        'poa_id', 'employee_id', 'role', 'borrowed_employee_id', 'note'
    ];

    public function poa(): BelongsTo
    {
        return $this->belongsTo(Poa::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function borrowedEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'borrowed_employee_id');
    }
}

