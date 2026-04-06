<?php

namespace App\Filament\Widgets;

use App\Models\SensorDataWater;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WaterSensorTableWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected ?string $pollingInterval = '5s';
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SensorDataWater::query()->latest('updated_at')->limit(20)
            )
            ->columns([
                Tables\Columns\TextColumn::make('ph')
                    ->label('pH')
                    ->numeric(decimalPlaces: 2)
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 5.5 && $state <= 7.0 => 'success',
                        $state < 4 || $state > 8        => 'danger',
                        default                         => 'warning',
                    }),

                Tables\Columns\TextColumn::make('tds')
                    ->label('TDS (ppm)')
                    ->numeric(decimalPlaces: 2)
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 800 && $state <= 1500 => 'success',
                        $state < 400 || $state > 2000   => 'danger',
                        default                         => 'warning',
                    }),

                Tables\Columns\TextColumn::make('do')
                    ->label('DO (mg/L)')
                    ->numeric(decimalPlaces: 2)
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 5 => 'success',
                        $state < 3  => 'danger',
                        default     => 'warning',
                    }),

                Tables\Columns\TextColumn::make('conditions')
                    ->label('Klasifikasi Kondisi Air')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->paginated(false)
            ->heading('Data Terbaru Water Sensor');
    }
}
