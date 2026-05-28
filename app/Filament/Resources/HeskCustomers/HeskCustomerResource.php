<?php

namespace App\Filament\Resources\HeskCustomers;

use App\Filament\Resources\HeskCustomers\Pages\ListHeskCustomers;
use App\Filament\Resources\HeskCustomers\Schemas\HeskCustomerForm;
use App\Filament\Resources\HeskCustomers\Tables\HeskCustomersTable;
use App\Models\HeskCustomer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeskCustomerResource extends Resource
{
    protected static ?string $model = HeskCustomer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Clientes legados';

    protected static ?string $modelLabel = 'cliente legado';

    protected static ?string $pluralModelLabel = 'clientes legados';

    protected static string|UnitEnum|null $navigationGroup = 'Migração HESK';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return HeskCustomerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeskCustomersTable::configure($table);
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
            'index' => ListHeskCustomers::route('/'),
        ];
    }
}
