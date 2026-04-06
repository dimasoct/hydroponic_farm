<?php

namespace App\Filament\Widgets;

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
        $latestRecord = \App\Models\SensorData::with('aktuator')->latest('updated_at')->first();

        $condition = $latestRecord && $latestRecord->conditions ? $latestRecord->conditions : 'Belum ada data';
        
        $color = match(strtolower($condition)) {
            'sangat baik' => 'success',
            'baik' => 'info',
            'sedang' => 'warning',
            'buruk' => 'danger',
            default => 'gray',
        };

        $sprinklerValue = $latestRecord && $latestRecord->aktuator ? $latestRecord->aktuator->is_sprinkler_on : 0;
        $fanValue = $latestRecord && $latestRecord->aktuator ? $latestRecord->aktuator->is_fan_on : 0;

        $sprinklerText = $sprinklerValue > 0 ? "AKTIF ({$sprinklerValue}%)" : 'SIAGA';
        $fanText = $fanValue > 0 ? "AKTIF ({$fanValue}%)" : 'SIAGA';

        return [
            Stat::make('Pompa Air (Sprinkler)', $sprinklerText)
                ->description($sprinklerValue > 0 ? 'Sedang melakukan penyiraman' : 'Menunggu jadwal / instruksi')
                ->descriptionIcon($sprinklerValue > 0 ? 'heroicon-m-arrow-path' : 'heroicon-m-pause-circle')
                ->color($sprinklerValue > 0 ? 'success' : 'gray')
                ->icon('heroicon-o-sparkles'),

            Stat::make('Kipas Sirkulasi (Fan)', $fanText)
                ->description($fanValue > 0 ? 'Menjaga suhu & sirkulasi udara' : 'Menunggu instruksi pendinginan')
                ->descriptionIcon($fanValue > 0 ? 'heroicon-m-arrow-path' : 'heroicon-m-pause-circle')
                ->color($fanValue > 0 ? 'info' : 'gray')
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
