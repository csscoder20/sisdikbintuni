<?php

namespace App\Filament\Resources\GtkKeuangan\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GtkKeuanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gtk_id')
                    ->relationship('gtk', 'nama')
                    ->required(),
                TextInput::make('nomor_rekening')
                    ->label('Nomor Rekening')
                    ->numeric()
                    ->default(0),
                TextInput::make('nama_bank')
                    ->label('Nama Bank'),
                TextInput::make('npwp')
                    ->label('NPWP')
                    ->numeric()
                    ->default(0),
            ])->columns(3);
    }
}
