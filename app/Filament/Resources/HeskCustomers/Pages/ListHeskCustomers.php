<?php

namespace App\Filament\Resources\HeskCustomers\Pages;

use App\Filament\Resources\HeskCustomers\HeskCustomerResource;
use Filament\Resources\Pages\ListRecords;

class ListHeskCustomers extends ListRecords
{
    protected static string $resource = HeskCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
