<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;
use App\Models\SensorData;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected function getHeaderActions(): array
    {
        $latest = SensorData::latest('updated_at')->first();
        $text = $latest && $latest->updated_at ? $latest->updated_at->format('d M Y, H:i:s') : 'Belum ada data';

        return [
            Action::make('last_updated')
                ->label('Update: ' . $text)
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->disabled()
                ->link()
        ];
    }
}
