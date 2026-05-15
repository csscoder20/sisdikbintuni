<?php

namespace App\Filament\Resources\GtkKeuangan\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GtkKeuanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama GTK')
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('nama_bank_gaji')
                    ->label('Bank Rekening Gaji'),
                TextInput::make('no_rek_gaji')
                    ->label('Nomor Rekening Gaji'),
                TextInput::make('nama_bank_tunjangan')
                    ->label('Bank Rekening Tunjangan'),
                TextInput::make('no_rek_tunjangan')
                    ->label('Nomor Rekening Tunjangan'),
                TextInput::make('npwp')
                    ->label('NPWP')
                    ->columnSpanFull(),
            ]);
    }
}
