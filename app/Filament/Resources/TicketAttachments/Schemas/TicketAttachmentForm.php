<?php

namespace App\Filament\Resources\TicketAttachments\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketAttachmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ticket_id')
                    ->label('Chamado')
                    ->relationship('ticket', 'protocol')
                    ->required(),
                Select::make('ticket_reply_id')
                    ->label('Resposta')
                    ->relationship('reply', 'id'),
                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name'),
                Select::make('user_id')
                    ->label('Atendente')
                    ->relationship('user', 'name'),
                TextInput::make('original_name')
                    ->label('Nome original')
                    ->required(),
                TextInput::make('stored_name')
                    ->label('Nome salvo')
                    ->required(),
                TextInput::make('disk')
                    ->label('Disco')
                    ->required()
                    ->default('local'),
                TextInput::make('path')
                    ->label('Caminho')
                    ->required(),
                TextInput::make('mime_type')
                    ->label('MIME type'),
                TextInput::make('size')
                    ->label('Tamanho')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('hash'),
                Select::make('uploaded_by_type')
                    ->label('Enviado por')
                    ->options([
                        'customer' => 'Cliente',
                        'staff' => 'Atendente',
                        'system' => 'Sistema',
                    ])
                    ->required()
                    ->default('customer'),
                TextInput::make('legacy_attachment_id')
                    ->label('ID legado')
                    ->numeric(),
                TextInput::make('legacy_saved_name')
                    ->label('Nome salvo legado'),
                KeyValue::make('metadata')
                    ->label('Metadados')
                    ->columnSpanFull(),
            ]);
    }
}
