<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Ticket;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AtendimentoOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Central de atendimento';

    protected ?string $description = 'Visão rápida das solicitações importadas e em operação.';

    protected function getStats(): array
    {
        $pending = Ticket::query()
            ->whereIn('status', ['new', 'open', 'waiting_staff', 'on_hold'])
            ->count();

        $waitingCitizen = Ticket::query()
            ->where('status', 'waiting_customer')
            ->count();

        $resolved = Ticket::query()
            ->whereIn('status', ['resolved', 'closed'])
            ->count();

        return [
            Stat::make('Solicitações', number_format(Ticket::count(), 0, ',', '.'))
                ->description('Total no sistema novo')
                ->icon(Heroicon::OutlinedTicket)
                ->color('primary'),
            Stat::make('Pendentes', number_format($pending, 0, ',', '.'))
                ->description('Novas, abertas ou em atendimento')
                ->icon(Heroicon::OutlinedClock)
                ->color('warning'),
            Stat::make('Aguardando cidadão', number_format($waitingCitizen, 0, ',', '.'))
                ->description('Dependem de retorno do solicitante')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('gray'),
            Stat::make('Resolvidas', number_format($resolved, 0, ',', '.'))
                ->description('Atendimento concluído')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
            Stat::make('Cidadãos', number_format(Customer::count(), 0, ',', '.'))
                ->description('Cadastros únicos por e-mail')
                ->icon(Heroicon::OutlinedUsers)
                ->color('info'),
            Stat::make('Serviços', number_format(Category::count(), 0, ',', '.'))
                ->description('Categorias de atendimento')
                ->icon(Heroicon::OutlinedFolder)
                ->color('primary'),
        ];
    }
}
