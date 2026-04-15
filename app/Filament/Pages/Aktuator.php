<?php

namespace App\Filament\Pages;

use App\Models\ActuatorSetting;
use App\Models\SensorData;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class Aktuator extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static \UnitEnum|string|null $navigationGroup = 'Hydroponic System';

    protected string $view = 'filament.pages.aktuator';

    public ?array $data = [];

    public function mount(): void
    {
        $sensorData = SensorData::with('aktuator')->latest('updated_at')->first();
        $aktuator = $sensorData?->aktuator;

        $setting = ActuatorSetting::firstOrCreate(['id' => 1], [
            'sprinkler_50_duration' => 30,
            'sprinkler_100_duration' => 60,
            'fan_50_duration' => 30,
            'fan_100_duration' => 60,
        ]);

        $sprinklerValue = $aktuator?->is_sprinkler_on ?? 0;
        $fanValue = $aktuator?->is_fan_on ?? 0;

        $sprinklerDuration = $aktuator?->sprinkler_duration ?? 0;
        $fanDuration = $aktuator?->fan_duration ?? 0;

        if ($sprinklerValue > 0) {
            if (! is_null($aktuator?->sprinkler_off_at)) {
                $sprinklerValue = 0;
            } else {
                $sprinklerExpiry = $aktuator?->updated_at?->copy()->addSeconds($sprinklerDuration);
                if ($sprinklerExpiry && now()->greaterThanOrEqualTo($sprinklerExpiry)) {
                    $sprinklerValue = 0;
                }
            }
        }

        if ($fanValue > 0) {
            if (! is_null($aktuator?->fan_off_at)) {
                $fanValue = 0;
            } else {
                $fanExpiry = $aktuator?->updated_at?->copy()->addSeconds($fanDuration);
                if ($fanExpiry && now()->greaterThanOrEqualTo($fanExpiry)) {
                    $fanValue = 0;
                }
            }
        }

        $this->form->fill([
            'is_sprinkler_on' => $sprinklerValue,
            'is_fan_on' => $fanValue,
            'sprinkler_50_duration' => $setting->sprinkler_50_duration,
            'sprinkler_100_duration' => $setting->sprinkler_100_duration,
            'fan_50_duration' => $setting->fan_50_duration,
            'fan_100_duration' => $setting->fan_100_duration,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Kontrol Perangkat')
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
                    ])
                    ->columns(2),

                Section::make('Pengaturan Durasi Timer (Detik)')
                    ->description('Berapa lama alat akan menyala ketika dalam status Sedang (50%) atau Penuh (100%).')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\TextInput::make('sprinkler_50_duration')
                            ->label('Sprinkler Sedang (50%)')
                            ->numeric()
                            ->suffix('detik')
                            ->required(),
                        Forms\Components\TextInput::make('sprinkler_100_duration')
                            ->label('Sprinkler Penuh (100%)')
                            ->numeric()
                            ->suffix('detik')
                            ->required(),
                        Forms\Components\TextInput::make('fan_50_duration')
                            ->label('Fan Sedang (50%)')
                            ->numeric()
                            ->suffix('detik')
                            ->required(),
                        Forms\Components\TextInput::make('fan_100_duration')
                            ->label('Fan Penuh (100%)')
                            ->numeric()
                            ->suffix('detik')
                            ->required(),
                        Actions::make([
                            Action::make('save')
                                ->label('Simpan Perubahan')
                                ->submit('save')
                                ->icon('heroicon-o-check-circle')
                                ->size('lg'),
                        ])->alignEnd()->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    private function getDuration(int $value, $setting, $type): int
    {
        if ($type === 'sprinkler') {
            return match ($value) {
                50 => $setting->sprinkler_50_duration ?? 30,
                100 => $setting->sprinkler_100_duration ?? 60,
                default => 0,
            };
        } else {
            return match ($value) {
                50 => $setting->fan_50_duration ?? 30,
                100 => $setting->fan_100_duration ?? 60,
                default => 0,
            };
        }
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save Settings
        $setting = ActuatorSetting::firstOrCreate(['id' => 1]);
        $setting->update([
            'sprinkler_50_duration' => $data['sprinkler_50_duration'],
            'sprinkler_100_duration' => $data['sprinkler_100_duration'],
            'fan_50_duration' => $data['fan_50_duration'],
            'fan_100_duration' => $data['fan_100_duration'],
        ]);

        // Hitung durasi berdasarkan input user
        $sprinklerDuration = $this->getDuration((int) $data['is_sprinkler_on'], $setting, 'sprinkler');
        $fanDuration = $this->getDuration((int) $data['is_fan_on'], $setting, 'fan');

        $aktuatorPayload = [
            'is_sprinkler_on' => $data['is_sprinkler_on'],
            'is_fan_on' => $data['is_fan_on'],
            'sprinkler_duration' => $sprinklerDuration,
            'fan_duration' => $fanDuration,
            // Reset off_at karena aktuator baru saja diinstruksikan menyala (timer direset)
            'sprinkler_off_at' => null,
            'fan_off_at' => null,
        ];

        $sensorData = SensorData::with('aktuator')->latest('updated_at')->first();
        if ($sensorData) {
            if ($sensorData->aktuator) {
                $sensorData->aktuator->update($aktuatorPayload);
            } else {
                $sensorData->aktuator()->create($aktuatorPayload);
            }
        } else {
            // If no sensor data exists, create a default blank one with those states
            $newSensor = SensorData::create([
                'temperature' => 0,
                'humidity' => 0,
                'pressure' => 0,
                'index_uv' => 0,
            ]);
            $newSensor->aktuator()->create($aktuatorPayload);
        }

        Notification::make()
            ->success()
            ->title('Berhasil disimpan')
            ->send();
    }
}
