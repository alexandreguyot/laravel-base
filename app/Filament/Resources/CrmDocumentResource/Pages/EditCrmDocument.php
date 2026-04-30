<?php
namespace App\Filament\Resources\CrmDocumentResource\Pages;
use App\Filament\Resources\CrmDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditCrmDocument extends EditRecord {
    protected static string $resource = CrmDocumentResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
