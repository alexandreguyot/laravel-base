<?php
namespace App\Filament\Resources\ContactContactResource\Pages;
use App\Filament\Resources\ContactContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListContactContacts extends ListRecords {
    protected static string $resource = ContactContactResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
