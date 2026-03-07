<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RabKegiatan extends Model
{
    protected $fillable = [
        'rab_menu_id',
        'name',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(RabMenu::class, 'rab_menu_id');
    }
}

