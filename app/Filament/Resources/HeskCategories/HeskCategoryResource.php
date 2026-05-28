<?php

namespace App\Filament\Resources\HeskCategories;

use App\Filament\Resources\HeskCategories\Pages\ListHeskCategories;
use App\Filament\Resources\HeskCategories\Schemas\HeskCategoryForm;
use App\Filament\Resources\HeskCategories\Tables\HeskCategoriesTable;
use App\Models\HeskCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeskCategoryResource extends Resource
{
    protected static ?string $model = HeskCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $navigationLabel = 'Categorias legadas';

    protected static ?string $modelLabel = 'categoria legada';

    protected static ?string $pluralModelLabel = 'categorias legadas';

    protected static string|UnitEnum|null $navigationGroup = 'Migração HESK';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return HeskCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeskCategoriesTable::configure($table);
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
            'index' => ListHeskCategories::route('/'),
        ];
    }
}
