<?php
namespace App\Filament\Resources\TaskTagResource\Pages;
use App\Filament\Resources\TaskTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditTaskTag extends EditRecord {
    protected static string $resource = TaskTagResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
