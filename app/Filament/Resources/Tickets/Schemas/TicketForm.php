<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Solicitação')
                    ->schema([
                        TextInput::make('protocol')
                            ->label('Protocolo')
                            ->default(fn (): string => 'GD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)))
                            ->required(),
                        TextInput::make('legacy_track_id')
                            ->label('Protocolo HESK')
                            ->disabled(),
                        TextInput::make('subject')
                            ->label('Assunto')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->label('Mensagem inicial')
                            ->required()
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Atendimento')
                    ->schema([
                        Select::make('category_id')
                            ->label('Categoria de serviço')
                            ->relationship('category', 'name')
                            ->required(),
                        Select::make('assigned_user_id')
                            ->label('Responsável')
                            ->relationship('assignedUser', 'name'),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'new' => 'Novo',
                                'open' => 'Aberto',
                                'waiting_customer' => 'Aguardando cidadão',
                                'waiting_staff' => 'Aguardando atendimento',
                                'on_hold' => 'Em espera',
                                'resolved' => 'Resolvido',
                                'closed' => 'Fechado',
                                'cancelled' => 'Cancelado',
                            ])
                            ->required()
                            ->default('new'),
                        Select::make('priority')
                            ->label('Prioridade')
                            ->options([
                                'low' => 'Baixa',
                                'medium' => 'Média',
                                'high' => 'Alta',
                                'critical' => 'Crítica',
                            ])
                            ->required()
                            ->default('medium'),
                        DateTimePicker::make('due_at')
                            ->label('Prazo'),
                        Select::make('source')
                            ->label('Origem')
                            ->options([
                                'web' => 'Web',
                                'email' => 'E-mail',
                                'admin' => 'Painel',
                                'migration' => 'Migração HESK',
                            ])
                            ->required()
                            ->default('web'),
                    ])
                    ->columns(3),

                Section::make('Cidadão')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Cadastro do cidadão')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('customer_name')
                            ->label('Nome informado'),
                        TextInput::make('customer_email')
                            ->label('E-mail informado')
                            ->email(),
                        TextInput::make('customer_phone')
                            ->label('Telefone informado')
                            ->tel(),
                    ])
                    ->columns(2),

                Section::make('Datas do atendimento')
                    ->schema([
                        DateTimePicker::make('first_response_at')
                            ->label('Primeira resposta'),
                        DateTimePicker::make('last_reply_at')
                            ->label('Última resposta'),
                        DateTimePicker::make('resolved_at')
                            ->label('Resolvido em'),
                        DateTimePicker::make('closed_at')
                            ->label('Fechado em'),
                    ])
                    ->columns(4)
                    ->collapsed(),

                Section::make('Auditoria da migração')
                    ->schema([
                        Textarea::make('message_html')
                            ->label('Mensagem HTML original')
                            ->rows(4)
                            ->columnSpanFull(),
                        KeyValue::make('metadata')
                            ->label('Metadados preservados')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
