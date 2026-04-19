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
                    ->label('Nama GTK')
                    ->relationship('gtk', 'nama')
                    ->required(),
                Select::make('laporan_id')
                    ->label('Laporan Bulanan')
                    ->relationship('laporan', 'id') // Ideally show month/year
                    ->required(),
                TextInput::make('hari_kerja')
                    ->label('Hari Kerja')
                    ->numeric()
                    ->required(),
                TextInput::make('sakit')
                    ->label('Sakit')
                    ->numeric()
                    ->default(0),
                TextInput::make('izin')
                    ->label('Izin')
                    ->numeric()
                    ->default(0),
                TextInput::make('alfa')
                    ->label('Alpa')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
