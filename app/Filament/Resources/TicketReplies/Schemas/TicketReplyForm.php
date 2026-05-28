<?php

namespace App\Filament\Resources\TicketReplies\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TicketReplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ticket_id')
                    ->label('Chamado')
                    ->relationship('ticket', 'protocol')
                    ->required(),
                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name'),
                Select::make('user_id')
                    ->label('Atendente')
                    ->relationship('user', 'name'),
                Select::make('author_type')
                    ->label('Autor')
                    ->options([
                        'customer' => 'Cliente',
                        'staff' => 'Atendente',
                        'system' => 'Sistema',
                    ])
                    ->required()
                    ->default('customer'),
                TextInput::make('author_name')
                    ->label('Nome do autor'),
                TextInput::make('author_email')
                    ->label('E-mail do autor')
                    ->email(),
                Textarea::make('message')
                    ->label('Mensagem')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('message_html')
                    ->label('Mensagem HTML')
                    ->columnSpanFull(),
                Toggle::make('is_internal')
                    ->label('Interna')
                    ->default(false)
                    ->required(),
                Toggle::make('is_read_by_customer')
                    ->label('Lida pelo cliente')
                    ->default(false)
                    ->required(),
                Toggle::make('is_read_by_staff')
                    ->label('Lida pela equipe')
                    ->default(false)
                    ->required(),
                TextInput::make('legacy_reply_id')
                    ->label('ID legado')
                    ->numeric(),
                KeyValue::make('metadata')
                    ->label('Metadados')
                    ->columnSpanFull(),
            ]);
    }
}
