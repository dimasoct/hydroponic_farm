<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktuator extends Model
{
    protected $fillable = [
        'sensor_data_id',
        'is_sprinkler_on',
        'is_fan_on',
    ];

    protected $casts = [
        'is_sprinkler_on' => 'integer',
        'is_fan_on' => 'integer',
    ];

    public function sensorData()
    {
        return $this->belongsTo(SensorData::class);
    }
}
