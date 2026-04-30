<?php
namespace App\Filament\Resources\CrmDocumentResource\Pages;
use App\Filament\Resources\CrmDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListCrmDocuments extends ListRecords {
    protected static string $resource = CrmDocumentResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
