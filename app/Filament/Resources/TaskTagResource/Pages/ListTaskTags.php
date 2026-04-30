<?php
namespace App\Filament\Resources\TaskTagResource\Pages;
use App\Filament\Resources\TaskTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListTaskTags extends ListRecords {
    protected static string $resource = TaskTagResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
