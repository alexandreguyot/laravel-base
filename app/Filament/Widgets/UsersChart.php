<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UsersChart extends ChartWidget
{
    protected static ?string $heading    = 'Nouveaux utilisateurs';
    protected static ?int    $sort       = 2;
    protected static ?string $maxHeight  = '250px';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '7'  => '7 derniers jours',
            '30' => '30 derniers jours',
            '90' => '3 derniers mois',
        ];
    }

    protected function getData(): array
    {
        $days = (int) $this->filter;

        $data = Trend::model(User::class)
            ->between(start: now()->subDays($days), end: now())
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label'           => 'Inscriptions',
                    'data'            => $data->map(fn (TrendValue $v) => $v->aggregate)->toArray(),
                    'borderColor'     => 'rgb(99, 102, 241)',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $v) => $v->date)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
