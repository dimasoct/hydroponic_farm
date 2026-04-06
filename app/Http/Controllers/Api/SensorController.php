<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aktuator;
use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'pressure'    => 'required|numeric',
            'index_uv'    => 'required|numeric',
            'conditions'  => 'required|string',
            'sprinkler'   => 'required|integer',
            'blower'      => 'required|integer',
        ]);

        $sensor = SensorData::create([
            'temperature' => $validated['temperature'],
            'humidity'    => $validated['humidity'],
            'pressure'    => $validated['pressure'],
            'index_uv'    => $validated['index_uv'],
            'conditions'  => $validated['conditions'],
        ]);

        Aktuator::create([
            'sensor_data_id' => $sensor->id,
            'is_sprinkler_on' => $validated['sprinkler'],
            'is_fan_on'       => $validated['blower'],
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'id'      => $sensor->id,
        ], 201);
    }
}