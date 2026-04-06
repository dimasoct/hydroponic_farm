<?php

namespace App\Filament\Resources\SensorDataWaters\Pages;

use App\Filament\Resources\SensorDataWaters\SensorDataWaterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSensorDataWater extends EditRecord
{
    protected static string $resource = SensorDataWaterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
