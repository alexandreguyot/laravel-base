<?php
namespace App\Filament\Resources\ContactContactResource\Pages;
use App\Filament\Resources\ContactContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditContactContact extends EditRecord {
    protected static string $resource = ContactContactResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
