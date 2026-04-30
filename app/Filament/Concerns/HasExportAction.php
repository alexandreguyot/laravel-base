<?php

namespace App\Filament\Concerns;

use App\Exports\GenericExport;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

trait HasExportAction
{
    /**
     * Returns a Filament table header action that exports to Excel.
     * Override $exportColumns and $exportRelations in the Resource.
     */
    public static function getExportAction(): Action
    {
        return Action::make('export')
            ->label('Exporter Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function () {
                $model    = static::getModel();
                $columns  = static::getExportColumns();
                $relations = static::getExportRelations();
                $filename  = str(class_basename($model))->snake()->append('_export_' . now()->format('Ymd_His') . '.xlsx');

                return Excel::download(
                    new GenericExport($model::query(), $columns, $relations),
                    $filename
                );
            });
    }

    public static function getExportColumns(): array
    {
        return ['id' => 'ID', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return [];
    }
}
