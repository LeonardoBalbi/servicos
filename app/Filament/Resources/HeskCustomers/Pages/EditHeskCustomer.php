<?php

namespace App\Filament\Resources\HeskCustomers\Pages;

use App\Filament\Resources\HeskCustomers\HeskCustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeskCustomer extends EditRecord
{
    protected static string $resource = HeskCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
