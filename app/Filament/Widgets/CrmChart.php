<?php

namespace App\Filament\Widgets;

use App\Models\CrmCustomer;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CrmChart extends ChartWidget
{
    protected static ?string $heading   = 'Nouveaux clients CRM';
    protected static ?int    $sort      = 4;
    protected static ?string $maxHeight = '250px';

    public ?string $filter = '90';

    protected function getFilters(): ?array
    {
        return [
            '30' => '30 derniers jours',
            '90' => '3 derniers mois',
            '180' => '6 derniers mois',
        ];
    }

    protected function getData(): array
    {
        $days = (int) $this->filter;

        $data = Trend::model(CrmCustomer::class)
            ->between(start: now()->subDays($days), end: now())
            ->perWeek()
            ->count();

        return [
            'datasets' => [[
                'label'           => 'Nouveaux clients',
                'data'            => $data->map(fn (TrendValue $v) => $v->aggregate)->toArray(),
                'backgroundColor' => 'rgba(16, 185, 129, 0.7)',
                'borderColor'     => 'rgb(16, 185, 129)',
                'borderRadius'    => 4,
            ]],
            'labels' => $data->map(fn (TrendValue $v) => $v->date)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
