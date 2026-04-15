<?php

namespace App\Filament\Widgets;

use App\Models\SensorData;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClassificationWidget extends StatsOverviewWidget
{
    // Ensure the classification widget is placed below SensorStats.
    // We can do this by setting a sort property or by controlling the order in the PanelProvider.
    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '5s';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $latestRecord = SensorData::with('aktuator')->latest('updated_at')->first();

        $condition = $latestRecord && $latestRecord->conditions ? $latestRecord->conditions : 'Belum ada data';

        $color = match (strtolower($condition)) {
            'sangat baik' => 'success',
            'baik' => 'info',
            'sedang' => 'warning',
            'buruk' => 'danger',
            default => 'gray',
        };

        $aktuator = $latestRecord?->aktuator;

        $sprinklerValue = $aktuator?->is_sprinkler_on ?? 0;
        $fanValue = $aktuator?->is_fan_on ?? 0;

        $sprinklerDuration = $aktuator?->sprinkler_duration ?? 0;
        $fanDuration = $aktuator?->fan_duration ?? 0;

        // 1. Jika value 0, pasti SIAGA
        // 2. Jika off_at sudah terisi, berarti PI sudah mematikan fisik -> SIAGA
        // 3. Jika off_at belum terisi, tapi waktu saat ini sudah MENGLEBIHI (updated_at + duration) -> otomatis SIAGA
        $sprinklerActive = false;
        if ($sprinklerValue > 0 && is_null($aktuator?->sprinkler_off_at)) {
            $sprinklerExpiry = $aktuator?->updated_at?->copy()->addSeconds($sprinklerDuration);
            if ($sprinklerExpiry && now()->lessThan($sprinklerExpiry)) {
                $sprinklerActive = true;
            }
        }

        $fanActive = false;
        if ($fanValue > 0 && is_null($aktuator?->fan_off_at)) {
            $fanExpiry = $aktuator?->updated_at?->copy()->addSeconds($fanDuration);
            if ($fanExpiry && now()->lessThan($fanExpiry)) {
                $fanActive = true;
            }
        }

        $sprinklerText = $sprinklerActive ? "AKTIF ({$sprinklerValue}%)" : 'MATI';
        $fanText = $fanActive ? "AKTIF ({$fanValue}%)" : 'MATI';

        return [
            Stat::make('Pompa Air (Sprinkler)', $sprinklerText)
                ->description($sprinklerActive ? 'Sedang melakukan penyiraman' : 'Menunggu jadwal / instruksi')
                ->descriptionIcon($sprinklerActive ? 'heroicon-m-arrow-path' : 'heroicon-m-pause-circle')
                ->color($sprinklerActive ? 'success' : 'gray')
                ->icon('heroicon-o-sparkles'),

            Stat::make('Kipas Sirkulasi (Fan)', $fanText)
                ->description($fanActive ? 'Menjaga suhu & sirkulasi udara' : 'Menunggu instruksi pendinginan')
                ->descriptionIcon($fanActive ? 'heroicon-m-arrow-path' : 'heroicon-m-pause-circle')
                ->color($fanActive ? 'info' : 'gray')
                ->icon('heroicon-o-bolt'),

            Stat::make('Status Lingkungan', strtoupper($condition))
                ->description('Analisis Kondisi Hidroponik')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($color)
                ->icon('heroicon-o-check-badge')
                ->extraAttributes([
                    'class' => 'ring-2 ring-'.($color === 'danger' ? 'red' : ($color === 'warning' ? 'yellow' : 'green')).'-500',
                ]),
        ];
    }
}
