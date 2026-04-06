<?php

namespace App\Filament\Resources\SensorDataWaters;

use App\Filament\Resources\SensorDataWaters\Pages\CreateSensorDataWater;
use App\Filament\Resources\SensorDataWaters\Pages\EditSensorDataWater;
use App\Filament\Resources\SensorDataWaters\Pages\ListSensorDataWaters;
use App\Filament\Resources\SensorDataWaters\Pages\ViewSensorDataWater;
use App\Filament\Resources\SensorDataWaters\Schemas\SensorDataWaterForm;
use App\Filament\Resources\SensorDataWaters\Schemas\SensorDataWaterInfolist;
use App\Filament\Resources\SensorDataWaters\Tables\SensorDataWatersTable;
use App\Models\SensorDataWater;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SensorDataWaterResource extends Resource
{
    protected static ?string $model = SensorDataWater::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationLabel = 'Monitoring Water Sensor';
    protected static string | \UnitEnum | null $navigationGroup = 'Hydroponic System';
    protected static ?string $modelLabel = 'Water Sensor Data';
    protected static ?string $recordTitleAttribute = 'early_warning';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return SensorDataWaterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SensorDataWaterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SensorDataWatersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSensorDataWaters::route('/'),
            'create' => CreateSensorDataWater::route('/create'),
            'view' => ViewSensorDataWater::route('/{record}'),
            'edit' => EditSensorDataWater::route('/{record}/edit'),
        ];
    }
}
