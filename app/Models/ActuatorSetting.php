<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActuatorSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'sprinkler_50_duration',
        'sprinkler_100_duration',
        'fan_50_duration',
        'fan_100_duration',
    ];
}
