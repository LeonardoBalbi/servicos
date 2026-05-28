<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova solicitação'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todas' => Tab::make('Todas'),
            'pendentes' => Tab::make('Pendentes')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereIn('status', ['new', 'open', 'waiting_staff', 'on_hold'])),
            'aguardando_cidadao' => Tab::make('Aguardando cidadão')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', 'waiting_customer')),
            'resolvidas' => Tab::make('Resolvidas')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereIn('status', ['resolved', 'closed'])),
        ];
    }
}
