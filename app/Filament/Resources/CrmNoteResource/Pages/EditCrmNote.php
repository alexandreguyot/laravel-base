<?php
namespace App\Filament\Resources\CrmNoteResource\Pages;
use App\Filament\Resources\CrmNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditCrmNote extends EditRecord {
    protected static string $resource = CrmNoteResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
