<?php
namespace App\Filament\Resources\CrmNoteResource\Pages;
use App\Filament\Resources\CrmNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListCrmNotes extends ListRecords {
    protected static string $resource = CrmNoteResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
