<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktuator extends Model
{
    protected $fillable = [
        'sensor_data_id',
        'is_sprinkler_on',
        'is_fan_on',
        'sprinkler_duration',
        'fan_duration',
        'sprinkler_off_at',
        'fan_off_at',
    ];

    protected $casts = [
        'is_sprinkler_on' => 'integer',
        'is_fan_on' => 'integer',
        'sprinkler_duration' => 'integer',
        'fan_duration' => 'integer',
        'sprinkler_off_at' => 'datetime',
        'fan_off_at' => 'datetime',
    ];

    public function sensorData()
    {
        return $this->belongsTo(SensorData::class);
    }
}
