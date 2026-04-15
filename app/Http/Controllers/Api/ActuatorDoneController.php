<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aktuator;
use Illuminate\Http\Request;

/**
 * Controller untuk menerima notifikasi dari Raspberry Pi
 * bahwa aktuator (sprinkler/fan) telah selesai aktif.
 *
 * Pi memanggil endpoint ini setelah timer habis,
 * dan kita update timestamp kapan aktuator dimatikan.
 */
class ActuatorDoneController extends Controller
{
    /**
     * PATCH /api/actuator-done/{id}
     *
     * Body JSON:
     *   - sprinkler_off (bool, optional): true jika sprinkler baru mati
     *   - fan_off       (bool, optional): true jika fan baru mati
     */
    public function update(Request $request, $id)
    {
        $aktuator = Aktuator::findOrFail($id);

        $now = now();

        if ($request->boolean('sprinkler_off') && is_null($aktuator->sprinkler_off_at)) {
            $aktuator->sprinkler_off_at = $now;
        }

        if ($request->boolean('fan_off') && is_null($aktuator->fan_off_at)) {
            $aktuator->fan_off_at = $now;
        }

        $aktuator->save();

        return response()->json([
            'message' => 'Aktuator berhasil diperbarui',
            'sprinkler_off_at' => $aktuator->sprinkler_off_at,
            'fan_off_at' => $aktuator->fan_off_at,
        ]);
    }
}
