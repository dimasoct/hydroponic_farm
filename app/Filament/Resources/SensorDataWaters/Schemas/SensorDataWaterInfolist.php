<?php

namespace App\Filament\Resources\SensorDataWaters\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SensorDataWaterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ph')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tds')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('do')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('early_warning')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
