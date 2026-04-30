<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TasksByStatusChart extends ChartWidget
{
    protected static ?string $heading   = 'Tâches par statut';
    protected static ?int    $sort      = 3;
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data = Task::select('task_statuses.name', DB::raw('count(*) as total'))
            ->leftJoin('task_statuses', 'tasks.status_id', '=', 'task_statuses.id')
            ->whereNull('tasks.deleted_at')
            ->groupBy('task_statuses.name')
            ->pluck('total', 'name');

        $colors = ['#6366f1','#f59e0b','#10b981','#ef4444','#3b82f6','#8b5cf6','#ec4899'];

        return [
            'datasets' => [[
                'data'            => $data->values()->toArray(),
                'backgroundColor' => array_slice($colors, 0, $data->count()),
            ]],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
