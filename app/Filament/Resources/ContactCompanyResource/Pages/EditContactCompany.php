<?php
namespace App\Filament\Resources\ContactCompanyResource\Pages;
use App\Filament\Resources\ContactCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditContactCompany extends EditRecord {
    protected static string $resource = ContactCompanyResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
