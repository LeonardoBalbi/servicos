<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('protocol')
                    ->label('Protocolo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Solicitação')
                    ->searchable()
                    ->limit(56)
                    ->wrap(),
                TextColumn::make('customer.name')
                    ->label('Cidadão')
                    ->searchable()
                    ->limit(32),
                TextColumn::make('customer_email')
                    ->label('E-mail')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->label('Serviço')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label('Responsável')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'new' => 'Novo',
                        'open' => 'Aberto',
                        'waiting_customer' => 'Aguardando cidadão',
                        'waiting_staff' => 'Aguardando atendimento',
                        'on_hold' => 'Em espera',
                        'resolved' => 'Resolvido',
                        'closed' => 'Fechado',
                        'cancelled' => 'Cancelado',
                        default => ucfirst((string) $state),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'new' => 'info',
                        'open' => 'primary',
                        'waiting_staff', 'on_hold' => 'warning',
                        'waiting_customer' => 'gray',
                        'resolved', 'closed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('Prioridade')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'low' => 'Baixa',
                        'medium' => 'Média',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                        default => ucfirst((string) $state),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'critical' => 'danger',
                        'high' => 'warning',
                        'medium' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('replies_count')
                    ->label('Respostas')
                    ->counts('replies')
                    ->sortable(),
                TextColumn::make('attachments_count')
                    ->label('Anexos')
                    ->counts('attachments')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Origem')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'web' => 'Web',
                        'email' => 'E-mail',
                        'admin' => 'Painel',
                        'migration' => 'Migração HESK',
                        default => ucfirst((string) $state),
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('legacy_track_id')
                    ->label('Protocolo HESK')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('due_at')
                    ->label('Prazo')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('first_response_at')
                    ->label('Primeira resposta')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('last_reply_at')
                    ->label('Última atualização')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Aberto em')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Excluído em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
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
                    ]),
                SelectFilter::make('priority')
                    ->label('Prioridade')
                    ->options([
                        'low' => 'Baixa',
                        'medium' => 'Média',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Serviço')
                    ->relationship('category', 'name'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('last_reply_at', 'desc');
    }
}
