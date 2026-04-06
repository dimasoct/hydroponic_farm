<?php

namespace App\Filament\Resources\SensorDataWaters\Pages;

use App\Filament\Resources\SensorDataWaters\SensorDataWaterResource;
use App\Filament\Widgets\WaterSensorStats;
use App\Filament\Widgets\PhChart;
use App\Filament\Widgets\TdsChart;
use App\Filament\Widgets\DoChart;
use App\Filament\Widgets\WaterSensorTableWidget;
use App\Models\SensorDataWater;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListSensorDataWaters extends ListRecords
{
    protected static string $resource = SensorDataWaterResource::class;

    protected string $view = 'filament.resources.sensor-data-waters.pages.list-sensor-data-waters';

    protected function getHeaderActions(): array
    {
        $latest = SensorDataWater::latest('updated_at')->first();
        $text = $latest && $latest->updated_at ? $latest->updated_at->format('d M Y, H:i:s') : 'Belum ada data';

        return [
            Action::make('last_updated')
                ->label('Update: ' . $text)
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->disabled()
                ->link(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WaterSensorStats::class,
            PhChart::class,
            TdsChart::class,
            DoChart::class,
            WaterSensorTableWidget::class,
        ];
    }
}
