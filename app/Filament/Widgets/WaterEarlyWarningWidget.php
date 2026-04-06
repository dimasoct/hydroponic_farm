<?php

namespace App\Filament\Widgets;

use App\Models\SensorDataWater;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WaterEarlyWarningWidget extends BaseWidget
{
    protected ?string $pollingInterval = '5s';

    protected static ?int $sort = 3;

    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        $latest = SensorDataWater::latest('updated_at')->first();

        $warning = $latest?->early_warning ?? 'Belum ada data prediksi';

        $color = match(true) {
            str_contains(strtolower($warning), 'baik')    => 'success',
            str_contains(strtolower($warning), 'normal')  => 'info',
            str_contains(strtolower($warning), 'waspada') => 'warning',
            str_contains(strtolower($warning), 'kritis')  => 'danger',
            default                                        => 'gray',
        };

        return [
            Stat::make('Early Warning / Prediksi Kondisi', strtoupper($warning))
                ->description('Perkiraan kondisi air nutrisi hidroponik ke depannya')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color($color)
                ->icon('heroicon-o-bell-alert'),
        ];
    }
}
