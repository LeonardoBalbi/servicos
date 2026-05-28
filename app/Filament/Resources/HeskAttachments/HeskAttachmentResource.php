<?php

namespace App\Filament\Resources\HeskAttachments;

use App\Filament\Resources\HeskAttachments\Pages\ListHeskAttachments;
use App\Filament\Resources\HeskAttachments\Schemas\HeskAttachmentForm;
use App\Filament\Resources\HeskAttachments\Tables\HeskAttachmentsTable;
use App\Models\HeskAttachment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeskAttachmentResource extends Resource
{
    protected static ?string $model = HeskAttachment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperClip;

    protected static ?string $navigationLabel = 'Anexos legados';

    protected static ?string $modelLabel = 'anexo legado';

    protected static ?string $pluralModelLabel = 'anexos legados';

    protected static string|UnitEnum|null $navigationGroup = 'Migração HESK';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return HeskAttachmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeskAttachmentsTable::configure($table);
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
            'index' => ListHeskAttachments::route('/'),
        ];
    }
}
