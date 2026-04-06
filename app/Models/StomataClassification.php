<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StomataClassification extends Model
{
    use HasFactory;

    protected $table = 'stomata_classifications';

    protected $fillable = [
        'stomata_condition',
        'is_actuator_on',
        'image_path',
    ];

    protected $casts = [
        'is_actuator_on' => 'boolean',
    ];
}
