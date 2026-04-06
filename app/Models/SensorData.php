<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'sensor_data';

    protected $fillable = [
        'temperature',
        'humidity',
        'pressure',
        'index_uv',
        'conditions',
    ];

    public function aktuator()
    {
        return $this->hasOne(Aktuator::class);
    }
}