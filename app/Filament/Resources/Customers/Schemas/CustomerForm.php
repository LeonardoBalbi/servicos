<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required(),
                TextInput::make('document')
                    ->label('Documento'),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->tel(),
                TextInput::make('google_id')
                    ->label('Google ID'),
                DateTimePicker::make('email_verified_at')
                    ->label('E-mail verificado em'),
                Toggle::make('is_active')
                    ->label('Ativo')
                    ->default(true)
                    ->required(),
                KeyValue::make('metadata')
                    ->label('Metadados')
                    ->columnSpanFull(),
            ]);
    }
}
