<?php

namespace App\Filament\Widgets;

use App\Models\CrmCustomer;
use App\Models\Product;
use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $newUsersThisMonth  = User::whereMonth('created_at', now()->month)->count();
        $openTasks          = Task::whereHas('status', fn ($q) => $q->whereNotIn('name', ['Terminé', 'Done', 'Closed']))->count();
        $crmThisMonth       = CrmCustomer::whereMonth('created_at', now()->month)->count();

        return [
            Stat::make('Utilisateurs', User::count())
                ->description("{$newUsersThisMonth} ce mois")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Tâches ouvertes', $openTasks)
                ->description('En cours ou en attente')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Clients CRM', CrmCustomer::count())
                ->description("{$crmThisMonth} ce mois")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Produits', Product::count())
                ->description('Dans le catalogue')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
        ];
    }
}
