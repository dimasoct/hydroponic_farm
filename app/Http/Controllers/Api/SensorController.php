<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActuatorSetting;
use App\Models\Aktuator;
use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Hitung durasi aktif aktuator dalam detik berdasarkan nilai (0/50/100).
     *
     * Nilai 0   → mati, durasi = 0 detik
     * Nilai 50  → aktif 30 detik
     * Nilai 100 → aktif 60 detik
     */
    private function getDuration(int $value, $type = 'sprinkler'): int
    {
        $setting = ActuatorSetting::firstOrCreate(['id' => 1], [
            'sprinkler_50_duration' => 30,
            'sprinkler_100_duration' => 60,
            'fan_50_duration' => 30,
            'fan_100_duration' => 60,
        ]);

        if ($type === 'sprinkler') {
            return match ($value) {
                50 => $setting->sprinkler_50_duration,
                100 => $setting->sprinkler_100_duration,
                default => 0,
            };
        } else {
            return match ($value) {
                50 => $setting->fan_50_duration,
                100 => $setting->fan_100_duration,
                default => 0,
            };
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'pressure' => 'required|numeric',
            'index_uv' => 'required|numeric',
            'conditions' => 'required|string',
            'sprinkler' => 'required|integer|in:0,50,100',
            'blower' => 'required|integer|in:0,50,100',
        ]);

        // Simpan data sensor
        $sensor = SensorData::create([
            'temperature' => $validated['temperature'],
            'humidity' => $validated['humidity'],
            'pressure' => $validated['pressure'],
            'index_uv' => $validated['index_uv'],
            'conditions' => $validated['conditions'],
        ]);

        // Hitung durasi untuk masing-masing aktuator
        $sprinklerDuration = $this->getDuration($validated['sprinkler'], 'sprinkler');
        $fanDuration = $this->getDuration($validated['blower'], 'fan');

        // Simpan aktuator dengan durasi
        $aktuator = Aktuator::create([
            'sensor_data_id' => $sensor->id,
            'is_sprinkler_on' => $validated['sprinkler'],
            'is_fan_on' => $validated['blower'],
            'sprinkler_duration' => $sprinklerDuration,
            'fan_duration' => $fanDuration,
        ]);

        // Balasan ke Raspberry Pi: sertakan aktuator_id dan durasi
        // supaya Pi tahu berapa lama harus menunggu sebelum memanggil actuator-done
        return response()->json([
            'message' => 'Data berhasil disimpan',
            'sensor_id' => $sensor->id,
            'aktuator_id' => $aktuator->id,
            'sprinkler_duration' => $sprinklerDuration,
            'fan_duration' => $fanDuration,
        ], 201);
    }
}
