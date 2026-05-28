<?php

namespace App\Filament\Resources\HeskCustomers\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HeskCustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('verified')
                    ->label('Verificado')
                    ->boolean(),
                TextColumn::make('language')
                    ->label('Idioma')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('id', 'desc');
    }
}
