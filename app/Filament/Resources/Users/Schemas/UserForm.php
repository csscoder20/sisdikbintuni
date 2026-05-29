<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required(),
                TextInput::make('nohp')
                    ->label('Nomor WA')
                    ->tel(),
                Select::make('sekolah_id')
                    ->label('Asal Sekolah')
                    ->options(\App\Models\Sekolah::pluck('nama', 'id'))
                    ->searchable()
                    ->preload(),
                Select::make('roles')
                    ->label('Peran')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'pending' => 'Menunggu Verifikasi',
                        'rejected' => 'Tidak Aktif',
                    ])
                    ->required()
                    ->default('pending'),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->placeholder('Kosongkan jika tidak ingin mengubah')
                    ->required(fn (string $context): bool => $context === 'create')
                    ->rule(Password::defaults())
                    ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state)),
            ]);
    }
}
