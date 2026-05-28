<?php

namespace App\Filament\Resources\HeskCategories\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HeskCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('Prioridade padrão')
                    ->sortable(),
                IconColumn::make('autoassign')
                    ->label('Autoatribuição')
                    ->boolean(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn ($state): string => ((int) $state) === 1 ? 'Privada' : 'Pública')
                    ->badge(),
                TextColumn::make('cat_order')
                    ->label('Ordem')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('cat_order');
    }
}
