<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $title = 'Anexos da solicitação';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('original_name')
                    ->label('Arquivo')
                    ->searchable()
                    ->limit(56),
                TextColumn::make('size')
                    ->label('Tamanho')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('uploaded_by_type')
                    ->label('Enviado por')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'staff' => 'Atendente',
                        'customer' => 'Cidadão',
                        default => ucfirst((string) $state),
                    }),
                TextColumn::make('path')
                    ->label('Caminho preservado')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('legacy_attachment_id')
                    ->label('ID HESK')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc');
    }
}
