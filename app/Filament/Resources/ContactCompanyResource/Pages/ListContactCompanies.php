<?php
namespace App\Filament\Resources\ContactCompanyResource\Pages;
use App\Filament\Resources\ContactCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListContactCompanies extends ListRecords {
    protected static string $resource = ContactCompanyResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
