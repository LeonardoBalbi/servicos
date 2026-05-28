<?php

namespace App\Filament\Resources\HeskTickets\Pages;

use App\Filament\Resources\HeskTickets\HeskTicketResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeskTicket extends EditRecord
{
    protected static string $resource = HeskTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
