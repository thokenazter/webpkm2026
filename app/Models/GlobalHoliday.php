<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalHoliday extends Model
{
    protected $table = 'global_holidays';

    protected $fillable = [
        'date',
        'name',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}

