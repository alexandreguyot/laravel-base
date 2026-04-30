<?php
namespace App\Filament\Resources\UserAlertResource\Pages;
use App\Filament\Resources\UserAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditUserAlert extends EditRecord {
    protected static string $resource = UserAlertResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
