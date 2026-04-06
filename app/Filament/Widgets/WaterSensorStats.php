<?php

namespace App\Filament\Widgets;

use App\Models\SensorDataWater;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WaterSensorStats extends BaseWidget
{
    protected ?string $pollingInterval = '5s';

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        $history = SensorDataWater::latest('updated_at')->take(15)->get()->reverse();

        $latest   = $history->last();
        $previous = $history->count() > 1 ? $history->slice(-2, 1)->first() : null;

        $phs  = $history->pluck('ph')->toArray();
        $tdss = $history->pluck('tds')->toArray();
        $dos  = $history->pluck('do')->toArray();

        $phTrend  = $previous && $latest ? round($latest->ph  - $previous->ph,  2) : 0;
        $tdsTrend = $previous && $latest ? round($latest->tds - $previous->tds, 2) : 0;
        $doTrend  = $previous && $latest ? round($latest->do  - $previous->do,  2) : 0;

        $phColor = match(true) {
            $latest && $latest->ph >= 5.5 && $latest->ph <= 7.0 => 'success',
            $latest && ($latest->ph < 4 || $latest->ph > 8)     => 'danger',
            default                                              => 'warning',
        };

        $tdsColor = match(true) {
            $latest && $latest->tds >= 800 && $latest->tds <= 1500 => 'success',
            $latest && ($latest->tds < 400 || $latest->tds > 2000) => 'danger',
            default                                                 => 'warning',
        };

        $doColor = match(true) {
            $latest && $latest->do >= 5 => 'success',
            $latest && $latest->do < 3  => 'danger',
            default                     => 'warning',
        };

        $warning = $latest?->early_warning ?? 'Belum ada data';
        $ewColor = match(true) {
            str_contains(strtolower($warning), 'baik')    => 'success',
            str_contains(strtolower($warning), 'normal')  => 'info',
            str_contains(strtolower($warning), 'waspada') => 'warning',
            str_contains(strtolower($warning), 'kritis')  => 'danger',
            default                                        => 'gray',
        };

        $condition = $latest?->conditions ?? 'Belum ada data';
        $conditionColor = match(strtolower($condition)) {
            'sangat baik' => 'success',
            'baik'        => 'info',
            'sedang'      => 'warning',
            'buruk'       => 'danger',
            default       => 'gray',
        };

        return [
            Stat::make('pH Air', $latest ? number_format($latest->ph, 2) : 'N/A')
                ->description($phTrend > 0 ? 'Naik '.abs($phTrend) : ($phTrend < 0 ? 'Turun '.abs($phTrend) : 'Stabil'))
                ->descriptionIcon($phTrend > 0 ? 'heroicon-m-arrow-trending-up' : ($phTrend < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->chart($phs ?: [0])
                ->color($phColor)
                ->icon('heroicon-o-beaker'),

            Stat::make('TDS (ppm)', $latest ? number_format($latest->tds, 2) : 'N/A')
                ->description($tdsTrend > 0 ? 'Naik '.abs($tdsTrend).' ppm' : ($tdsTrend < 0 ? 'Turun '.abs($tdsTrend).' ppm' : 'Stabil'))
                ->descriptionIcon($tdsTrend > 0 ? 'heroicon-m-arrow-trending-up' : ($tdsTrend < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->chart($tdss ?: [0])
                ->color($tdsColor)
                ->icon('heroicon-o-funnel'),

            Stat::make('DO (mg/L)', $latest ? number_format($latest->do, 2) : 'N/A')
                ->description($doTrend > 0 ? 'Naik '.abs($doTrend).' mg/L' : ($doTrend < 0 ? 'Turun '.abs($doTrend).' mg/L' : 'Stabil'))
                ->descriptionIcon($doTrend > 0 ? 'heroicon-m-arrow-trending-up' : ($doTrend < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->chart($dos ?: [0])
                ->color($doColor)
                ->icon('heroicon-o-eye-dropper'),

            Stat::make('Early Warning', strtoupper($warning))
                ->description('Prediksi kondisi air nutrisi')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color($ewColor)
                ->icon('heroicon-o-bell-alert'),

            Stat::make('Klasifikasi Kondisi Air', strtoupper($condition))
                ->description('Analisis kondisi air saat ini')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color($conditionColor)
                ->icon('heroicon-o-check-badge'),
        ];
    }
}
