<?php

namespace App\Filament\Resources\SensorDataWaters\Pages;

use App\Filament\Resources\SensorDataWaters\SensorDataWaterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSensorDataWater extends ViewRecord
{
    protected static string $resource = SensorDataWaterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
