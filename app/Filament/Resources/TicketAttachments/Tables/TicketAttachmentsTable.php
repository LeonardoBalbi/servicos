<?php

namespace App\Filament\Resources\TicketAttachments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketAttachmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket.id')
                    ->searchable(),
                TextColumn::make('ticket_reply_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('original_name')
                    ->searchable(),
                TextColumn::make('stored_name')
                    ->searchable(),
                TextColumn::make('disk')
                    ->searchable(),
                TextColumn::make('path')
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->searchable(),
                TextColumn::make('size')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('hash')
                    ->searchable(),
                TextColumn::make('uploaded_by_type')
                    ->searchable(),
                TextColumn::make('legacy_attachment_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('legacy_saved_name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
