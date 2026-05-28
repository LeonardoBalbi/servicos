<?php

namespace App\Filament\Resources\HeskTickets\Pages;

use App\Filament\Resources\HeskTickets\HeskTicketResource;
use Filament\Resources\Pages\ListRecords;

class ListHeskTickets extends ListRecords
{
    protected static string $resource = HeskTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
