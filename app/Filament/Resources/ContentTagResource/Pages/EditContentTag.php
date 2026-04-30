<?php
namespace App\Filament\Resources\ContentTagResource\Pages;
use App\Filament\Resources\ContentTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditContentTag extends EditRecord {
    protected static string $resource = ContentTagResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
