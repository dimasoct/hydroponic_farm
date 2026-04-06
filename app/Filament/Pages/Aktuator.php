<?php

namespace App\Filament\Pages;

use App\Models\SensorData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Aktuator extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static string | \UnitEnum | null $navigationGroup = 'Hydroponic System';

    protected string $view = 'filament.pages.aktuator';

    public ?array $data = [];

    public function mount(): void
    {
        $sensorData = SensorData::with('aktuator')->latest('updated_at')->first();
        $aktuator = $sensorData?->aktuator;

        $this->form->fill([
            'is_sprinkler_on' => $aktuator?->is_sprinkler_on ?? 0,
            'is_fan_on' => $aktuator?->is_fan_on ?? 0,
        ]);
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Kontrol Perangkat')
                    ->description('Aktifkan atau matikan perangkat hardware hidroponik secara manual.')
                    ->icon('heroicon-o-cpu-chip')
                    ->schema([
                        Forms\Components\ToggleButtons::make('is_sprinkler_on')
                            ->label('Sprinkler')
                            ->helperText('Menghidupkan pompa penyiraman air')
                            ->options([
                                0 => 'Mati (0%)',
                                50 => 'Sedang (50%)',
                                100 => 'Penuh (100%)',
                            ])
                            ->colors([
                                0 => 'danger',
                                50 => 'warning',
                                100 => 'success',
                            ])
                            ->icons([
                                0 => 'heroicon-m-power',
                                50 => 'heroicon-m-bolt',
                                100 => 'heroicon-m-bolt',
                            ])
                            ->inline(),
                        Forms\Components\ToggleButtons::make('is_fan_on')
                            ->label('Kipas / Fan')
                            ->helperText('Menghidupkan kipas sirkulasi dan pendingin udara')
                            ->options([
                                0 => 'Mati (0%)',
                                50 => 'Sedang (50%)',
                                100 => 'Penuh (100%)',
                            ])
                            ->colors([
                                0 => 'danger',
                                50 => 'warning',
                                100 => 'success',
                            ])
                            ->icons([
                                0 => 'heroicon-m-power',
                                50 => 'heroicon-m-bolt',
                                100 => 'heroicon-m-bolt',
                            ])
                            ->inline(),
                        \Filament\Schemas\Components\Actions::make([
                            \Filament\Actions\Action::make('save')
                                ->label('Simpan Perubahan')
                                ->submit('save')
                                ->icon('heroicon-o-check-circle')
                                ->size('lg')
                        ])->alignEnd()->columnSpanFull()
                    ])
                    ->columns(2)
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $sensorData = SensorData::with('aktuator')->latest('updated_at')->first();
        if ($sensorData) {
            if ($sensorData->aktuator) {
                $sensorData->aktuator->update([
                    'is_sprinkler_on' => $data['is_sprinkler_on'],
                    'is_fan_on' => $data['is_fan_on'],
                ]);
            } else {
                $sensorData->aktuator()->create([
                    'is_sprinkler_on' => $data['is_sprinkler_on'],
                    'is_fan_on' => $data['is_fan_on'],
                ]);
            }
        } else {
            // If no sensor data exists, create a default blank one with those states
            $newSensor = SensorData::create([
                'temperature' => 0,
                'humidity' => 0,
                'pressure' => 0,
                'index_uv' => 0,
            ]);
            $newSensor->aktuator()->create([
                'is_sprinkler_on' => $data['is_sprinkler_on'],
                'is_fan_on' => $data['is_fan_on'],
            ]);
        }

        Notification::make()
            ->success()
            ->title('Berhasil disimpan')
            ->send();
    }
}
