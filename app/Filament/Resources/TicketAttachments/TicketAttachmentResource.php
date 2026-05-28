<?php

namespace App\Filament\Resources\TicketAttachments;

use App\Filament\Resources\TicketAttachments\Pages\CreateTicketAttachment;
use App\Filament\Resources\TicketAttachments\Pages\EditTicketAttachment;
use App\Filament\Resources\TicketAttachments\Pages\ListTicketAttachments;
use App\Filament\Resources\TicketAttachments\Schemas\TicketAttachmentForm;
use App\Filament\Resources\TicketAttachments\Tables\TicketAttachmentsTable;
use App\Models\TicketAttachment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TicketAttachmentResource extends Resource
{
    protected static ?string $model = TicketAttachment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperClip;

    protected static string|UnitEnum|null $navigationGroup = 'Atendimento';

    protected static ?string $navigationLabel = 'Anexos';

    protected static ?string $modelLabel = 'anexo';

    protected static ?string $pluralModelLabel = 'anexos';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return TicketAttachmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketAttachmentsTable::configure($table);
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
            'index' => ListTicketAttachments::route('/'),
            'create' => CreateTicketAttachment::route('/create'),
            'edit' => EditTicketAttachment::route('/{record}/edit'),
        ];
    }
}
