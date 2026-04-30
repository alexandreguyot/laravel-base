<?php
namespace App\Filament\Resources\CrmCustomerResource\Pages;
use App\Filament\Resources\CrmCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListCrmCustomers extends ListRecords {
    protected static string $resource = CrmCustomerResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
