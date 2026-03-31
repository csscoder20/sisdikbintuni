<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('nohp')
                    ->label('No. Handphone')
                    ->tel()
                    ->maxLength(255),
                Select::make('sekolah_id')
                    ->label('Asal Sekolah')
                    ->relationship('sekolah', 'nama_sekolah')
                    ->searchable()
                    ->preload(),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'operator' => 'Operator',
                    ])
                    ->required()
                    ->default('operator'),
                Toggle::make('is_verified')
                    ->label('Verifikasi Operator')
                    ->default(false),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state)),
            ]);
    }
}
