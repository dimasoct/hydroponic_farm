<?php

namespace App\Filament\Widgets;

use App\Models\SensorDataWater;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PhChart extends ChartWidget
{
    protected ?string $heading = 'Grafik pH Air';
    protected static ?int $sort = 2;
    protected ?string $pollingInterval = '5s';
    public ?string $filter = '1h';

    protected function getFilters(): ?array
    {
        return [
            '1h'  => '1 Jam Terakhir',
            '1d'  => '1 Hari Terakhir',
            '7d'  => '7 Hari Terakhir',
            '30d' => '30 Hari Terakhir',
        ];
    }

    protected function getData(): array
    {
        $from = match($this->filter) {
            '1d'  => Carbon::now()->subDay(),
            '7d'  => Carbon::now()->subDays(7),
            '30d' => Carbon::now()->subDays(30),
            default => Carbon::now()->subHour(),
        };

        $data = SensorDataWater::where('created_at', '>=', $from)
            ->orderBy('created_at')
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'pH',
                    'data'            => $data->pluck('ph')->toArray(),
                    'borderColor'     => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $data->pluck('created_at')->map(fn ($d) => $d ? $d->format('H:i') : 'N/A')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
