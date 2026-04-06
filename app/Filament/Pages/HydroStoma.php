<?php

namespace App\Filament\Pages;

use App\Models\StomataClassification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class HydroStoma extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-eye';
    protected static string|\UnitEnum|null $navigationGroup = 'HydroStoma';
    protected static ?string $navigationLabel = 'HydroStoma';
    protected static ?string $title = 'HydroStoma — Klasifikasi Kondisi Stomata';
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.hydro-stoma';

    public ?StomataClassification $latest = null;

    public function mount(): void
    {
        // Ambil hanya 1 data terbaru (realtime)
        $this->latest = StomataClassification::latest()->first();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StomataClassification::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Gambar')
                    ->disk('public')
                    ->defaultImageUrl(fn () => null)
                    ->height(48)
                    ->width(64),
                Tables\Columns\TextColumn::make('stomata_condition')
                    ->label('Kondisi Stomata')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_actuator_on')
                    ->label('Aktuator')
                    ->boolean()
                    ->trueIcon('heroicon-o-bolt')
                    ->falseIcon('heroicon-o-power')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Timestamp')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->striped();
    }
}
