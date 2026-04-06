<?php

namespace App\Filament\Widgets;

use App\Models\SensorData;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SensorStats extends BaseWidget
{
    protected ?string $pollingInterval = '5s';

    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        // Get the latest 10 records for the sparkline charts, reverse to show chronological order left-to-right
        $history = SensorData::latest('updated_at')->take(15)->get()->reverse();
        
        $latest = $history->last();
        $previous = $history->count() > 1 ? $history->slice(-2, 1)->first() : null;

        $temperatures = $history->pluck('temperature')->toArray();
        $humidities = $history->pluck('humidity')->toArray();
        $pressures = $history->pluck('pressure')->toArray();
        $uvIndexes = $history->pluck('index_uv')->toArray();

        // Calculate trends
        $tempTrend = $previous ? $latest->temperature - $previous->temperature : 0;
        $humTrend = $previous ? $latest->humidity - $previous->humidity : 0;
        $pressTrend = $previous ? $latest->pressure - $previous->pressure : 0;
        $uvTrend = $previous ? $latest->index_uv - $previous->index_uv : 0;

        return [
            Stat::make('Temperature', $latest?->temperature . ' °C')
                ->description($tempTrend > 0 ? 'Naik '.round(abs($tempTrend), 2).' °C' : ($tempTrend < 0 ? 'Turun '.round(abs($tempTrend), 2).' °C' : 'Stabil'))
                ->descriptionIcon($tempTrend > 0 ? 'heroicon-m-arrow-trending-up' : ($tempTrend < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->chart($temperatures ?: [0])
                ->color($latest?->temperature > 30 ? 'danger' : 'success')
                ->icon('heroicon-o-fire'),

            Stat::make('Humidity', $latest?->humidity . ' %')
                ->description($humTrend > 0 ? 'Naik '.round(abs($humTrend), 2).' %' : ($humTrend < 0 ? 'Turun '.round(abs($humTrend), 2).' %' : 'Stabil'))
                ->descriptionIcon($humTrend > 0 ? 'heroicon-m-arrow-trending-up' : ($humTrend < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->chart($humidities ?: [0])
                ->color($latest?->humidity < 40 ? 'warning' : 'info')
                ->icon('heroicon-o-cloud'),

            Stat::make('Pressure', $latest?->pressure . ' hPa')
                ->description($pressTrend > 0 ? 'Naik '.round(abs($pressTrend), 2).' hPa' : ($pressTrend < 0 ? 'Turun '.round(abs($pressTrend), 2).' hPa' : 'Stabil'))
                ->descriptionIcon($pressTrend > 0 ? 'heroicon-m-arrow-trending-up' : ($pressTrend < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->chart($pressures ?: [0])
                ->color('primary')
                ->icon('heroicon-o-arrow-trending-up'),

            Stat::make('UV Index', $latest?->index_uv)
                ->description($uvTrend > 0 ? 'Naik '.round(abs($uvTrend), 2) : ($uvTrend < 0 ? 'Turun '.round(abs($uvTrend), 2) : 'Stabil'))
                ->descriptionIcon($uvTrend > 0 ? 'heroicon-m-arrow-trending-up' : ($uvTrend < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'))
                ->chart($uvIndexes ?: [0])
                ->color($latest?->index_uv > 5 ? 'danger' : 'success')
                ->icon('heroicon-o-sun'),
        ];
    }
}