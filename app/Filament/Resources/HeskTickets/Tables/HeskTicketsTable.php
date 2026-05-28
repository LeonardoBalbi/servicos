<?php

namespace App\Filament\Resources\HeskTickets\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HeskTicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trackid')
                    ->label('Protocolo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Assunto')
                    ->searchable()
                    ->limit(48),
                TextColumn::make('name')
                    ->label('Solicitante')
                    ->searchable()
                    ->limit(32),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('heskCategory.name')
                    ->label('Categoria')
                    ->sortable(),
                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Novo' => 'info',
                        'Aguardando atendimento' => 'warning',
                        'Aguardando cidadão' => 'gray',
                        'Resolvido' => 'success',
                        'Em andamento' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('priority_label')
                    ->label('Prioridade')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'Crítica' => 'danger',
                        'Alta' => 'warning',
                        'Média' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('replies')
                    ->label('Resp.')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dt')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('lastchange')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        0 => 'Novo',
                        1 => 'Aguardando atendimento',
                        2 => 'Aguardando cidadão',
                        3 => 'Resolvido',
                        4 => 'Em andamento',
                        5 => 'Em espera',
                    ]),
                SelectFilter::make('priority')
                    ->label('Prioridade')
                    ->options([
                        0 => 'Crítica',
                        1 => 'Alta',
                        2 => 'Média',
                        3 => 'Baixa',
                    ]),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('lastchange', 'desc');
    }
}
