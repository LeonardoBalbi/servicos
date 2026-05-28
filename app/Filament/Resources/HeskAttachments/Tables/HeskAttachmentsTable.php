<?php

namespace App\Filament\Resources\HeskAttachments\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HeskAttachmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('att_id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('ticket_id')
                    ->label('Protocolo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('real_name')
                    ->label('Arquivo original')
                    ->searchable()
                    ->limit(48),
                TextColumn::make('saved_name')
                    ->label('Arquivo salvo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('size')
                    ->label('Tamanho')
                    ->formatStateUsing(fn ($state): string => number_format(((int) $state) / 1024, 1, ',', '.') . ' KB')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('att_id', 'desc');
    }
}
