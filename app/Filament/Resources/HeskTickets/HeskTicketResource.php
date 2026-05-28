<?php

namespace App\Filament\Resources\HeskTickets;

use App\Filament\Resources\HeskTickets\Pages\ListHeskTickets;
use App\Filament\Resources\HeskTickets\Schemas\HeskTicketForm;
use App\Filament\Resources\HeskTickets\Tables\HeskTicketsTable;
use App\Models\HeskTicket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeskTicketResource extends Resource
{
    protected static ?string $model = HeskTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'Chamados legados';

    protected static ?string $modelLabel = 'chamado legado';

    protected static ?string $pluralModelLabel = 'chamados legados';

    protected static string|UnitEnum|null $navigationGroup = 'Migração HESK';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return HeskTicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeskTicketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHeskTickets::route('/'),
        ];
    }
}
