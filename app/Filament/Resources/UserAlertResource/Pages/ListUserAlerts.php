<?php
namespace App\Filament\Resources\UserAlertResource\Pages;
use App\Filament\Resources\UserAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListUserAlerts extends ListRecords {
    protected static string $resource = UserAlertResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
