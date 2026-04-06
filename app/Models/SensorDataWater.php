<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensorDataWater extends Model
{
    use HasFactory;

    protected $fillable = [
        'ph',
        'tds',
        'do',
        'early_warning',
        'conditions',
    ];
}
