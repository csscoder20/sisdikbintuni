<?php

namespace App\Filament\Resources\KehadiranGtk\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KehadiranGtkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gtk_id')
                    ->relationship('gtk', 'nama')
                    ->required(),
                Select::make('laporan_id')
                    ->relationship('laporan', 'id') // Ideally show month/year
                    ->required(),
                TextInput::make('hari_kerja')
                    ->numeric()
                    ->required(),
                TextInput::make('sakit')
                    ->numeric()
                    ->default(0),
                TextInput::make('izin')
                    ->numeric()
                    ->default(0),
                TextInput::make('alfa')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
