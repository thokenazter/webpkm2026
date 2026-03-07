<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RabMenu extends Model
{
    protected $fillable = [
        'component_key',
        'name',
    ];

    public function kegiatans(): HasMany
    {
        return $this->hasMany(RabKegiatan::class);
    }
}

