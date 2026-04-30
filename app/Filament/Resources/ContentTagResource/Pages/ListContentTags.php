<?php
namespace App\Filament\Resources\ContentTagResource\Pages;
use App\Filament\Resources\ContentTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListContentTags extends ListRecords {
    protected static string $resource = ContentTagResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
