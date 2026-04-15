<?php

namespace App\Filament\Resources\SensorDataWaters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SensorDataWaterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('ph')
                    ->numeric(),
                TextInput::make('tds')
                    ->numeric(),
                TextInput::make('do')
                    ->numeric(),
                TextInput::make('early_warning'),
                TextInput::make('conditions'),
            ]);
    }
}
