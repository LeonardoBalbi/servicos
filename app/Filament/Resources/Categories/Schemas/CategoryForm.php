<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
                TextInput::make('color')
                    ->label('Cor'),
                TextInput::make('icon')
                    ->label('Ícone'),
                Select::make('default_priority')
                    ->label('Prioridade padrão')
                    ->options([
                        'low' => 'Baixa',
                        'medium' => 'Média',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                    ])
                    ->required()
                    ->default('medium'),
                TextInput::make('default_due_days')
                    ->label('Prazo padrão em dias')
                    ->numeric(),
                TextInput::make('sort_order')
                    ->label('Ordem')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_private')
                    ->label('Privada')
                    ->default(false)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Ativa')
                    ->default(true)
                    ->required(),
            ]);
    }
}
