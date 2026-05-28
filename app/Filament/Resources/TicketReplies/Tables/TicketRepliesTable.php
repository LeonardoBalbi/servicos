<?php

namespace App\Filament\Resources\TicketReplies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketRepliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket.id')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('author_type')
                    ->searchable(),
                TextColumn::make('author_name')
                    ->searchable(),
                TextColumn::make('author_email')
                    ->searchable(),
                IconColumn::make('is_internal')
                    ->boolean(),
                IconColumn::make('is_read_by_customer')
                    ->boolean(),
                IconColumn::make('is_read_by_staff')
                    ->boolean(),
                TextColumn::make('legacy_reply_id')
                    ->numeric()
                    ->sortable(),
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
