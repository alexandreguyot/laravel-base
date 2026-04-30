<?php
namespace App\Filament\Resources\CrmCustomerResource\Pages;
use App\Filament\Resources\CrmCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditCrmCustomer extends EditRecord {
    protected static string $resource = CrmCustomerResource::class;
    protected function getHeaderActions(): array {
        return [Actions\DeleteAction::make(), Actions\ForceDeleteAction::make(), Actions\RestoreAction::make()];
    }
}
