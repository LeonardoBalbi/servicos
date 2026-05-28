<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RepliesRelationManager extends RelationManager
{
    protected static string $relationship = 'replies';

    protected static ?string $title = 'Histórico de atendimento';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('author_type')
                    ->label('Autor')
                    ->options([
                        'customer' => 'Cidadão',
                        'staff' => 'Atendente',
                        'system' => 'Sistema',
                    ])
                    ->required()
                    ->default('staff'),
                Select::make('user_id')
                    ->label('Atendente')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Textarea::make('message')
                    ->label('Resposta')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author_type')
                    ->label('Autor')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'staff' => 'Atendente',
                        'customer' => 'Cidadão',
                        'system' => 'Sistema',
                        default => ucfirst((string) $state),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'staff' => 'primary',
                        'customer' => 'gray',
                        default => 'info',
                    }),
                TextColumn::make('user.name')
                    ->label('Atendente')
                    ->placeholder('-'),
                TextColumn::make('message')
                    ->label('Mensagem')
                    ->limit(90)
                    ->wrap()
                    ->searchable(),
                IconColumn::make('is_internal')
                    ->label('Interna')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar resposta'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'asc');
    }
}
