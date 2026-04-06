<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'pressure'          => 'required|numeric',
            'index_uv'          => 'nullable|numeric',
        ]);

        $data = SensorData::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}