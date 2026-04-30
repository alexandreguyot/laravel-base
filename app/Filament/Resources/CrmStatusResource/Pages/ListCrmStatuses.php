<?php
namespace App\Filament\Resources\CrmStatusResource\Pages;
use App\Filament\Resources\CrmStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListCrmStatuses extends ListRecords {
    protected static string $resource = CrmStatusResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
