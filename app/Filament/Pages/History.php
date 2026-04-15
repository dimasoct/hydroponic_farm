<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\HumidityChart;
use App\Filament\Widgets\PressureChart;
use App\Filament\Widgets\TemperatureChart;
use App\Filament\Widgets\UvIndexChart;
use App\Models\SensorData;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class History extends Page implements HasTable
{
    use InteractsWithTable;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clock';

    protected static \UnitEnum|string|null $navigationGroup = 'Hydroponic System';

    protected string $view = 'filament.pages.history';

    protected function getHeaderWidgets(): array
    {
        return [
            TemperatureChart::class,
            HumidityChart::class,
            PressureChart::class,
            UvIndexChart::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(SensorData::query()->latest('id'))
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('temperature')
                    ->label('Suhu (°C)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('humidity')
                    ->label('Kelembapan (%)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pressure')
                    ->label('Tekanan (hPa)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('index_uv')
                    ->label('Index UV')
                    ->sortable(),
            ])
            ->paginated([10, 25, 50]);
    }
}
